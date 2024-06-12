<template>
	<nav class="nav" id="nav">
		<a href="#nav" class="hamburger">☰</a>
		<div>
			<ul>
				<li><router-link :class="{ 'custom-active': isHome }" :to="{ name: 'home' }">Home</router-link></li>
				<li><router-link :to="{ name: 'registration' }">Create Account</router-link></li>
				<li><a href="https://wiki.themanaworld.org/wiki/Downloads">Download</a></li>
				<li><router-link :to="{ name: 'about' }">About</router-link></li>
				<li><a href="https://wiki.themanaworld.org/wiki/FAQ">FAQ</a></li> <!-- we might want to put FAQ under About, or put About on the wiki -->
				<li><router-link :class="{ 'custom-active': isSupport }" :to="{ name: 'support' }">Support</router-link></li>
				<li><a href="https://wiki.themanaworld.org/">Wiki</a></li>
				<li><a href="https://forums.themanaworld.org/">Forums</a></li>
				<li><a href="https://policies.themanaworld.org/">Policies & Rules</a></li>
			</ul>
		</div>
		<div class="server">
			<span>Server Status</span>
			<ServerStatus class="status"/>
		</div>
		<div class="screenshots">
			<a href="https://wiki.themanaworld.org/wiki/Screenshots" title="Screenshots" aria-label="view screenshots">Screenshots</a>
		</div>
		<div>
			<span>Source Code</span>
			<ul>
				<li><a href="https://git.themanaworld.org/explore/groups" aria-label="source code for The Mana World">The Mana World</a></li>
				<li><a href="https://github.com/mapeditor/tiled" aria-label="source code for Tiled">Tiled</a></li>
			</ul>
		</div>
	</nav>
</template>

<style scoped>
.nav {
	background: #BA7A58;
	color: #2f2e32;
	border-bottom: 1px solid #2f2e32;
	padding: 1.25ch;

	& .hamburger {
		position: absolute;
		top: 0.4vw;
		right: 2vw;
		font-size: 8vw;
		text-decoration: none;
		color: gray(50);
		z-index: 300;
		display: block;
	}

	& div {
		background: #CBA083;
		margin: 0;
		padding: 0;
		border-radius: 1.25ch;
		border: solid 1px #2f2e32;
		margin-bottom: 1.25ch;
		min-width: max(160px, 17ch);

		& ul {
			list-style: none;
			margin: 0;
			padding: 0.75ch;
		}

		& span {
			text-align: center;
			font-size: 0.9em;
			display: block;
			padding: 0.5ch;
			border-bottom: solid 1px #2f2e32;
		}

		& a, & a:visited {
			color: #2f2e32;
			text-decoration: none;
			display: block;
			border: solid 1px #CBA083;
			border-radius: 0.5ch;
			padding: 0.75ch 1ch;

			&:is(:hover, :focus), &.router-link-exact-active, &.custom-active {
				background-color: rgba(255,255,255,0.4);
				border: solid 1px #2f2e32;
			}
		}
	}

	& .server > .status {
		text-align: center;
		font-weight: bolder;
		border: 0;
		border-radius: 0 0 1.25ch 1.25ch;

		&:hover {
			background-color: rgba(255,255,255,0.4);
		}
	}

	& .screenshots {
		& a, & a:visited {
			background: url(../assets/screenshot-thumb.webp) no-repeat center center #CBA083;
			border: 1px solid #784f3f;
			margin: 5px auto;
			padding: 0;
			width: 133px;
			height: 100px;
			color: rgba(0, 0, 0, 0);

			&:hover {
				opacity: 0.8;
			}
		}
	}
}

@media (min-width: 1100px) {
	.nav {
		border-radius: 0 2.5ch 2.5ch 0;
		border: 1px solid #2f2e32;

		& .hamburger {
			display: none;
		}
	}
}

@media (min-width: 460px) {
	.nav {
		& .hamburger {
			top: 3vw;
			right: 2vw;
			font-size: calc(1rem + 3vw);
		}
	}
}
</style>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import ServerStatus from "@/components/ServerStatus.vue";

@Options({
	components: {
		ServerStatus,
	},
	computed: {
		// XXX: find a better way to do this

		isSupport() {
			return this.$route.path.startsWith("/recover");
		},
		isHome() {
			return this.$route.path.startsWith("/news");
		}
	}
})
export default class NavigationV extends Vue {}
</script>
