<template>
	<div class="news" v-if="count">
		<span v-if="!entries.length">(no news entries)</span>

		<article class="entry" v-for="entry in entries" :id="entry.id">
			<a :href="'#' + entry.id">{{ entry.title }}</a>
			<time :datetime="entry.date" class="date">{{ entry.date }}</time>
			<section class="body" v-html="entry.html"></section>
			<q>{{entry.author}}</q>
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

		& :any-link {
			color: inherit;
			text-decoration: none;

			&::before {
				content: "➜ ";
				color: blue;
			}

			&:hover {
				text-decoration: underline;
			}
		}
	}

	& q {
		color: #009000;
		margin-top: 0.5em;
		display: inline-block;

		&::before {
			content: "– ";
		}
	}
}
</style>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";
import newsEntries from "@/assets/news.json";

interface NewsEntry {
	id: string;
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

	mounted () {
		if (!process.env.VUE_APP_NEWS_JSON || !process.env.VUE_APP_NEWS_JSON.startsWith("https")) {
			// TODO: allow arbitrary paths (no hardcoded news.json)
			return;
		}

		// restore from cache while we're loading a fresh copy
		if (Reflect.has(self, "localStorage")) {
			let newsCache = localStorage.getItem("newsCache");

			if (newsCache !== null) {
				this.entries = (JSON.parse(newsCache) as NewsEntry[]).slice(this.from, this.count);
			}
		}

		const req = new Request(process.env.VUE_APP_NEWS_JSON, {
			method: "GET",
			cache: "default", // serve if fresh, else fetch
		});

		fetch(req)
		.then(data => data.json())
		.then(data => {
			this.entries = (data as NewsEntry[]).slice(this.from, this.count);

			if (Reflect.has(self, "localStorage")) {
				localStorage.setItem("newsCache", JSON.stringify(data));
			}
		})
		.catch(e => {
			// no news (will use cache, if any)
		})
	}
}
</script>
