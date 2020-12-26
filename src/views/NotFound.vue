<template>
	<main>
		<h1>Page not found</h1>
		<p>This page does not exist or has been removed.</p>
		<p><router-link :to="{ name: 'home' }">back to home page</router-link></p>
	</main>
</template>

<style scoped>
a {
	margin-top: 2em;
	font-size: 1.3em;

	&::before {
		content: "🠨  ";
		text-decoration: none;
	}
}
</style>
