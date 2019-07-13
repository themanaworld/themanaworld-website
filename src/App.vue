<template>
	<div id="app">
		<Logo class="header"/>
		<Navigation class="nav"/>
		<router-view class="content"/>
		<Copyright class="footer"/>
	</div>
</template>

<style>
/*
	we might want to consider Normalize
*/

:root {
	background: gray(95);
}

#app {
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
		border-radius: 15px 15px 0 0;

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

	font-family: Helvetica, Arial, sans-serif;
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
			background: url(assets/page_footer.png) no-repeat left bottom #E1D6CF;
			min-width: 890px;
			padding-bottom: 200px;
			border-radius: 15px 0 0 15px;
		}
	}
}
</style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import Navigation from "@/components/Navigation.vue";
import Logo from "@/components/Logo.vue";
import Copyright from "@/components/Footer.vue";

@Component({
	components: {
		Navigation,
		Logo,
		Copyright,
	},
})
export default class AppV extends Vue {}
</script>
