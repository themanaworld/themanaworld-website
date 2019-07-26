// see https://cli.vuejs.org/config
const CompressionPlugin = require("compression-webpack-plugin");
const zopfli = require("@gfx/zopfli");

module.exports = {
	//integrity: true, // enable SRI in script/style tags
	parallel: true,
	configureWebpack: {
		plugins: [
			new CompressionPlugin({
				filename: "[path].br[query]",
				algorithm: "brotliCompress",
				test: /\.(js|css|html|svg|ico|png|webp|ttf|woff|woff2)$/,
				compressionOptions: { level: 11 },
				minRatio: 0.9,
			}),
			new CompressionPlugin({
				filename: "[path].gz[query]",
				compressionOptions: {
					numiterations: 15,
				},
				minRatio: 0.9,
				algorithm (input, compressionOptions, callback) {
					return zopfli.gzip(input, compressionOptions, callback);
				},
			}),
			new CompressionPlugin({
				filename: "[path].zopfli[query]",
				compressionOptions: {
					numiterations: 15,
				},
				minRatio: 0.9,
				algorithm (input, compressionOptions, callback) {
					return zopfli.gzip(input, compressionOptions, callback);
				},
			}),
		],
	}
}
