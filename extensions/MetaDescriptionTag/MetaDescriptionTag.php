<?php
/*
 * MetaDescriptionTag.php - A MediaWiki tag extension for adding <meta> description to a page.
 * @author Joshua C. Lerner
 * @version 0.3
 * @copyright Copyright (C) 2007-8 Joshua C. Lerner, Jim R. Wilson, Dror Snir
 * @license The MIT License - http://www.opensource.org/licenses/mit-license.php 
 * -----------------------------------------------------------------------
 * Description:
 *     This is a MediaWiki extension which adds support for injecting a <meta> description tag
 *     into the page header.
 * Requirements:
 *     MediaWiki 1.6.x, 1.8.x, 1.9.x, 1.13.x or higher
 *     PHP 4.x, 5.x or higher
  * Installation:
 *     1. Drop this script (MetaDescriptionTag.php) in $IP/extensions
 *         Note: $IP is your MediaWiki install dir.
 *     2. Enable the extension by adding this line to your LocalSettings.php:
 *         require_once('extensions/MetaDescriptionTag.php');
 * Usage:
 *     Once installed, you may utilize MetaDescriptionTag by adding the <metadesc> tag to articles:
 *         <metadesc> Home page for the MetaDescriptionTag MediaWiki extension </metadesc>
 * Version Notes:
 *     version 0.1:
 *         Initial release.
 *     version 0.2:
 *         Change syntax to <metadesc>some content</metadesc> to support template variable substitution.
 *     version 0.3:
 *          Fix i18n to work with v1.16+, sanitize output using htmlspecialchars()
 * 
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
 
if( !defined( 'MEDIAWIKI' ) ) {
        echo "This file is part of MediaWiki, it is not a valid entry point.\n";
        exit( 1 );
}
 
# Credits
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'MetaDescriptionTag',
        'author' => 'Joshua C. Lerner',
        'url' => 'https://www.mediawiki.org/wiki/Extension:MetaDescriptionTag',
        'descriptionmsg' => 'metadescriptiontag-desc',
        'version'=> '0.3'
);
 
$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$wgExtensionMessagesFiles['MetaDescriptionTag'] = $dir . 'MetaDescriptionTag.i18n.php';
$wgHooks['ParserFirstCallInit'][] = 'setupMetaDescriptionTagParserHooks';
$wgHooks['OutputPageBeforeHTML'][] = 'insertMetaDescription'; // Attach post-parser hook to extract metadata and alter headers
 
/**
 * Sets up the MetaDescriptionTag Parser hook and system messages
 */
function setupMetaDescriptionTagParserHooks( &$parser ) {
        $parser->setHook( 'metadesc', 'renderMetaDescriptionTag' );
        return true;
}
 
/**
 * Renders the <metadesc> tag.
 * @param String $text Incoming text - should always be null or empty (passed by value).
 * @param Array $params Attributes specified for tag - must contain 'content' (passed by value).
 * @param Parser $parser Reference to currently running parser (passed by reference).
 * @return String Always empty.
 */
function renderMetaDescriptionTag( $text, $params, Parser $parser, PPFrame $frame) {
 
        // Short-circuit with error message if content is not specified.
        if ( !isset($text) ) {
                return
                        '<div class="errorbox">'.
                        wfMsgForContent('metadescriptiontag-missing-content').
                        '</div>';
        }
 
        return '<!-- META_DESCRIPTION '.base64_encode( htmlspecialchars( $text ) ).' -->';
}
 
/**
 * Adds the <meta> description to document head.
 * Usage: $wgHooks['OutputPageBeforeHTML'][] = 'insertMetaDescription';
 * @param OutputPage $out Handle to an OutputPage object - presumably $wgOut (passed by reference).
 * @param String $text Output text.
 * @return Boolean Always true to allow other extensions to continue processing.
 */
function insertMetaDescription( $out, $text ) {
 
        // Extract meta description
        if (preg_match_all(
                '/<!-- META_DESCRIPTION ([0-9a-zA-Z\\+\\/]+=*) -->/m', 
                $text, 
                $matches)===false
        ) return true;
        $data = $matches[1];
 
        // Merge description data into OutputPage as meta tag
        foreach ($data AS $item) {
                $content = @base64_decode($item);
                if ($content) 
                        $out->addMeta( 'description', $content );
        }
        return true;
}
?>
