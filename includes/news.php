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

$xml = new XMLReader();

if (!$xml) {
    echo "Error, no XMLReader.\n";
    exit;
}

$xml->open($feedurl);
xml_read_rss($xml);
$xml->close();

function xml_read_rss($xml)
{
    if ($xml->next("rss")) {
	xml_read_channels($xml);
    } else {
	echo "Error, not an rss feed.";
    }
}

function xml_read_channels($xml)
{
    while ($xml->read()) {
	switch ($xml->nodeType) {
	case XMLReader::ELEMENT:
	    if ($xml->name == "channel") {
		xml_read_channel($xml);
	    } else {
		xml_read_unknown_element($xml);
	    }
	    break;
	case XMLReader::END_ELEMENT:
	    return;
	}
    }
}

function xml_read_channel($xml)
{
    while ($xml->read()) {
	switch ($xml->nodeType) {
	case XMLReader::ELEMENT:
	    if ($xml->name == "item") {
		xml_read_item($xml);
	    } else {
		xml_read_unknown_element($xml);
	    }
	    break;
	case XMLReader::END_ELEMENT:
	    return;
	}
    }
}

function xml_read_item($xml)
{
    $newsdata = array();

    while ($xml->read()) {
	switch ($xml->nodeType) {
	case XMLReader::ELEMENT:
	    $newsdata[$xml->name] = $xml->readString();
	    xml_read_unknown_element($xml);
	    break;
	case XMLReader::END_ELEMENT:
	    print_news_item($newsdata);
	    return;
	}
    }
}

function xml_read_unknown_element($xml)
{
    while ($xml->read()) {
	switch ($xml->nodeType) {
	case XMLReader::ELEMENT:
	    xml_read_unknown_element($xml);
	    break;
	case XMLReader::END_ELEMENT:
	    return;
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
