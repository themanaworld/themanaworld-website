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
single-page app, no rewrite of all routes to `index.html` is needed; the
server should serve `404.html` for unknown paths instead. Redirects are
deliberately not part of the generated site and are expected from the
webserver (see the website-www role in the ansible repository for the live
configuration):

- Legacy paths: `/index.php` (to the wiki), `/about.php`, `/news-feed.php`,
  `/registration.php`, `/downloads.php` (to the wiki downloads page).
- `/recover` to `/support/`, and `/recover/username` to `/recover/password/`.
- Emailed password reset links of the form `/recover/password/<token>` (the
  token was a path parameter in the SPA era) to `/recover/password/#<token>`,
  the fragment the recovery page reads.

[Zola]: https://www.getzola.org/
