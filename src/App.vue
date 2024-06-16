<template>
	<div v-if="globalStatus" class="dialog" v-html="globalStatus"></div>
	<Logo v-else class="header"/>
	<Navigation class="nav"/>
	<router-view v-if="loaded" class="content"/>
	<main v-else class="content" role="alert" aria-busy="true">
		<h1>&nbsp;</h1>
		<p>Loading...</p>
	</main>
	<Copyright class="footer"/>
</template>

<style>
:root {
	background: gray(95);
}
#app {
	z-index: 100;
	& > .nav {
		grid-area: side;
	}
	& > .header {
		grid-area: logo;
	}
	& > .content {
		grid-area: page;
		background: #E1D6CF;
		padding: 15px 15px 30px 15px;
		border-top: 1px solid #2f2e32;
		text-align: justify;
		line-height: 1.5em;
		& h1 {
			margin: 20px 0 0 0 0;
			font-weight: bold;
			font-size: 1.3em;
			border-bottom: 1px solid #9f9894;
			color: gray(24);
			&:nth-of-type(1n + 2) {
				margin-top: 2em;
			}
		}
	}
	& > .footer {
		grid-area: footer;
	}

	& .dialog {
		background: #ff0000;
		color: #fff;
		padding: 1em;
		display: block;
		font-size: 1.5rem;
		margin: 1rem;
		z-index: 10000;
		grid-area: logo;
		border: 5px gray(95) dotted;
	}

	font-family: sans-serif;
	color: #2c3e50;
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
	display: grid;
	grid-template-areas:
		"logo"
		"page"
		"side"
		"footer";
}
@media (min-width: 1100px) {
	#app {
		grid-column-gap: 0em;
		grid-row-gap: 0px;
		grid-template-columns: 2fr 4fr;
		grid-template-areas:
			"logo logo"
			"page side"
			"footer footer";
		& > .content {
			background: url(assets/page_footer.webp) no-repeat left bottom #E1D6CF;
			min-width: 870px;
			padding-bottom: 200px;
			border: 1px solid #2f2e32;
			border-right: none;
			border-radius: 0 0 0 2.5ch;
			& p {
				margin-left: 30px;
				margin-right: 40px;
			}
		}
	}
}
</style>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import Navigation from "@/components/Navigation.vue";
import Logo from "@/components/Logo.vue";
import Copyright from "@/components/Footer.vue";

@Options({
	components: {
		Navigation,
		Logo,
		Copyright,
	},
})
export default class AppV extends Vue {
	loaded = false;

	globalStatus = process.env.VUE_APP_STATUS?.trim() ?? "";

	mounted (): void {
		self.addEventListener("initial-load", () => {
			this.loaded = true;
		});
	}
}
</script>
