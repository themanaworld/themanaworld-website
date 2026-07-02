// Account form handling: registration, account recovery and migration
// (port of the former Registration.vue, AccountRecovery.vue and Migration.vue)
(function () {
	"use strict";

	var config = self.tmwConfig;

	/* ---------- reCAPTCHA (loaded only after the user opts in) ---------- */

	var recaptchaPromise = null;

	function loadRecaptcha() {
		if (recaptchaPromise) {
			return recaptchaPromise;
		}

		recaptchaPromise = new Promise(function (resolve, reject) {
			if (self.grecaptcha) {
				resolve(self.grecaptcha);
				return;
			}

			self.onRecaptchaLoad = function () {
				delete self.onRecaptchaLoad;
				resolve(self.grecaptcha);
			};

			var el = document.createElement("script");
			el.async = true;
			el.src = "https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit";
			el.addEventListener("error", reject);
			el.addEventListener("abort", reject);
			document.head.appendChild(el);
		});

		return recaptchaPromise;
	}

	// When the consent checkbox of a form is ticked, load reCAPTCHA, render
	// an invisible widget and enable the submit button.
	function setupConsent(form) {
		var consent = form.querySelector(".recaptcha-consent");
		var container = form.querySelector(".g-recaptcha");
		var submit = form.querySelector("button[type=submit]");
		var failure = form.querySelector(".recaptcha-error");
		var state = { widget: null };

		consent.addEventListener("change", function () {
			if (!consent.checked) {
				submit.disabled = true;
				return;
			}

			if (state.widget !== null) {
				submit.disabled = false;
				return;
			}

			loadRecaptcha().then(function (grecaptcha) {
				grecaptcha.ready(function () {
					state.widget = grecaptcha.render(container, {
						sitekey: config.recaptcha,
						size: "invisible",
					});
					submit.disabled = false;
				});
			}).catch(function () {
				failure.hidden = false;
				consent.checked = false;
			});
		});

		return state;
	}

	function sleep(milliseconds) {
		return new Promise(function (resolve) {
			setTimeout(resolve, milliseconds);
		});
	}

	async function captchaToken(state) {
		var grecaptcha = await loadRecaptcha();
		grecaptcha.execute(state.widget);
		var token = "";

		// the recaptcha API does not signal completion: poll for the token
		while (!(token = grecaptcha.getResponse(state.widget))) {
			await sleep(1000);
		}

		return token;
	}

	function captchaReset(state) {
		if (self.grecaptcha && state.widget !== null) {
			self.grecaptcha.reset(state.widget);
		}
	}

	/* ---------- password helpers ---------- */

	// turns a subtlecrypto arraybuffer into a usable hex string
	function hexString(buffer) {
		return Array.from(new Uint8Array(buffer)).map(function (value) {
			return value.toString(16).padStart(2, "0");
		}).join("");
	}

	async function sha1(text) {
		var data = new TextEncoder().encode(text);
		var buffer = await self.crypto.subtle.digest("SHA-1", data);
		return hexString(buffer);
	}

	// checks the password against the Pwned Passwords range API
	async function isPasswordExposed(password) {
		var fullHash = await sha1(password);
		var hashPrefix = fullHash.substring(0, 5);
		var hashSuffix = fullHash.substring(5);

		var req = new Request("https://api.pwnedpasswords.com/range/" + hashPrefix, {
			mode: "cors",
			cache: "force-cache",
			referrer: "no-referrer",
		});

		try {
			var response = await (await fetch(req)).text();

			return response.split("\n").some(function (line) {
				return hashSuffix.toUpperCase() === line.split(":")[0].toUpperCase();
			});
		} catch (err) {
			// the check is best-effort: do not block registration when the
			// API is unreachable
			return false;
		}
	}

	function showExposed(form) {
		var box = form.querySelector(".exposed");

		// restart the animation when the warning is already visible
		box.hidden = true;
		void box.offsetWidth;
		box.hidden = false;

		form.querySelector("input[name=password]").focus();
	}

	function passwordsMatch(form) {
		var password2 = form.querySelector("input[name=password2]");
		var matches = form.querySelector("input[name=password]").value === password2.value;
		password2.setCustomValidity(matches ? "" : "The passwords do not match.");
		return matches;
	}

	/* ---------- shared form helpers ---------- */

	// password visibility toggles (shared state per form, like the old site)
	function setupEyeToggles(form) {
		var visible = false;
		var boxes = form.querySelectorAll(".pass-box");

		function update() {
			boxes.forEach(function (box) {
				var input = box.querySelector("input");
				var toggle = box.querySelector("span[role=button]");
				input.type = visible ? "text" : "password";
				toggle.title = (visible ? "hide" : "show") + " password";
				toggle.setAttribute("aria-pressed", String(visible));
			});
		}

		boxes.forEach(function (box) {
			box.querySelector("span[role=button]").addEventListener("click", function () {
				visible = !visible;
				update();
			});
		});
	}

	function showError(form, message) {
		var box = form.querySelector(".form-error");
		box.textContent = message;
		box.hidden = false;
	}

	function clearError(form) {
		var box = form.querySelector(".form-error");
		box.textContent = "";
		box.hidden = true;
	}

	function apiError(status) {
		switch (status) {
			case 400:
				return "API: malformed request";
			case 403:
				return "Captcha validation failed.\nPlease try again later";
			case 425:
				return "An email has already been sent.\nPlease check your inbox and wait before you try again.";
			case 429:
				return "Too many requests.\nPlease try again later";
			case 500:
				return "Internal server error.\nPlease try again later";
			case 502:
				return "Couldn't reach the server.\nPlease try again later";
			default:
				return "Unknown error: " + status;
		}
	}

	function show(id) {
		document.getElementById(id).hidden = false;
	}

	function hide(id) {
		document.getElementById(id).hidden = true;
	}

	/* ---------- registration ---------- */

	var registerForm = document.getElementById("register-form");

	if (registerForm) {
		var registerCaptcha = setupConsent(registerForm);
		setupEyeToggles(registerForm);

		var takenBox = document.getElementById("username-taken");
		registerForm.elements.username.addEventListener("input", function () {
			takenBox.hidden = true;
		});

		registerForm.addEventListener("submit", async function (event) {
			event.preventDefault();
			clearError(registerForm);

			passwordsMatch(registerForm);
			if (!registerForm.reportValidity()) {
				return;
			}

			if (await isPasswordExposed(registerForm.elements.password.value)) {
				showExposed(registerForm);
				return;
			}

			var token = await captchaToken(registerCaptcha);

			var req = new Request(config.api + "/tmwa/account", {
				method: "POST",
				mode: "cors",
				cache: "no-cache",
				redirect: "follow",
				referrer: "no-referrer",
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
					"X-CAPTCHA-TOKEN": token,
				},
				body: JSON.stringify({
					username: registerForm.elements.username.value,
					password: registerForm.elements.password.value,
					email: registerForm.elements.email.value,
				}),
			});

			var response;
			try {
				response = await fetch(req);
			} catch (err) {
				showError(registerForm, apiError(502));
				captchaReset(registerCaptcha);
				return;
			}

			switch (response.status) {
				case 201:
					registerForm.hidden = true;
					show("register-success");
					document.getElementById("register-success").scrollIntoView({ block: "nearest", behavior: "smooth" });
					break;
				case 409:
					takenBox.hidden = false;
					captchaReset(registerCaptcha);
					registerForm.elements.username.focus();
					break;
				default:
					showError(registerForm, apiError(response.status));
					captchaReset(registerCaptcha);
					break;
			}
		});
	}

	/* ---------- account recovery ---------- */

	var recoveryForm = document.getElementById("recovery-form");
	var resetForm = document.getElementById("reset-form");
	var emailToken = "";

	if (recoveryForm) {
		// an emailed reset link carries a token, either in the fragment or,
		// on the old site, in the path (the 404 page forwards the latter)
		var token = self.location.hash.slice(1);
		var match = self.location.pathname.match(/^\/recover\/(?:password|username)\/(.+)$/);

		if (match) {
			token = decodeURIComponent(match[1]);
		}

		if (token.startsWith("/")) {
			token = token.slice(1);
		}

		if (token.length > 1) {
			if (/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/i.test(token)) {
				emailToken = token;
				hide("recovery-intro");
				recoveryForm.hidden = true;
				show("reset-section");
			} else {
				recoveryForm.hidden = true;
				show("reset-expired");
			}
		}
	}

	if (recoveryForm && !recoveryForm.hidden) {
		var recoveryCaptcha = setupConsent(recoveryForm);

		var notFoundBox = document.getElementById("email-not-found");
		recoveryForm.elements.email.addEventListener("input", function () {
			notFoundBox.hidden = true;
		});

		recoveryForm.addEventListener("submit", async function (event) {
			event.preventDefault();
			clearError(recoveryForm);

			if (!recoveryForm.reportValidity()) {
				return;
			}

			var token = await captchaToken(recoveryCaptcha);

			var req = new Request(config.api + "/tmwa/account", {
				method: "PUT",
				mode: "cors",
				cache: "no-cache",
				redirect: "follow",
				referrer: "no-referrer",
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
					"X-CAPTCHA-TOKEN": token,
				},
				body: JSON.stringify({
					email: recoveryForm.elements.email.value,
				}),
			});

			var response;
			try {
				response = await fetch(req);
			} catch (err) {
				showError(recoveryForm, apiError(502));
				captchaReset(recoveryCaptcha);
				return;
			}

			switch (response.status) {
				case 200:
				case 201:
					recoveryForm.hidden = true;
					show("recovery-sent");
					break;
				case 404:
					notFoundBox.hidden = false;
					captchaReset(recoveryCaptcha);
					recoveryForm.elements.email.focus();
					break;
				case 408:
					recoveryForm.hidden = true;
					show("reset-expired");
					break;
				default:
					showError(recoveryForm, apiError(response.status));
					captchaReset(recoveryCaptcha);
					break;
			}
		});
	}

	/* ---------- password reset (with emailed token) ---------- */

	if (resetForm) {
		var resetCaptcha = setupConsent(resetForm);
		setupEyeToggles(resetForm);

		resetForm.addEventListener("submit", async function (event) {
			event.preventDefault();
			clearError(resetForm);

			passwordsMatch(resetForm);
			if (!resetForm.reportValidity()) {
				return;
			}

			if (await isPasswordExposed(resetForm.elements.password.value)) {
				showExposed(resetForm);
				return;
			}

			var token = await captchaToken(resetCaptcha);

			var req = new Request(config.api + "/tmwa/account", {
				method: "PUT",
				mode: "cors",
				cache: "no-cache",
				redirect: "follow",
				referrer: "no-referrer",
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
					"X-CAPTCHA-TOKEN": token,
				},
				body: JSON.stringify({
					username: resetForm.elements.username.value,
					password: resetForm.elements.password.value,
					code: emailToken,
				}),
			});

			var response;
			try {
				response = await fetch(req);
			} catch (err) {
				showError(resetForm, apiError(502));
				captchaReset(resetCaptcha);
				return;
			}

			switch (response.status) {
				case 200:
				case 201:
					document.getElementById("reset-account").textContent = resetForm.elements.username.value;
					hide("reset-section");
					show("reset-success");
					break;
				case 404:
					showError(resetForm, "You are unauthorized to reset the password of this account.\nOnly accounts listed in the email you received can be reset.");
					captchaReset(resetCaptcha);
					break;
				case 408:
					hide("reset-section");
					show("reset-expired");
					break;
				default:
					showError(resetForm, apiError(response.status));
					captchaReset(resetCaptcha);
					break;
			}
		});
	}

	/* ---------- account migration (TMW Legacy to Vault) ---------- */

	var migrationForm = document.getElementById("migration-form");

	if (migrationForm) {
		var migrationCaptcha = setupConsent(migrationForm);

		migrationForm.addEventListener("submit", async function (event) {
			event.preventDefault();
			clearError(migrationForm);

			if (!migrationForm.reportValidity()) {
				return;
			}

			var token = await captchaToken(migrationCaptcha);

			var req = new Request(config.pyapi + "/tmwa_auth", {
				method: "POST",
				mode: "no-cors",
				cache: "no-cache",
				redirect: "follow",
				referrer: "no-referrer",
				headers: {
					"Accept": "application/json",
					"Content-Type": "application/json",
					"X-CAPTCHA-TOKEN": token,
				},
				body: JSON.stringify({
					mail: migrationForm.elements.email.value,
					user: migrationForm.elements.user.value,
					pass: migrationForm.elements.pass.value,
					totp: migrationForm.elements.totp.checked,
				}),
			});

			var response;
			try {
				response = await fetch(req);
			} catch (err) {
				showError(migrationForm, apiError(502));
				captchaReset(migrationCaptcha);
				return;
			}

			// no-cors requests yield an opaque response (status 0), which the
			// old site also treated as success
			switch (response.status) {
				case 0:
				case 200:
				case 201:
					migrationForm.hidden = true;
					show("migration-success");
					break;
				default:
					showError(migrationForm, apiError(response.status));
					captchaReset(migrationCaptcha);
					break;
			}
		});
	}
})();
