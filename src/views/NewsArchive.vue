
<template>
	<main class="main-content">
		<h1>News archive</h1>
		<News :count="Infinity"/>
	</main>
</template>

<style scoped>
.main-content h1 {
	margin-bottom: 0;
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
