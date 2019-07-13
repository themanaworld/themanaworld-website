// legacy paths from the PHP website

const redirects = [
	{
		path: "/index.php",
		redirect: { name: "home" },
	},
	{
		path: "/news-feed.php",
		redirect: { name: "news" },
	},
	{
		path: "/about.php",
		redirect: { name: "about" },
	},
	{
		path: "/registration.php",
		redirect: { name: "registration" },
	},
	{
		path: "/downloads.php",
		redirect: () => {
			self.location.href = "https://wiki.themanaworld.org/index.php/Downloads";
		}
	},
];

export default redirects;
