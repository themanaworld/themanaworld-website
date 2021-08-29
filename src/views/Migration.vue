<template>
	<main>
		<div v-if="nextStep != 4">
			<h1>Account Migration</h1>
			<p>Use this form to migrate a TMW Legacy account to Vault.</p>
			<p>Please note you may only have <b>one</b> Vault account.</p>
			<p>Once complete, you'll need the Mana Launcher to login.</p>
		</div>

		<div v-if="step == -3">
			<h1>reCAPTCHA privacy notice</h1>
			<p>This page uses Google reCAPTCHA. By continuing, you agree to use reCAPTCHA, which may register tracking cookies in your browser.</p>
			<p>You may review the Google Privacy Policy at <a href="https://policies.google.com/privacy">https://policies.google.com/privacy</a>.</p>

			<button @click="start">I understand and wish to proceed</button>
			<br><br>

			<h1>Proceeding without reCAPTCHA</h1>
			<p>If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.</p>
			<br>
			<address>support@themanaworld.org</address>
		</div>

		<div v-if="step == -4">
			<h1>Loading...</h1>
			<p>Please wait while reCAPTCHA is loading...</p>
			<br><br>

			<h1>Proceeding without reCAPTCHA</h1>
			<p>If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.</p>
			<br>
			<address>support@themanaworld.org</address>
		</div>

		<div v-if="step == -1">
			<h1>reCAPTCHA could not be loaded</h1>
			<p>This page requires reCAPTCHA but something prevents it from loading.
			If you are using an ad blocker or tracker blocker please whitelist this page and refresh to continue.</p>

			<h1>Proceeding without reCAPTCHA</h1>
			<p>If you would rather not use reCAPTCHA, you may recover your account by contacting us by email.</p>
			<br>
			<address>support@themanaworld.org</address>
		</div>

		<div v-if="step == 1">
			<h1>TMW Legacy Data</h1>
			<p>Do note it validates the username, email and password.</p>
			<p>Your Vault Password will be set to the same password you've used in TMW Legacy. Your Vault Username is your email.</p>
			<p>If you opt for 2 Factor Authentication, you'll receive by email the secret to configure an authentication app e.g. Google Authenticator.</p>
			<p>Once enabled, you will not be able to login without it.</p>

			<form @submit.prevent="checkEmail">
				<label for="email">Enter your email address:</label>
				<input v-model="user.email" type="email" maxlength="39" id="email" ref="_email" placeholder="you@mail.com" required>
				<label for="user">Enter your username:</label>
				<input v-model="user.name" type="text" maxlength="39" id="user" ref="_user" placeholder="username" required>
				<label for="password">Enter your password:</label>
				<input v-model="user.pwd" type="password" maxlength="39" id="password" ref="_password" placeholder="password" required>
				<label for="totp">Enable 2FA?</label>
				<input v-model="user.totp" type="checkbox" id="totp" ref="_totp" checked>
				<button type="submit">Submit &rarr;</button>
			</form>
		</div>

		<div v-if="step == 2">
			<h1>Confirm</h1>
			<label for="c-email">Email address:</label>
			<input id="c-email" disabled readonly type="email" :value="user.email" placeholder="(no email)">
			<button @click="confirm">Confirm</button>
		</div>

		<div v-if="step == 3">
			<h1>Migration process started</h1>
			<p>Your account migration has been requested.</p>

			<h1>Next steps</h1>
			<p>If everything went well, you'll be able to login on Mana Launcher using the provided email and password.</p>
			<p>If you enabled 2FA, you'll also need the token which has been sent to your email.</p>

			<br><br><br>
			<h1>Can't find the account you were looking for?</h1>
			<p>If data inserted here is incorrect or a Vault account already exists bound to that email, the migration will not happen.</p>

			<h1>Still need help?</h1>
			<p>Feel free to <router-link :to="{ name: 'support' }">contact us</router-link> for further assistance.</p>
		</div>

		<div class="g-recaptcha" id="recaptcha-container"
			:data-sitekey="recaptcha_key"
			data-size="invisible">
		</div>
	</main>
</template>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import reCAPTCHA from "@/reCAPTCHA";

@Options({})
export default class Recovery extends Vue {
	step = -3; // ask to use reCAPTCHA
	nextStep = 1; // first step after reCAPTCHA confirmation
	user = {
		email: "",
		name: "",
		totp: false,
		pwd: "",
	};

	recaptcha_key = process.env.VUE_APP_RECAPTCHA;

	async mounted () {
		// already loaded (user returned to this page)
		if (reCAPTCHA.isReady) {
			if (this.step == -3) {
				this.step = this.nextStep;
			}

			await this.$nextTick();
			reCAPTCHA.instance.render("recaptcha-container", {
				sitekey: process.env.VUE_APP_RECAPTCHA,
				size: "invisible",
			});
			reCAPTCHA.instance.reset();

			if (this.step == 1) {
				(this.$refs._email as HTMLInputElement).focus();
			}
		}
	}

	async start () {
		this.step = -4;

		try {
			await reCAPTCHA.load();
			this.step = this.nextStep;
			await this.$nextTick();

			if (this.step == 1) {
				(this.$refs._email as HTMLInputElement).focus();
			}
		} catch (err) {
			this.step = -1
		}
	}

	async checkEmail () {
		this.step = reCAPTCHA.isReady ? 2 : -1;
		// XXX: any actual checks needed here?
	}

	private sleep (milliseconds: number) {
		return new Promise(resolve => setTimeout(resolve, milliseconds));
	}

	async confirm () {
		reCAPTCHA.instance.execute();
		let token = "";
		console.log("Confirmed")

		// the recaptcha API doesn't play nice with Vue
		while (!(token = reCAPTCHA.instance.getResponse())) {
			await this.sleep(1000);
		}
		console.log("Captcha OK")

		// ${process.env.VUE_APP_API}
		// https://api.tmw2.org:13370/
		const req = new Request(`${process.env.VUE_APP_PYAPI}/tmwa_auth`, {
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
				mail: this.user.email,
				user: this.user.name,
				pass: this.user.pwd,
				totp: this.user.totp,
			}),
		});
		console.log("Req OK")

		const rawResponse = await fetch(req);
		//const response: string = await rawResponse.text();
		console.log("Reply obtained")

		// FIXME: Not caught, always cause error 0
		switch (rawResponse.status) {
			// TODO: don't use alerts: embed the error message on the page
			case 0:
				this.step = 3;
				break;
			case 200:
			case 201:
				this.step = 3;
				break;
			case 400:
				self.alert("API: malformed request");
				document.location.reload();
				break;
			case 403:
				self.alert("Captcha or account validation failed.\nPlease try again later");
				document.location.reload();
				break;
			case 404:
				this.step = 1;
				reCAPTCHA.instance.reset();
				await this.$nextTick();
				(this.$refs._email as HTMLInputElement).focus();
				break;
			case 408:
				this.step = -4;
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
				self.alert(`Unknown error: ${rawResponse.status}`);
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
