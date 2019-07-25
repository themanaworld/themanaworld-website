import Vue from "vue"
import Router from "vue-router"
import Home from "./views/Home.vue"
import NotFound from "./views/NotFound.vue"
import redirects from "./redirects"

Vue.use(Router)

const router = new Router({
	mode: "history",
	base: process.env.BASE_URL,
	routes: [
		{
			path: "/",
			name: "home",
			component: Home,
		},
		{
			path: "/news",
			name: "news",
			component: () => import(/* webpackChunkName: "news" */ "./views/News.vue"),
		},
		{
			path: "/about",
			name: "about",
			component: () => import(/* webpackChunkName: "about" */ "./views/About.vue"),
		},
		{
			path: "/support",
			name: "support",
			component: () => import(/* webpackChunkName: "support" */ "./views/Support.vue"),
		},
		{
			path: "/recover/password",
			alias: ["/recover/username"],
			name: "account recovery",
			component: () => import(/* webpackChunkName: "recovery" */ "./views/AccountRecovery.vue"),
		},
		// BUG: normally we should be able to put this route under alias but aliases cannot have props:
		{
			path: "/recover/password/:emailToken",
			name: "password reset",
			component: () => import(/* webpackChunkName: "recovery" */ "./views/AccountRecovery.vue"),
		},
		{
			path: "/register",
			name: "registration",
			component: () => import(/* webpackChunkName: "registration" */ "./views/Registration.vue"),
		},
		{
			path: "/404",
			alias: "*",
			name: "not found",
			component: NotFound,
		},
		...redirects,
	]
});

router.afterEach((to, from) => {
	const mainTitle = document.querySelector("#app > .content > h1");

	// scroll to the title if we're below it
	if (mainTitle) {
		mainTitle.scrollIntoView({
			block: "nearest", // FIXME: weird behaviour in firefox!
			inline: "nearest",
			behavior: "smooth",
		});
	}

	if (to.name && to.path !== "/") {
		document.title = `${process.env.VUE_APP_TITLE} - ${to.name[0].toUpperCase() + to.name.slice(1)}`;
	} else {
		document.title = process.env.VUE_APP_TITLE;
	}
})

export default router;
