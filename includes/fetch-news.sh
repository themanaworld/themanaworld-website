#!/bin/sh
#
#    Fetches project news and saves it for local use.
#

/usr/bin/wget -q -O /home/groups/t/th/themanaworld/htdocs/includes/rss2_projnews.tmp 'http://sourceforge.net/export/rss2_projnews.php?group_id=106790&rss_fulltext=1' > /dev/null
/bin/mv -f /home/groups/t/th/themanaworld/htdocs/includes/rss2_projnews.tmp \
           /home/groups/t/th/themanaworld/htdocs/includes/rss2_projnews.cache
