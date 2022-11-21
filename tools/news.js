/**
 * fetches the full news file and generates a json file with the first entry
 * and another with the full archive
 */

const https = require("https");
const fs = require("fs");

const source = "https://updates.tmw2.org/legacy/news.json";

console.info("Acquiring news from static site...");

https.get(source, res => {
	let file = "";

	res.on("data", chunk => file += chunk);

	res.on("end", () => {
		const news = JSON.parse(file);
		const first = [];

		console.info(`Received ${news.length} news entries; splitting...`);

		if (news.length > 0) {
			first.push(news[0]);

			let data = new Uint8Array(Buffer.from(JSON.stringify(first)));
			fs.writeFileSync("src/assets/news.json", data, {
				encoding: "utf-8",
				flag: "w+",
			});

			data = new Uint8Array(Buffer.from(file));
			fs.writeFileSync("src/assets/news-full.json", data, {
				encoding: "utf-8",
				flag: "w+",
			});
		} else {
			console.info("Creating dummy news files...");

			let data = new Uint8Array(Buffer.from("[]"));
			fs.writeFileSync("src/assets/news.json", data, {
				encoding: "utf-8",
				flag: "w+",
			});

			fs.writeFileSync("src/assets/news-full.json", data, {
				encoding: "utf-8",
				flag: "w+",
			});
		}

		console.info("Success.");
	});

});
