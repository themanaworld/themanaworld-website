# themanaworld-website

The website of The Mana World, built with [Zola].

## Development

[Install Zola](https://www.getzola.org/documentation/getting-started/installation/), then:

```
zola serve
```

and open http://127.0.0.1:1111/. The production build is created with:

```
zola build
```

which writes the site to the `dist` folder.

## Layout

- `config.toml`: site configuration. The `[extra]` values replace the
  `VUE_APP_*` environment variables of the previous Vue site (API endpoints,
  reCAPTCHA site key, optional status banner and event notice).
- `content/`: the pages, mostly Markdown with embedded HTML for the account
  forms (registration, recovery and migration).
- `templates/`: the Tera templates (layout, home, news and redirects).
- `static/`: assets served as-is (images, fonts, CSS and JavaScript).

## News

News entries are not maintained in this repository. They are fetched from
<https://updates.themanaworld.org/news.json> at build time, so the site needs
to be rebuilt (and redeployed) for new entries to show up.

## JavaScript

The site is fully static; a small amount of vanilla JavaScript provides the
interactive parts:

- `static/js/server-status.js`: polls the server status API and updates the
  indicator in the navigation.
- `static/js/account-forms.js`: drives the registration, account recovery and
  migration forms. reCAPTCHA is only loaded after the user opts in with a
  checkbox, and passwords are checked against the Pwned Passwords range API
  before submission.

## Deployment

CI builds the site (see `.gitlab-ci.yml`) and publishes the `dist` folder as
an artifact, which the ansible deploy pulls from
`/jobs/artifacts/master/download?job=build`.

The site can be served by any static file server. Unlike the previous Vue
single-page app, no rewrite of all routes to `index.html` is needed. Two
things are worth configuring on the server:

- Serve `404.html` for unknown paths. Emailed password reset links of the
  form `/recover/password/<token>` rely on this: the 404 page forwards the
  token to `/recover/password/#<token>`. Alternatively, rewrite those paths
  directly:

  ```nginx
  location ~ ^/recover/(password|username)/(?<token>[0-9a-f-]+)$ {
      return 302 /recover/$1/#$token;
  }
  ```

- Legacy `.php` paths (`/index.php`, `/about.php`, `/news-feed.php`,
  `/registration.php`, `/downloads.php`) are covered by generated redirect
  pages, but make sure the server does not try to interpret them as PHP.

[Zola]: https://www.getzola.org/
