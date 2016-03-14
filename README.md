themanaworld-website
====================

The website is a extention for mediawiki.

Add to LocalSettings.php:
```
require_once("$IP/extensions/tmwa/tmwa.php");
```

Configuration Variables / Settings:

Account registrations come from:
```
$wgTMWAccountEmail = "validemail@domain.com";
```

Set News Site path:
```
$wgTMWNews = "$IP/news.html";
```

To use Wiki's DB Backend:
```
$wgTMWAccountLib = 'dbLadmin';
```

To use php Ladmin connection:
```
$wgTMWAccountLib = 'phpLadmin';
$wgLadminHost = '127.0.0.1';
$wgLadminPort = '6901';
```

Create a new php.ini entry:
```
[tmwa]
tmwa.cfg.LADMIN_PASS = "admin";
```

(A restart of webserver or php-fpm is needed)

To use TMW as Auth services for Wiki
> Note: Mediawiki enforces First Letter of username to be uppercase.
```
$wgAuth = new TMWAuth();
```

Depends:
* Webserver, php, mysql
* [Mediawiki](https://github.com/wikimedia/mediawiki.git)
* [Mediawiki-extensions](https://github.com/wikimedia/mediawiki-extensions.git)
** Extensions TMW uses
** ConfirmEdit
** ReCaptchaNoCaptcha
** ConfirmAccount
** Renameuser
** ParserFunctions
** Cite
** WikiEditor
** SyntaxHighlight_GeSHi
** ImageMap
** googleAnalytics
** WikiArticleFeeds
** MetaKeywordsTag
** MetaDescriptionTag
** tmwa
** GoogleSiteSearch
** Spoilers
* [ReCaptchaEnabled](https://www.google.com/recaptcha/intro/index.html)
