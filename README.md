# themanaworld-website

The website of The Mana World, built with Vue and Vue-Cli.


## Project setup
```
yarn install
```

### Compiles and hot-reloads for development
```
yarn serve
```

### Compiles and minifies for production
```
yarn build
```

### Lints and fixes files
```
yarn lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/). The app can also be configured with environment variables. See [Dotenv] for further info.

## Deployment

### Nginx
1. Build in production mode
2. Add a nginx vhost pointing to the `dist` folder as root
3. Rewrite all requests to index.html within a `location` block:
```nginx
	index  index.html;
	try_files $uri $uri/ /index.html;
```

### [Netlify]
1. Set up a new project
	- Build command: `yarn deploy`
	- Publish directory: `dist`

### [Render]
1. Set up a new Web Service
	- Environment: `Static Site`
	- Build command: `yarn deploy`
	- Publish directory: `dist`


[Vue]: https://vuejs.org/
[Dotenv]: https://cli.vuejs.org/guide/mode-and-env.html
[Netlify]: https://www.netlify.com/
[Render]: https://render.com/
