<template>
	<div class="news" v-if="count">
		<span v-if="!entries.length">(no news entries)</span>

		<article class="entry" v-for="entry in entries" :id="entry.id" v-bind:key="entry.id">
			<a :href="'#' + entry.id">{{ entry.title }}</a>
			<time :datetime="entry.date" class="date">{{ entry.date }}</time>
			<section class="body" v-html="entry.html"></section>
			<q>{{entry.author}}</q>
		</article>
		<article v-if="count > 1 && !fullyLoaded" class="entry loading">
			<section class="body">
				Loading... Please wait.
			</section>
		</article>
	</div>
</template>

<style scoped>
.news .entry {
	margin-bottom: 0;
	padding-bottom: 1em;
	hyphens: auto;

	&:not(:last-of-type) {
		padding-bottom: 3em;
	}

	&:nth-of-type(1n + 2) {
		padding-top: 1em;
		border-top: solid 0.4ex rgba(0, 0, 0, 0.1);
	}

	&:first-child:not(:only-child) {
		padding-top: 1em;
		border-top: solid 0.4ex transparent;
	}

	&:nth-of-type(1n + 2), &:not(:only-child) {
		&:hover, &:target {
			border-color: #0000FF;
			background-color: rgba(220,220,220,0.5);

			& > .date, & > a {
				color: #0000FF;
			}
		}
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
		margin-top: 2ex;

		&:deep(b) {
			display: block;
			margin-bottom: 1ex;
		}

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
		margin-top: 2ex;
		display: inline-block;

		&::before {
			content: "– ";
		}
	}
}

@media (min-width: 1100px) {
	.news .entry {
		padding-left: 30px;
		padding-right: 40px;
	}
}
</style>

<script lang="ts">
import { Options, Vue } from "vue-class-component";
import newsEntries from "@/assets/news.json";

interface NewsEntry {
	id: string;
	title: string;
	date: string;
	author: string;
	html: string;
}

@Options({
	props: {
		count: Number,
		from: Number,
	}
})
export default class News extends Vue {
	private count = Infinity;
	private from = 0;
	private entries: NewsEntry[] = (newsEntries as NewsEntry[]).slice(this.from, this.count);
	private fullyLoaded = false;

	beautify () {
		this.entries.forEach(entry => {
			// FIXME: weird Vue bug
			entry.html = entry.html.replace(/<br\/>/g,"<br></br>");

			// compare the entry title with its first line:
			const compare = `<b>${entry.title}</b><br></br>`;

			if (entry.html.startsWith(compare)) {
				// duplicate title: remove the extra one
				entry.html = entry.html.slice(compare.length);
			}
		});
	}

	mounted () {
		if (!process.env.VUE_APP_NEWS_JSON || !process.env.VUE_APP_NEWS_JSON.startsWith("https")) {
			this.beautify();
			// no extra news to load so end here
			return;
		}

		// restore from cache while we're loading a fresh copy
		if (Reflect.has(self, "localStorage")) {
			const newsCache = localStorage.getItem("newsCache");

			if (newsCache !== null) {
				this.entries = (JSON.parse(newsCache) as NewsEntry[]).slice(this.from, this.count);
			}
		}

		// initial rendering
		this.beautify();

		if (this.count === 1 && this.entries.length >= 1) {
			// don't loaad extra news unprompted
			return;
		}

		const req = new Request(process.env.VUE_APP_NEWS_JSON, {
			method: "GET",
			cache: "default", // serve if fresh, else fetch
		});

		fetch(req)
		.then(data => data.json())
		.then(data => {
			this.entries = (data as NewsEntry[]).slice(this.from, this.count);
			this.fullyLoaded = true;
			this.beautify();

			if (Reflect.has(self, "localStorage")) {
				localStorage.setItem("newsCache", JSON.stringify(data));
			}
		})
		.catch(() => {
			// no news (will use cache, if any)
		})
	}
}
</script>
