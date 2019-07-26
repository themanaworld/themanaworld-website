<template>
	<aside>
		<a v-if="Online && Players" target="_blank" rel="noopener" href="https://server.themanaworld.org">Online: {{Players}} players</a>
		<a v-if="Online && !Players" target="_blank" rel="noopener" href="https://server.themanaworld.org">Online</a>
		<a v-if="!Online" class="offline" target="_blank" rel="noopener" href="https://www.youtube.com/watch?v=ILVfzx5Pe-A">Offline</a>
	</aside>
</template>

<style scoped>
aside :any-link {
	text-decoration: none;
	text-shadow: 0 0 1ch #e1d6cf;
	color: green;
	display: block;
	padding: 8px;

	&.offline {
		color: #d42424;
	}
}
</style>

<script lang="ts">
import Vue from "vue"
import Component from "vue-class-component"

interface StatusResponse {
	serverStatus: string;
	playersOnline?: number;
}

@Component
export default class ServerStatus extends Vue {
	Players = 0;
	Online = true;

	private async getStatus () {
		const req = new Request(`${process.env.VUE_APP_API}/tmwa/server`, {
			mode: "cors",
			referrer: "no-referrer",
		});

		try {
			const raw_response = await fetch(req);
			const data: StatusResponse = await raw_response.json();

			this.Online = data.serverStatus === "Online";
			this.Players = data.playersOnline || 0;

			if (Reflect.has(self, "localStorage")) {
				localStorage.setItem("onlinePlayers", `${this.Players}`);
				localStorage.setItem("serverOnline", this.Online ? "true": "false");
			}
		} catch (err) {
			// API unreachable (assume it's online anyway)
			this.Online = true;
		}

		setTimeout(this.getStatus, 8000);
	}

	mounted () {
		// use the last cached value to populate prior to first fetch:
		if (Reflect.has(self, "localStorage")) {
			this.Players = +(localStorage.getItem("onlinePlayers") || 99);
			this.Online = !!(localStorage.getItem("serverOnline") || true);
		} else {
			this.Players = 99;
		}

		this.getStatus();
	}
}
</script>
