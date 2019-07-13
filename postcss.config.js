module.exports = {
	plugins: {
		"autoprefixer": {},
		"postcss-preset-env": {
			features: {
				"nesting-rules": true,
				"custom-properties": true,
				"gray-function": true,
				"matches-pseudo-class": true,
				"not-pseudo-class": true,
				"media-query-ranges": true,
				"prefers-color-scheme-query": true,
			}
		}
	}
}
