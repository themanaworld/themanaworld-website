<template>
	<footer class="footer">
		&copy; 2004&mdash;{{ year }} The Mana World
	</footer>
</template>

<style scoped>
.footer {
	text-align: right;
	font-size: 8pt;
	padding: 5px;
}
</style>

<script lang="ts">
import Vue from "vue"
import Component from "vue-class-component"

@Component
export default class Copyright extends Vue {
	year = Reflect.construct(Date, []).getFullYear();
}
</script>
