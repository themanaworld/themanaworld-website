import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import Home from "../views/Home.vue";
import NotFound from "../views/NotFound.vue";
import redirects from "./redirects";

const routes: Array<RouteRecordRaw> = [
	{
		path: "/",
		name: "home",
		component: Home,
	},
	{
		path: "/news",
		name: "news",
		component: () => import(/* webpackChunkName: "news" */ "../views/NewsArchive.vue"),
	},
	{
		path: "/about",
		name: "about",
		component: () => import(/* webpackChunkName: "about" */ "../views/About.vue"),
	},
	{
		path: "/support",
		name: "support",
		component: () => import(/* webpackChunkName: "support" */ "../views/Support.vue"),
	},
	{
		path: "/recover/password:emailToken(.*)",
		alias: ["/recover/username:emailToken(.*)"],
		name: "account recovery",
		component: () => import(/* webpackChunkName: "recovery" */ "../views/AccountRecovery.vue"),
	},
	{
		path: "/register",
		name: "registration",
		component: () => import(/* webpackChunkName: "registration" */ "../views/Registration.vue"),
	},
	{
		path: "/404:pathMatch(.*)",
		alias: "/:pathMatch(.*)",
		name: "not found",
		component: NotFound,
	},
	...redirects,
];

const router = createRouter({
	history: createWebHistory(process.env.BASE_URL),
	routes,
});

router.afterEach((to,) => {
	const mainTitle = document.querySelector("#app > .content > h1");

	// scroll to the title if we're below it
	if (mainTitle) {
		mainTitle.scrollIntoView({
			block: "nearest", // FIXME: weird behaviour in firefox!
			inline: "nearest",
			behavior: "smooth",
		});
	}

	if (to.name && typeof to.name === "string" && to.path !== "/") {
		document.title = `${process.env.VUE_APP_TITLE} - ${to.name[0].toUpperCase() + to.name.slice(1)}`;
	} else {
		document.title = process.env.VUE_APP_TITLE;
	}
});

export default router;
