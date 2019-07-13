# themanaworld-website
The website of The Mana World, built with Vue and Vue-Cli.

## Project setup
```
npm install
```

### Customize configuration
The app can be configured with environment variables. See [Dotenv] for further info.


## Building

### Compiles and hot-reloads for development
```sh
npm run serve
```

### Compiles and minifies for production
```sh
npm run build
```

## Testing
### Run all tests
```sh
npm run test
```

### Lints and fixes files
```sh
npm run lint
```

### Run unit tests
```sh
npm run test:unit
```

### Run accessibility tests
```sh
npm run test:accessibility
```

### Run performance tests
```sh
# TODO: implement Lighthouse
npm run test:speed
```

### Run security tests
```sh
# TODO: implement Wapiti
npm run test:security
```

## Deployment

### Nginx
1. Build in production mode
2. Add a nginx vhost pointing to the `dist` folder as root

### [Netlify]
1. Set up a new project
	- Build command: `npm run build`
	- Publish directory: `dist`

### [Render]
1. Set up a new Web Service
	- Environment: `Static Site`
	- Build command: `npm run build`
	- Publish directory: `dist`

### Gitlab Pages
```yml
pages: # the job must be named pages
  image: node:latest
  stage: deploy
  script:
    - npm ci
    - npm run build
    - mv public public-vue # GitLab Pages hooks on the public folder
    - mv dist public # rename the dist folder (result of npm run build)
  artifacts:
    paths:
      - public # artifact path must be /public for GitLab Pages to pick it up
  only:
    - master
```

[Vue]: https://vuejs.org/
[Dotenv]: https://cli.vuejs.org/guide/mode-and-env.html
[Netlify]: https://www.netlify.com/
[Render]: https://render.com/
