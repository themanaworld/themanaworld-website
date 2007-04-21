<?php
// WARNING:
// This code uses the "DOM XML" extension, which is only available with PHP 4.
// Be sure to update it to use the "XML", "DOM" or "XMLReader" extensions when
// SF upgrades to PHP 5.
//
// The news is cached locally using a cronjob which runs in the 9th minute of
// every hour:
//
// 9 * * * * /home/groups/t/th/themanaworld/htdocs/includes/fetch-news.sh 
//

//$feedurl = "http://sourceforge.net/export/rss2_projnews.php?group_id=106790&rss_fulltext=1";
$feedurl = "includes/rss2_projnews.cache";

if (!$dom = domxml_open_file($feedurl)) {
    echo "Error while opening news feed.\n";
    exit;
}

$root = $dom->document_element();
$rootchilds = $root->child_nodes();

foreach ($rootchilds as $rootchild)
{
    if ($rootchild->tagname == "channel")
    {
        $channelchilds = $rootchild->child_nodes();

        foreach ($channelchilds as $channelchild)
        {
            if ($channelchild->tagname == "item")
            {
                $itemchilds = $channelchild->child_nodes();
                $newsdata = array();

                foreach ($itemchilds as $itemchild)
                {
                    if (strlen($itemchild->tagname) > 0)
                    {
                        $newsdata[$itemchild->tagname] =
                            $itemchild->get_content();
                    }
                }

                print_news_item($newsdata);
            }
        }
    }
}

function print_news_item($newsdata)
{
    echo '<div class="news">';
    echo '<div class="news_date">' . $newsdata['pubDate'] . '</div>';
    echo '<h3>' . $newsdata['title'] . '</h3>';
    echo '<div class="news_body"><p>' . $newsdata['description'] . '</p></div>';
    echo '</div>';
}
?>
