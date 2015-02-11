Spoilers
========

Mediawiki extension that allows for spoiler tags that will hide a block of text. The spoiler button's show/hide message can be customized for each set.

Installation
------------
To install this extension, add the follwoing lines to the end of the LocalSettings.php file:
```
//Spoilers
require("$IP/extensions/Spoilers/Spoilers.php");
```

Usage Example
---------------------
```
<spoiler hide="hide_message">spoiler_text</spoiler>
<spoiler show="show_message">spoiler_text</spoiler>
<spoiler show="show_message" hide="hide_message">spoiler_text</spoiler>
<spoiler>spoiler_text</spoiler>
```
