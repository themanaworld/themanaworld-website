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
		var state = { widget: null, pending: null };

		// deliver the widget's verdict to a waiting captchaToken()
		function settle(token) {
			var resolve = state.pending;
			state.pending = null;
			if (resolve) {
				resolve(token);
			}
		}

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
						callback: settle,
						"error-callback": function () {
							settle(null);
						},
					});
					submit.disabled = false;

					// start verification right away so the token is
					// usually ready by the time the form is submitted
					// (captchaToken() re-runs it when it has expired)
					grecaptcha.execute(state.widget);
				});
			}).catch(function () {
				failure.hidden = false;
				consent.checked = false;
			});
		});

		return state;
	}

	// runs the invisible widget; resolves with a token as soon as the
	// widget callback delivers one, or with null after reporting a
	// failed verification on the form
	async function captchaToken(form, state) {
		var grecaptcha = await loadRecaptcha();

		var token = grecaptcha.getResponse(state.widget);
		if (!token) {
			token = await new Promise(function (resolve) {
				state.pending = resolve;
				grecaptcha.execute(state.widget);
			});
		}

		if (!token) {
			showError(form, "reCAPTCHA verification failed.\nPlease try again");
			captchaReset(state);
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

	/* ---------- shared form helpers ---------- */

	// password visibility toggles
	function setupEyeToggles(form) {
		form.querySelectorAll(".password-box").forEach(function (box) {
			var input = box.querySelector("input");
			var toggle = box.querySelector(".toggle-password");

			toggle.addEventListener("click", function () {
				var visible = input.type === "password";
				input.type = visible ? "text" : "password";
				toggle.setAttribute("aria-pressed", String(visible));
				toggle.setAttribute("aria-label", (visible ? "hide" : "show") + " password");
			});
		});
	}

	// disable the submit button while a submission is in flight, so the
	// form reacts immediately and cannot be submitted twice
	function setBusy(form, busy) {
		var submit = form.querySelector("button[type=submit]");

		if (busy) {
			submit.dataset.label = submit.textContent;
			submit.textContent = "Please wait…";
			submit.disabled = true;
			form.setAttribute("aria-busy", "true");
		} else {
			submit.textContent = submit.dataset.label || submit.textContent;
			submit.disabled = false;
			form.removeAttribute("aria-busy");
		}
	}

	// shared submit flow: validate, then show the busy state while the
	// handler runs (a handler that succeeds hides the form, so always
	// re-enabling it afterwards only matters on error paths)
	function handleSubmit(form, handler) {
		form.addEventListener("submit", async function (event) {
			event.preventDefault();
			clearError(form);

			if (!form.reportValidity()) {
				return;
			}

			setBusy(form, true);
			try {
				await handler();
			} finally {
				setBusy(form, false);
			}
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

	// sends the request; a network failure is reported on the form and
	// yields null
	async function sendRequest(form, state, req) {
		try {
			return await fetch(req);
		} catch (err) {
			showError(form, apiError(502));
			captchaReset(state);
			return null;
		}
	}

	function show(id) {
		document.getElementById(id).hidden = false;
	}

	// show a result section and move focus to it, so that the outcome is
	// announced to assistive technology and in view
	function reveal(id) {
		var el = document.getElementById(id);
		el.hidden = false;
		el.focus();
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
			registerForm.elements.username.removeAttribute("aria-invalid");
		});

		handleSubmit(registerForm, async function () {
			if (await isPasswordExposed(registerForm.elements.password.value)) {
				showExposed(registerForm);
				return;
			}

			var token = await captchaToken(registerForm, registerCaptcha);
			if (!token) {
				return;
			}

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

			var response = await sendRequest(registerForm, registerCaptcha, req);
			if (!response) {
				return;
			}

			switch (response.status) {
				case 201:
					hide("register-section");
					reveal("register-success");
					break;
				case 409:
					takenBox.hidden = false;
					registerForm.elements.username.setAttribute("aria-invalid", "true");
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

	var recoverySection = document.getElementById("recovery-section");
	var recoveryForm = document.getElementById("recovery-form");
	var resetForm = document.getElementById("reset-form");
	var emailToken = "";

	if (recoveryForm) {
		// an emailed reset link carries a token in the fragment (the
		// webserver forwards SPA-era path tokens here)
		var token = self.location.hash.slice(1);

		if (token.startsWith("/")) {
			token = token.slice(1);
		}

		if (token.length > 1) {
			hide("recovery-section");

			if (/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/i.test(token)) {
				emailToken = token;
				show("reset-section");
			} else {
				show("reset-expired");
			}
		}
	}

	if (recoveryForm && !recoverySection.hidden) {
		var recoveryCaptcha = setupConsent(recoveryForm);

		var notFoundBox = document.getElementById("email-not-found");
		recoveryForm.elements.email.addEventListener("input", function () {
			notFoundBox.hidden = true;
			recoveryForm.elements.email.removeAttribute("aria-invalid");
		});

		handleSubmit(recoveryForm, async function () {
			var token = await captchaToken(recoveryForm, recoveryCaptcha);
			if (!token) {
				return;
			}

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

			var response = await sendRequest(recoveryForm, recoveryCaptcha, req);
			if (!response) {
				return;
			}

			switch (response.status) {
				case 200:
				case 201:
					hide("recovery-section");
					reveal("recovery-sent");
					break;
				case 404:
					notFoundBox.hidden = false;
					recoveryForm.elements.email.setAttribute("aria-invalid", "true");
					captchaReset(recoveryCaptcha);
					recoveryForm.elements.email.focus();
					break;
				case 408:
					hide("recovery-section");
					reveal("reset-expired");
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

		handleSubmit(resetForm, async function () {
			if (await isPasswordExposed(resetForm.elements.password.value)) {
				showExposed(resetForm);
				return;
			}

			var token = await captchaToken(resetForm, resetCaptcha);
			if (!token) {
				return;
			}

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

			var response = await sendRequest(resetForm, resetCaptcha, req);
			if (!response) {
				return;
			}

			switch (response.status) {
				case 200:
				case 201:
					document.getElementById("reset-account").textContent = resetForm.elements.username.value;
					hide("reset-section");
					reveal("reset-success");
					break;
				case 404:
					showError(resetForm, "You are unauthorized to reset the password of this account.\nOnly accounts listed in the email you received can be reset.");
					captchaReset(resetCaptcha);
					break;
				case 408:
					hide("reset-section");
					reveal("reset-expired");
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
		setupEyeToggles(migrationForm);

		handleSubmit(migrationForm, async function () {
			var token = await captchaToken(migrationForm, migrationCaptcha);
			if (!token) {
				return;
			}

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

			var response = await sendRequest(migrationForm, migrationCaptcha, req);
			if (!response) {
				return;
			}

			// no-cors requests yield an opaque response (status 0), which the
			// old site also treated as success
			switch (response.status) {
				case 0:
				case 200:
				case 201:
					hide("migration-section");
					reveal("migration-success");
					break;
				default:
					showError(migrationForm, apiError(response.status));
					captchaReset(migrationCaptcha);
					break;
			}
		});
	}
})();
