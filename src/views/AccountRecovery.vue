<template>
	<main>
		<div v-if="step == 1">
			<h1>Account Recovery</h1>
			Use this form if you forgot your username or password.
			If it matches any account we have on file you will receive a message containing the list of your account usernames
			along with a password reset link, should you wish to reset your password.
		</div>

		<div v-if="step == -1">
			<h1>reCAPTCHA could not be loaded</h1>
			This page requires reCAPTCHA but something prevents it from loading.
			If you are using an ad blocker or tracker blocker please whitelist this page and refresh to continue.
		</div>

		<div v-if="step == 1">
			<h1>Email address</h1>
			The email address that was used to register your account(s).

			<div class="error notFound" v-if="notFound">
				<h2>Not found</h2>
				We were not able to find any accounts associated with this email address.
			</div>

			<form @submit.prevent="checkEmail">
				<label for="email">Enter your email address:</label>
				<input @input="notFound = false" v-model="user.email" type="email" maxlength="39" id="email" ref="email" placeholder="you@mail.com" required>
				<button type="submit">Next step &rarr;</button>
			</form>
		</div>

		<div v-if="step == 2">
			<h1>Confirm</h1>
			<label for="c-email">Email address:</label>
			<input id="c-email" disabled readonly type="email" :value="user.email" placeholder="(no email)">
			<button @click="confirm">Recover account</button>
		</div>

		<div v-if="step == 3">
			<h1>Recovery process started</h1>
			An email was sent with the list of your accounts.

			<h1>Next steps</h1>
			If you wish to reset the password of your accounts, click the provided link in the email you received.<br>
			This link is only valid once: if you wish to reset more than one password you will have to repeat the process.

			<br><br><br>
			<h1>Can't find the account you were looking for?</h1>
			Double-check the email address you entered; You might have used a different address when you created the account or you might have omitted to provide an email address.

			<h1>Still need help?</h1>
			Feel free to <router-link :to="{ name: 'support' }">contact us</router-link> for further assistance.
		</div>

		<!-- PART TWO: -->

		<div v-if="step == -2">
			<h1>Expired link</h1>
			This password reset link has expired or is invalid.<br>
			Keep in mind that emailed links are only valid for 60 minutes.

			<h1>Start over</h1>
			You may try again in 5 minutes: <router-link :to="{ name: 'support' }">account recovery</router-link>
		</div>

		<div v-if="step == 4">
			<h1>Username</h1>
			Your password reset link is <em>only</em> valid for account usernames listed in the email that was sent to you.

			<form @submit.prevent="checkUser">
				<label for="user">Enter a username:</label>
				<input v-model="user.name" type="text" id="user" ref="user" placeholder="type your username here" minlength="4" maxlength="23" pattern="^[a-zA-Z0-9]{4,23}$" title="4-23 characters, alphanumeric" required>
				<button type="submit" v-if="user.name.length >= 4">Next step &rarr;</button>
			</form>
		</div>

		<div v-if="step == 5">
			<h1>Password</h1>
			Please choose a new, hard-to-guess password.
			<p>It must contain between 8 and 23 characters. Letters and numbers only. Case-sensitive.</p>

			<div v-if="exposed" class="exposed">
				<h2>WARNING: This password is compromised</h2>
				This password has previously appeared in a data breach. Please use a more secure alternative.
				<a href="https://haveibeenpwned.com/Passwords" target="_blank" rel="noopener">verified by haveibeenpwned.com</a>
			</div>

			<form @submit.prevent="checkPassword">
				<div class="pass-box">
					<label for="password">Choose a unique password:</label>
					<input v-model="user.pwd" :type="visible ? 'text' : 'password'" id="password" ref="password" placeholder="type your password here" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" required>
					<span @click="visible = !visible"></span>
				</div>
				<div class="pass-box">
					<label for="password2">Confirm your password:</label>
					<input v-model="user.pwd2" :type="visible ? 'text' : 'password'" id="password2" ref="password2" placeholder="type your password again" minlength="8" maxlength="23" pattern="^[a-zA-Z0-9]{8,23}$" title="8-23 characters, alphanumeric" required>
					<span @click="visible = !visible"></span>
				</div>
				<button type="submit" v-if="user.pwd && user.pwd === user.pwd2">Next step &rarr;</button>
			</form>
		</div>

		<div v-if="step == 6">
			<h1>Confirm</h1>
			<label for="c-user">Username:</label>
			<input id="c-user" disabled readonly type="text" :value="user.name">

			<div class="pass-box">
				<label for="c-pass">Password:</label>
				<input id="c-pass" disabled readonly :type="visible ? 'text' : 'password'" :value="user.pwd">
				<span @click="visible = !visible"></span>
			</div>
			<button @click="confirm2">Reset my password</button>
		</div>

		<div v-if="step == 7">
			<h1>The deed is done</h1>
			The password of account <q>{{user.name}}</q> has been reset.

			<h1>Next steps</h1>
			To start playing, <a href="https://wiki.themanaworld.org/index.php/Downloads">download ManaPlus</a> and select the server <i>The Mana World</i>
		</div>

		<div class="g-recaptcha" id="recaptcha-container"
			:data-sitekey="recaptcha_key"
			data-size="invisible">
		</div>

		<script2 src="https://www.google.com/recaptcha/api.js" unload="Reflect.deleteProperty(self, 'grecaptcha')"/>
	</main>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator"

