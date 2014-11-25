<?php
class GameNewsPage extends SpecialPage {

    public function __construct() {
        parent::__construct('GameNews');
    }
     
    public function execute( $par ) {
        $request = $this->getRequest();
        $output = $this->getOutput();
        $this->setHeaders();
        $output->addMeta('description', 'Game News for The Mana World');
        $output->addMeta('keywords', 'news, game news, whats new, current release, new changes');

        $wikitext = self::printNews();

        $output->addWikiText( $wikitext );
    }
    // Parses news.html
    // displays feed
    public function printNews($num='all') {
        global $wgTMWNews;
        $count = 0;
        $content = "";
        $handle = @fopen($wgTMWNews, "r");
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
        $output = $this->getOutput();
        $output->addHTML($content);
        fclose($handle);
    }
}
?>
