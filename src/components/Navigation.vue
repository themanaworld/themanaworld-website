<template>
	<nav class="nav" id="nav">
		<a href="#nav" class="hamburger">☰</a>
		<div>
			<ul>
				<li><router-link :class="{ 'custom-active': isHome }" :to="{ name: 'home' }">Home</router-link></li>
				<li><router-link :to="{ name: 'registration' }">Create Account</router-link></li>
				<li><a href="https://wiki.themanaworld.org/index.php/Downloads">Download</a></li>
				<li><router-link :to="{ name: 'about' }">About</router-link></li>
				<li><a href="https://wiki.themanaworld.org/index.php/FAQ">FAQ</a></li> <!-- we might want to put FAQ under About, or put About on the wiki -->
				<li><router-link :class="{ 'custom-active': isSupport }" :to="{ name: 'support' }">Support</router-link></li>
				<li><a href="https://wiki.themanaworld.org/">Wiki</a></li>
				<li><a href="https://forums.themanaworld.org/">Forums</a></li>
			</ul>
		</div>
		<div class="server">
			<span>Server Status</span>
			<ServerStatus class="status"/>
		</div>
		<div class="screenshots">
			<a href="https://wiki.themanaworld.org/index.php/Screenshots">Screenshots</a>
		</div>
		<div>
			<span>Source Code</span>
			<ul>
				<li><a href="https://github.com/themanaworld">The Mana World</a></li>
				<li><a href="https://gitlab.com/evol">Evol Online</a></li>
				<li><a href="https://gitlab.com/manaplus">ManaPlus</a></li>
				<li><a href="https://github.com/bjorn/tiled">Tiled</a></li>
			</ul>
		</div>
	</nav>
</template>

<style scoped>
.nav {
	background: #BA7A58;
	color: #2f2e32;
	border-radius: 0 0 15px 15px;
	padding: 15px;
	font-size: 14px;

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
		border-radius: 5px;
		border: solid 1px #2f2e32;
		margin-bottom: 13px;
		min-width: max(160px, 17ch);

		& ul {
			list-style: none;
			margin: 0;
			padding: 0;

			& li {
				margin-left: 0.8ch;
				margin-right: 0.8ch;

				&:first-of-type {
					margin-top: 0.8ch;
				}

				&:last-of-type {
					margin-bottom: 0.8ch;
				}
			}
		}

		& span {
			text-align: center;
			display: block;
			padding: 5px;
			border-bottom: solid 1px #2f2e32;
		}

		& a, & a:visited {
			color: #2f2e32;
			text-decoration: none;
			display: block;
			border: solid 1px #CBA083;
			padding: 1ch;

			&:hover, &.router-link-exact-active, &.custom-active {
				background-color: rgba(255,255,255,0.4);
				border: solid 1px #2f2e32;
			}
		}
	}

	& .server > .status {
		text-align: center;
		font-weight: bolder;
		border: 0;
		border-radius: 0 0 5px 5px;

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
		border-radius: 0 15px 15px 0;

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
