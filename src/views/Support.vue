<template>
	<main class="support">
		<h1>Support</h1>
        <div class="dialog">
                <b>NOTICE:</b> Due the crescent escalation in the conflict between Russia, Europe and US, some email-based features may eventually not work.<br/>
                Please contact us on IRC, Matrix, Discord or ingame if you're unable to reset your password on the web portal, or if GDPR request emails fail to be delivered.
        </div>
        <div class="dialog">
                <b>NOTICE:</b> We are currently facing techinical issues on the backend, which may prevent you from filling the CAPTCHA.<br/>
                Password recovery and account creation may not be working from the web interface.
        </div>
		<p>Please select your issue below. If you cannot find your issue, contact us.</p>

		<h1>Account problems</h1>
		<ul>
			<li><router-link to="/recover/password">I forgot my password</router-link></li>
			<li><router-link to="/recover/username">I forgot my user name</router-link></li>
			<li><a href="https://forums.themanaworld.org/viewtopic.php?f=20&t=7559">My account is banned</a></li>
			<li><a href="https://forums.themanaworld.org/viewtopic.php?f=20&t=6472">My account was compromised</a></li>
		</ul>

		<h1 id="contact">Contact us</h1>
		<p>On IRC: <a href="https://web.libera.chat/?channels=#themanaworld" target="_blank" rel="noopener nofollow">#themanaworld on Libera Chat</a></p>
		<p>On Discord: <a href="https://discord.com/invite/UnY77dR" target="_blank" rel="noopener nofollow">The Mana World server</a></p>
		<p>On Matrix: <a href="https://matrix.to/#/#tmw:matrix.org" target="_blank" rel="noopener nofollow">TMW Matrix server</a></p>
		<p>On the forums: <a href="https://forums.themanaworld.org/viewforum.php?f=3">Support and Bug reports</a></p>

		<h1>Technical contacts</h1>
		Legal inquiries: <address>legal@themanaworld.org</address>
		DMCA takedown requests: <address>legal@themanaworld.org</address>
		GDPR requests: <address>data@themanaworld.org</address>
		Security disclosures:
		<address>
			security@themanaworld.org
			<span v-if="PGP">(PGP: <a :href="`http://pgp.mit.edu/pks/lookup?op=get&search=${PGP}`" rel="noopener">{{PGP}}</a>)</span>
		</address>
		<br/>
		<span style="font-size: smaller;"><i>The Mana World does <b>not</b> provide legal advise.</i></span>
	</main>
</template>

<style scoped>
address {
	font-family: monospace;

	&:not(:last-of-type) {
		margin-bottom: 1.5em;
	}
}
</style>

<script lang="ts">
import { Options, Vue } from "vue-class-component";

@Options({})
export default class Copyright extends Vue {
	PGP = process.env.VUE_APP_PGP;
}
</script>
