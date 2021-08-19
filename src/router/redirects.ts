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
			self.location.href = "https://manaplus.themanaworld.org/";
		}
	},
	{
		path: "/recover",
		redirect: { name: "support" },
	},
];

export default redirects;
