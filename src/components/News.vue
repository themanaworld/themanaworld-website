<template>
	<div class="news" v-if="count">
		<span v-if="!entries.length">(no news entries)</span>

		<article class="entry" v-for="entry in entries" :id="entry.date">
			<a :href="'#' + entry.date">{{ entry.title }}</a>
			<time :datetime="entry.date" class="date">{{ entry.date }}</time>
			<section class="body" v-html="entry.html"></section>
		</article>
	</div>
</template>

<style scoped>
.news .entry {
	margin-bottom: 1em;

	&:nth-of-type(1n + 2) {
		margin-top: 2em;
	}

	& > a {
		text-decoration: none;
		color: inherit;
		font-weight: bold;
	}

	& > .date {
		float: right;
	}

	& .body {
		margin-top: 5px;
	}
}
</style>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import newsEntries from "@/assets/news.json";

interface NewsEntry {
	title: string;
	date: string;
	author: string;
	html: string;
}

@Component
export default class News extends Vue {
	@Prop({ default: Infinity }) private count!: number;
	@Prop({ default: 0 }) private from!: number;
	private entries: NewsEntry[] = (newsEntries as NewsEntry[]).slice(this.from, this.count);
}
</script>
