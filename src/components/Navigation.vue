<template>
	<nav class="nav">
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
		<!-- TODO: we want a server status component: https://api.themanaworld.org/api/tmwa/server -->
		<div class="server">
			<span>Server Status</span>
			<ServerStatus class="status"/>
		</div>
		<ul>
			<span>Source Code</span>
			<li><a href="https://github.com/themanaworld">The Mana World</a></li>
			<li><a href="https://gitlab.com/evol">Evol Online</a></li>
			<li><a href="https://gitlab.com/manaplus">ManaPlus</a></li>
			<li><a href="https://github.com/bjorn/tiled">Tiled</a></li>
		</ul>
	</nav>
</template>

<style scoped>
.nav {
	background: #BA7A58;
	color: #2f2e32;
	border-radius: 0 0 15px 15px;
	padding: 15px;
	font-size: 14px;

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
			background: rgba(255,255,255,0.4);
			border: solid 1px #2f2e32;
			font-weight: bold;
		}
	}

	& ul, & div {
		background: #CBA083;
		margin: 0px;
		padding: 0px;
		border-radius: 5px;
		border: solid 1px #2f2e32;
		list-style: none;
		margin-bottom: 13px;
	}

	& ul li {
		margin-left: 0.8ch;
		margin-right: 0.8ch;

		&:first-of-type {
			margin-top: 0.8ch;
		}

		&:last-of-type {
			margin-bottom: 0.8ch;
		}
	}


	& .server > .status {
		text-align: center;
		font-weight: bolder;
		border: 0;
		border-radius: 0 0 5px 5px;

		&:hover {
			background: rgba(255,255,255,0.4);
		}
	}
}

@media (min-width: 1100px) {
	.nav {
		border-radius: 0 15px 15px 0;
	}
}
</style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import RouteRecord from "vue-router";
import ServerStatus from "@/components/ServerStatus.vue";

@Component({
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
