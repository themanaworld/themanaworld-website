<template>
	<main class="home">
		<h1>The Mana World Project</h1>
		<p>The Mana World (TMW) is a serious effort to create an innovative free and open source MMORPG. TMW uses 2D graphics and aims to create a large and diverse interactive world. It is licensed under the GPL, making sure this game can't ever run away from you.</p>
		<div class="read-more">
			<router-link :to="{ name: 'about' }">Read More >></router-link>
		</div>

		<h1>Recent News</h1>
		<News :count="1"/>
		<div class="read-more">
			<router-link :to="{ name: 'news' }">More News >></router-link>
		</div>
	</main>
</template>

<style scoped>
.read-more {
	text-align: right;
	padding-right: 8px;

	& a, & a:visited {
		color: #2f2e32;
		text-decoration: none;
		font-weight: bold;
		font-size: 0.8em;

		&:hover {
			text-decoration: underline;
		}
	}
}
</style>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import News from "@/components/News.vue";

@Options({
	components: {
		News,
	},
})
export default class Home extends Vue {}
</script>
