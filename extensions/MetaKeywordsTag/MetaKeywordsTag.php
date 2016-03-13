<?php
/*
 * MetaKeywordsTag.php - A MediaWiki tag extension for adding <meta> keyword to a page.
 * @author Jim R. Wilson, Joshua C. Lerner, Choshi, brted
 * @version 0.3
 * @copyright Copyright (C) 2007-12 Joshua C. Lerner, Jim R. Wilson
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php 
 * -----------------------------------------------------------------------
 * Description:
 *     This is a MediaWiki extension which adds support for injecting <meta> keyword tag
 *     into the page header.
 * Requirements:
 *     MediaWiki 1.18.0 or higher
 *     PHP 4.x, 5.x or higher
 * Installation:
 *     1. Drop this script (MetaKeywordsTag.php) in $IP/extensions
 *         Note: $IP is your MediaWiki install dir.
 *     2. Enable the extension by adding this line to your LocalSettings.php:
 *         require_once('extensions/MetaKeywordsTag.php');
 * Usage:
 *     Once installed, you may utilize MetaKeywordsTag by adding the <metakey> tag to articles:
 *         <metakey>put, a, list, of, keywords, here</metakey>
 * Version Notes:
 *     version 0.1 (2007):
 *         Initial release.
 *     version 0.2 (Dec 2010):
 *         Changed renderMetaKeywordsTag to not expect $parser as reference.
 *     version 0.3 (Jan 2012):
 *         Change syntax to <metakey>some content</metakey> to support template variable substitution.
 *         Incorporate Joshua Lerner and Choshi's changes they made to MetaDescriptionTag.php,
 *         which was based on the original MetaKeywordsTag.php
 * -----------------------------------------------------------------------
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of 
 * the Software, and to permit persons to whom the Software is furnished to do 
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all 
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR 
 * OTHER DEALINGS IN THE SOFTWARE. 
 * -----------------------------------------------------------------------
 */
# Confrim MW environment
if( !defined( 'MEDIAWIKI' ) ) {
        exit( 1 );
}
 
# Credits
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'MetaKeywordsTag',
        'author' => 'brted - brted&lt;_at_&gt;yahoo.com',
        'url' => 'http://www.mediawiki.org/wiki/Extension:MetaKeywordsTag',
        'description' => 'Tag to inject meta keywords into page header.',
        'version'=> '0.3'
);
# Add Extension Function 
$wgExtensionFunctions[] = 'setupMetaKeywordsTagParserHooks';
 
/**
 * Sets up the MetaKeywordsTag Parser hook and system messages
 */
function setupMetaKeywordsTagParserHooks() {
        global $wgParser, $wgMessageCache;
        $wgParser->setHook( 'metakey', 'renderMetaKeywordsTag' );
}
 
/**
 * Renders the <metakey> tag.
 * @param String $text Incoming text - should always be null or empty (passed by value).
 * @param Array $params Attributes specified for tag - must contain 'content' (passed by value).
 * @param Parser $parser Reference to currently running parser (passed by reference).
 * @return String Always empty.
 */
function renderMetaKeywordsTag( $input, array $args, Parser $parser, PPFrame $frame ) {
 
 
        # Short-circuit with error message if content is not specified.
        if ( !isset($input) ) {
                return
                        ('<div class="errorbox"&gt;'.
                        ' Error: metakey must contain a content attribute.'.
                        '</div&gt;');
        }
    # Retrun encoded content
        return '<!-- META_KEYWORDS '.base64_encode($input).' -->';
}
 
 
// Attach post-parser hook to extract metadata and alter headers
$wgHooks['OutputPageBeforeHTML'][] = 'insertMetaKeywords';
 
/**
 * Adds the <meta> keywords to document head.
 * Usage: $wgHooks['OutputPageBeforeHTML'][] = 'insertMetaKeywords';
 * @param OutputPage $out Handle to an OutputPage object - presumably $wgOut (passed by reference).
 * @param String $text Output text.
 * @return Boolean Always true to allow other extensions to continue processing.
 */
function insertMetaKeywords( $out, $text ) {
 
        # Extract meta keyword
        if (preg_match_all(
                '/<!-- META_KEYWORDS ([0-9a-zA-Z\\+\\/]+=*) -->/m', 
                $text, 
                $matches)===false
        ) return true;
        $data = $matches[1];
 
        # Merge keyword data into OutputPage as meta tag
        foreach ($data AS $item) {
                $content = @base64_decode($item);
                if ($content) 
                        $out->addMeta( 'keywords', $content );
        }
        return true;
}
?>
