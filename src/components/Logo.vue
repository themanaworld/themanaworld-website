<template>
	<router-link tag="div" :to="{ name: 'home' }" class="logo">
		The Mana World
		<!--<span>Feel the mana power growing inside you</span>-->
		<span>A free open source 2D MMORPG in development</span>
	</router-link>
</template>

<style scoped>
/*
 XXX: I couldn't find the font usued in the original PNG logo so I used something
      similar-ish

@import url('https://fonts.googleapis.com/css?family=Carter+One&display=swap');
*/

@font-face {
	font-family: "CarterSubset";
	src: url("https://gumi.ca/CarterSubset.eot");
	src: local("@"),
		url("https://gumi.ca/CarterSubset.woff2") format("woff2"),
		url("https://gumi.ca/CarterSubset.woff") format("woff"),
		url("https://gumi.ca/CarterSubset.ttf") format("truetype");
}

.logo {
	/* this is all relative because our mobile site has to be responsive */
	background: url(../assets/logo.svg) no-repeat left top; /* FIXME: the -small logo is fugly */
	background-size: 12vw 12vw;
	padding: 2vw 0 0 12vw;
	font-family: "CarterSubset", cursive;
	font-display: fallback;
	font-size: 7vw;
	text-shadow: 0.03ch 0.06ch #070905;
	color: #34B039;
	height: 11vw;
	cursor: pointer;

	& span {
		display: none;
	}
}

@media (min-width: 800px) {
	.logo {
		background-image: url(../assets/logo.svg);
		background-size: 100px 100px;
		padding: 10px 0 0 100px;
		font-size: 3em;
		height: 100px;
		position: relative;

		& span {
			display: inline;
			position: absolute;
			font-family: Helvetica;
			font-size: 0.3em;
			top: 72px;
			left: 110px;
			text-shadow: none;
			color: #616260;
			font-style: oblique;
		}
	}
}

@media (max-width: 300px) {
	.logo {
		background: url(../assets/logo-extrasmall.svg) no-repeat left top;
		background-size: 12vw 12vw;
		font-weight: bold;
		text-shadow: none;
	}
}
</style>
