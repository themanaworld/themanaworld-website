<template>
	<main class="support">
		<h1>Support</h1>
		<p>Please select your issue below. If you cannot find your issue, contact us.</p>

		<h1>Account problems</h1>
		<ul>
			<li><router-link to="/recover/password">I forgot my password</router-link></li>
			<li><router-link to="/recover/username">I forgot my user name</router-link></li>
			<li><a href="https://forums.themanaworld.org/viewtopic.php?f=20&t=7559">My account is banned</a></li>
			<li><a href="https://forums.themanaworld.org/viewtopic.php?f=20&t=6472">My account was compromised</a></li>
		</ul>

		<h1 id="contact">Contact us</h1>
		<p>On IRC: <a href="https://forums.themanaworld.org/viewforum.php?f=41" target="_blank" rel="noopener">#themanaworld on Libera Chat</a></p>
		<p>On Discord: <a href="https://forums.themanaworld.org/viewforum.php?f=65" target="_blank" rel="noopener">The Mana World server</a></p>
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