@Component
export default class Recovery extends Vue {
	step = 1; // no Begin button here
	notFound = false; // no accounts found
	visible = false; // password is visible
	exposed = false; // password has been breached
	user = {
		email: "",
		name: "",
		pwd: "",
		pwd2: "",
	};

	emailToken = "";
	recaptcha_key = process.env.VUE_APP_RECAPTCHA;

	async mounted () {
		let token = document.location.hash.slice(1);

		if (Reflect.has(this.$route.params, "emailToken")) {
			token = this.$route.params.emailToken;
		}

		if (token.length > 1) {
			if (/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/i.test(token)) {
				this.emailToken = token;
				this.step = 4;
			} else {
				this.step = -2;
			}
		}

		// already loaded (user returned to this page)
		if (Reflect.has(self, "grecaptcha")) {
			await this.$nextTick();
			(self as any).grecaptcha.render("recaptcha-container", {
				sitekey: process.env.VUE_APP_RECAPTCHA,
				size: "invisible",
			});
			(self as any).grecaptcha.reset();
		}
	}

	async checkEmail () {
		this.step = Reflect.has(self, "grecaptcha") ? 2 : -1;
		// XXX: any actual checks needed here?
	}

	private sleep (milliseconds: number) {
		return new Promise(resolve => setTimeout(resolve, milliseconds));
	}

