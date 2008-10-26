#!/bin/sh
#
#    Fetches project news and saves it for local use.
#

/usr/bin/wget -q -O /home/tmw/public_html/www/includes/rss2_projnews.tmp 'http://sourceforge.net/export/rss2_projnews.php?group_id=106790&rss_fulltext=1' > /dev/null
/bin/mv -f /home/tmw/public_html/www/includes/rss2_projnews.tmp \
           /home/tmw/public_html/www/includes/rss2_projnews.cache
