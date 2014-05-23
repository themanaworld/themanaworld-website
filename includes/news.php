<?php
// Parses news.html
// returns feed

function printNews($num='all') {
    $count = 0;
    $content = "";
    $handle = @fopen("news.html", "r");
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $content .= $buffer;
            if (preg_match('/<\/div>/',$buffer)) {
                $count++;
            }
            if ($count == $num && $num != 'all') {
                $content .= '<div class="read-more"><a class="more" href="/news-feed.php">More News >></a></div>';
                break 1;
            }
        }
    }
    fclose($handle);
    return $content;
}
?>
