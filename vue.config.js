// see https://cli.vuejs.org/config
const CompressionPlugin = require("compression-webpack-plugin");
const zopfli = require("@gfx/zopfli");

module.exports = {
	//integrity: true, // enable SRI in script/style tags
	parallel: true,
	configureWebpack: {
		plugins: [
			new CompressionPlugin({
				filename: '[path].br[query]',
				algorithm: 'brotliCompress',
				test: /\.(js|css|html|svg|ico|png|webp|ttf|woff|woff2)$/,
				compressionOptions: { level: 11 },
				threshold: 10240,
				minRatio: 0.8,
				deleteOriginalAssets: false,
			}),
			new CompressionPlugin({
				compressionOptions: {
					numiterations: 15,
				},
				algorithm (input, compressionOptions, callback) {
					return zopfli.gzip(input, compressionOptions, callback);
				},
			}),
		],
	}
}
