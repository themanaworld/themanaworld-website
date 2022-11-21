
<template>
	<main class="main-content">
		<h1>News archive</h1>
		<a href="https://updates.tmw2.org/legacy/news.rss" target="_blank" rel="noopener" title="RSS feed" aria-label="open the RSS feed" class="feed"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg></a>
		<News :count="Infinity"/>
	</main>
</template>

<style scoped>
.main-content h1 {
	margin-bottom: 0;
}
.main-content .feed {
	float: right;
	margin-top: -2.5em;
	text-decoration: none;
	color: inherit;

	& > svg {
		width: 1.8em;
		height: 1.8em;
		border: solid 1px inherit;
		border-radius: 30%;
		opacity: .6;

		&:is(:hover, :focus) {
			background: orange;
			color: #fff;
			opacity: 1;
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
export default class NewsV extends Vue {}
</script>
