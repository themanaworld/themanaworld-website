<template>
	<router-link role="banner" :to="{ name: 'home' }" class="logo" title="Go to home page" aria-label="go back to the home page">
		<span>The </span>Mana World
		<!--<span>Feel the mana power growing inside you</span>-->
		<span>A free open source 2D MMORPG in development</span>
	</router-link>
</template>

<style scoped>
@font-face {
	font-family: "Albertus TMW";
	font-display: swap;
	src: url("../assets/fonts/AlbertusTMW.woff2") format("woff2"),
		url("../assets/fonts/AlbertusTMW.woff") format("woff"),
		url("../assets/fonts/AlbertusTMW.ttf") format("truetype");
}
.logo {
	/* this is all relative because our mobile site has to be responsive */
	background: url(../assets/logo.svg) no-repeat left top; /* FIXME: the -small logo is fugly */
	background-size: 12vw 12vw;
	padding: 2vw 0 0 12vw;
	font-family: "Albertus TMW", "Arial Black", "Times New Roman", fantasy;
	font-size: 7vw;
	text-shadow: 0.03ch 0.07ch #070905;
	text-decoration: none;
	color: #34B039;
	height: 11vw;
	cursor: pointer;
	z-index: 200;
	max-width: calc(90% - 15vw);
	&::selection {
		text-shadow: none;
	}
	& span:last-of-type {
		display: none;
	}
}
@media (min-width: 800px) {
	.logo {
		background-image: url(../assets/logo.svg);
		background-size: 120px 120px;
		padding: 30px 0 0 125px;
		font-size: 4em;
		height: 100px;
		margin-top: 20px;
		position: relative;
		& span:first-of-type {
			position: absolute;
			left: 125px;
			top: 0;
			font-size: 0.6em;
		}
		& span:last-of-type {
			display: inline;
			position: absolute;
			font-family: Helvetica;
			font-size: 0.2em;
			letter-spacing: 2px;
			top: 99px;
			left: 128px;
			text-shadow: none;
			color: #616260;
			font-style: oblique;
		}
	}
}
@media (max-width: 300px) {
	.logo {
		background-image: url(../assets/logo-extrasmall.svg);
		background-size: 12vw 12vw;
		font-weight: bold;
		text-shadow: none;
	}
}
@media (min-width: 1100px) {
	.logo {
		max-width: 100%;
	}
}
</style>