	async confirm () {
		(self as any).grecaptcha.execute();
		let token: string = "";

		// the recaptcha API doesn't play nice with Vue
		while (!(token = (self as any).grecaptcha.getResponse())) {
			await this.sleep(1000);
		}

		const req = new Request(`${process.env.VUE_APP_API}/tmwa/account`, {
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
				email: this.user.email,
			}),
		});

		const raw_response = await fetch(req);
		const response: string = await raw_response.text();

		switch (raw_response.status) {
			// TODO: don't use alerts: embed the error message on the page
			case 200:
			case 201:
				this.step = 3;
				break;
			case 400:
				self.alert("API: malformed request");
				document.location.reload();
				break;
			case 403:
				self.alert("Captcha validation failed.\nPlease try again later");
				document.location.reload();
				break;
			case 404:
				this.notFound = true;
				this.step = 1;
				(self as any).grecaptcha.reset();
				await this.$nextTick();
				(this.$refs.email as any).focus();
				break;
			case 408:
				this.step = -2;
				break;
			case 429:
				self.alert("Too many requests.\nPlease try again later");
				document.location.reload();
				break;
			case 500:
				self.alert("Internal server error.\nPlease try again later");
				document.location.reload();
				break;
			case 502:
				self.alert("Couldn't reach the server.\nPlease try again later");
				document.location.reload();
				break;
			default:
				self.alert(`Unknown error: ${raw_response.status}`);
				document.location.reload();
				break;
		}
	}

	async checkUser () {
		// TODO: check if the token is valid for this username
		this.step = Reflect.has(self, "grecaptcha") ? 5 : -1;
		await this.$nextTick();
		(this.$refs.password as any).focus();
	}

	// TODO: this is not compatible with Edge! we must polyfill
	private async sha1 (text: string) {
		const encoder = new TextEncoder();
		const data = encoder.encode(text);
		const buffer = await self.crypto.subtle.digest("SHA-1", data);
		return this.hexString(buffer);
	}

	// turns a subtlecrypto arraybuffer into a usable hex string
	private hexString (buffer: ArrayBuffer) {
		const byteArray = new Uint8Array(buffer);
		const hexCodes = Array.from(byteArray).map(value =>
			value.toString(16).padStart(2, "0"));

		return hexCodes.join("");
	}

	async checkPassword () {
		const full_hash = await this.sha1(this.user.pwd);
		const hash_prefix = full_hash.substring(0, 5);
		const hash_suffix = full_hash.substring(5);

		const req = new Request(`https://api.pwnedpasswords.com/range/${hash_prefix}`, {
			mode: "cors",
			cache: "force-cache",
			referrer: "no-referrer",
		});

		const raw_response = await fetch(req);
		const response: string = await raw_response.text();

		const found = response.split("\n").some(h => {
			const [hs, times] = h.split(":");
			return hash_suffix.toUpperCase() === hs.toUpperCase();
		});

		if (found) {
			// reset the animation
			if (this.exposed) {
				this.exposed = false;
				await this.$nextTick();
			}

			this.exposed = true;
			await this.$nextTick();
			(this.$refs.password as any).focus();
		} else {
			this.exposed = false;
			this.step = 6;
		}
	}

	async confirm2 () {
		(self as any).grecaptcha.execute();
		let token: string = "";

		// the recaptcha API doesn't play nice with Vue
		while (!(token = (self as any).grecaptcha.getResponse())) {
			await this.sleep(1000);
		}

		const req = new Request(`${process.env.VUE_APP_API}/tmwa/account`, {
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
				username: this.user.name,
				password: this.user.pwd,
				code: this.emailToken,
			}),
		});

		const raw_response = await fetch(req);
		const response: string = await raw_response.text();

		switch (raw_response.status) {
			// TODO: don't use alerts: embed the error message on the page
			case 200:
			case 201:
				this.step = 7;
				break;
			case 400:
				self.alert("API: malformed request");
				document.location.reload();
				break;
			case 403:
				self.alert("Captcha validation failed.\nPlease try again later");
				document.location.reload();
				break;
			case 404:
				self.alert("You are unauthorized to reset the password of this account.\nOnly accounts listed in the email you received can be reset.");
				this.$router.replace({ name: "support" });
				break;
			case 408:
				this.step = -2;
				break;
			case 429:
				self.alert("Too many requests.\nPlease try again later");
				document.location.reload();
				break;
			case 500:
				self.alert("Internal server error.\nPlease try again later");
				document.location.reload();
				break;
			case 502:
				self.alert("Couldn't reach the server.\nPlease try again later");
				document.location.reload();
				break;
			default:
				self.alert(`Unknown error: ${raw_response.status}`);
				document.location.reload();
				break;
		}
	}
}
</script>

<style scoped>
/*
TODO: share the stylesheet with Registration (DRY)
*/
form {
	margin-top: 20px;
}

main {
	& > h1 + div {
		margin-top: 30px;
	}

	& label {
		display: block;

		&:nth-of-type(1n + 2) {
			margin-top: 1em;
		}
	}

	& .pass-box {
		position: relative;

		&:nth-of-type(1n + 2) {
			margin-top: 1em;
		}
	}

	& input {
		width: calc(100% - 2ch);
		border: 1px solid #2f2e32;
		font-size: 15px;
		padding: 1ch;
		margin-top: 0.6ch;

		& + .pass-box {
			margin-top: 1em;
		}

		& + span {
			&::after {
				content: "👁";
				font-family: monospace;
				padding: 0 0.5ch 0 0.5ch;
			}

			position: absolute;
			right: -1px;
			top: auto;
			bottom: 0;
			font-size: 1.9em;
			cursor: pointer;
		}

		&[type="text"] + span {
			background: rgba(0, 0, 0, 0.2);
		}
	}

	& button {
		margin-top: 1em;
		width: 100%;
		background-color: #34B039;
		border: 1px solid #2f2e32;
		display: inline-block;
		cursor: pointer;
		color: #ffffff;
		font-size: 15px;
		font-weight: bold;
		padding: 1ch;
		text-decoration: none;

		&:hover {
			background-color: #2F9E33;
		}
	}

	& > div:nth-of-type(1n + 2) {
		margin-top: 30px;
	}

	& .exposed {
		background: rgba(255, 0, 0, 0.1);
		border: dashed 6px rgba(255, 0, 0, 0.9);
		padding: 1em;
		margin: 1em;
		animation-name: scary;
		animation-duration: 2s;

		& a {
			display: block;
			margin-top: 0.7em;
		}
	}

	& .error {
		padding: 1em;
	}
}

@keyframes scary {
	from {
		background-color: rgba(255, 0, 0, 0);
		border-color: rgba(255, 0, 0, 0);
	}

	to {
		background-color: rgba(255, 0, 0, 0.1);
		border-color: rgba(255, 0, 0, 0.9);
	}
}
</style>
