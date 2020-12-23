// see https://cli.vuejs.org/config
/* eslint-disable @typescript-eslint/no-var-requires */
const CompressionPlugin = require("compression-webpack-plugin");
const zopfli = require("@gfx/zopfli");
const zlib = require("zlib");

module.exports = {
	//integrity: true, // enable SRI in script/style tags
	parallel: true,
	configureWebpack: {
		plugins: process.env.NODE_ENV === "production" && Reflect.has(zlib, "brotliCompress") ? [
			new CompressionPlugin({
				filename: "[file].br[query]",
				algorithm: "brotliCompress",
				test: /\.(js|css|html|svg|ico|png|webp|ttf|woff|woff2)$/,
				compressionOptions: { level: 11 },
				minRatio: 0.9,
			}),
			new CompressionPlugin({
				filename: "[file].gz[query]",
				compressionOptions: {
					numiterations: 15,
				},
				minRatio: 0.9,
				algorithm (input, compressionOptions, callback) {
					return zopfli.gzip(input, compressionOptions, callback);
				},
			}),
			new CompressionPlugin({
				filename: "[file].zopfli[query]",
				compressionOptions: {
					numiterations: 15,
				},
				minRatio: 0.9,
				algorithm (input, compressionOptions, callback) {
					return zopfli.gzip(input, compressionOptions, callback);
				},
			}),
		]: [],
	}
}
