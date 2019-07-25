<template>
	<router-link tag="div" :to="{ name: 'home' }" class="logo">
		<span>The </span>Mana World
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

	& span:last-of-type {
		display: none;
	}
}

@media (min-width: 800px) {
	.logo {
		background-image: url(../assets/logo.svg);
		background-size: 100px 100px;
		padding: 22px 0 0 105px;
		font-size: 3em;
		letter-spacing: 1px;
		height: 90px;
		margin-top: 20px;
		position: relative;

		& span:first-of-type {
			position: absolute;
			left: 105px;
			top: 4px;
			font-size: 0.6em;
		}

		& span:last-of-type {
			display: inline;
			position: absolute;
			font-family: Helvetica;
			font-size: 0.3em;
			letter-spacing: -0.8px;
			font-weight: lighter;
			top: 73px;
			left: 108px;
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
