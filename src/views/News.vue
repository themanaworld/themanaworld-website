<template>
	<main class="main-content">
		<h1>News archive</h1>
		<News count="Infinity"/>
	</main>
</template>

<style scoped>
.main-content {
	& h1 {
		margin-bottom: 0;
	}
}
</style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import News from "@/components/News.vue";

@Component({
	components: {
		News,
	},
})
export default class NewsV extends Vue {}
</script>
