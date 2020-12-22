<template>
	<footer class="footer">
		&copy; 2004&ndash;{{ year }} The Mana World
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
import { Options, Vue } from "vue-class-component";

@Options({})
export default class Copyright extends Vue {
	year = Reflect.construct(Date, []).getFullYear();
}
</script>
