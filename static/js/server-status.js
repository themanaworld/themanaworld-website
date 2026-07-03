// Live server status indicator in the navigation
// (port of the former ServerStatus.vue component)
(function () {
	"use strict";

	var el = document.getElementById("server-status");
	if (!el) {
		return;
	}

	var hasStorage = Reflect.has(self, "localStorage");

	function render(online, players) {
		if (online) {
			el.textContent = players ? "Online: " + players + " players" : "Online";
			el.classList.remove("offline");
			el.title = "View List";
			el.setAttribute("aria-label", "view list of online players");
			el.href = "https://server.themanaworld.org";
		} else {
			el.textContent = "Offline";
			el.classList.add("offline");
			el.title = "???";
			el.setAttribute("aria-label", "open a YouTube video");
			el.href = "https://www.youtube-nocookie.com/embed/ILVfzx5Pe-A?autoplay=1&modestbranding=1";
		}
	}

	function getStatus() {
		var req = new Request(self.tmwConfig.api + "/tmwa/server", {
			mode: "cors",
			referrer: "no-referrer",
		});

		fetch(req)
			.then(function (response) { return response.json(); })
			.then(function (data) {
				var online = data.serverStatus === "Online";
				var players = data.playersOnline || 0;

				render(online, players);

				if (hasStorage) {
					localStorage.setItem("onlinePlayers", String(players));
					localStorage.setItem("serverOnline", online ? "true" : "false");
				}
			})
			.catch(function () {
				// API unreachable (assume it's offline as you cannot register)
				render(false, 0);
			})
			.finally(function () {
				setTimeout(getStatus, 8000);
			});
	}

	// use the last cached value to populate prior to the first fetch;
	// without a cached value, keep the static "Checking…" text until
	// the first response settles it
	if (hasStorage && localStorage.getItem("serverOnline") !== null) {
		render(localStorage.getItem("serverOnline") !== "false",
			+(localStorage.getItem("onlinePlayers") || 0));
	}

	getStatus();
})();
