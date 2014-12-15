<?php
/** \file
* \brief Contains setup code for the GoogleSiteSearch Extension.
*/

/**
 * GoogleSiteSearch Extension for MediaWiki
 *
 * Copyright (C) Ryan Finnie
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "$IP/extensions/GoogleSiteSearch/GoogleSiteSearch.php" );
EOT;
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'GoogleSiteSearch',
	'author' => 'Ryan Finnie',
	'url' => 'https://www.mediawiki.org/wiki/Extension:GoogleSiteSearch',
	'descriptionmsg' => 'googlesitesearch-desc',
	'version' => '2.2',
);

# Default configuration globals
if ( !isset( $wgGoogleSiteSearchCSEID ) ) {
	$wgGoogleSiteSearchCSEID = '';
}
if ( !isset( $wgGoogleSiteSearchOnly ) ) {
	$wgGoogleSiteSearchOnly = false;
}
if ( !isset( $wgGoogleSiteSearchCharset ) ) {
	$wgGoogleSiteSearchCharset = 'UTF-8';
}

$dir = dirname( __FILE__ ) . '/';

# Define internationalizations
$wgMessagesDirs['GoogleSiteSearch'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['GoogleSiteSearch'] = $dir . 'GoogleSiteSearch.i18n.php';

# Hook into SpecialSearchResultsPrepend (MW 1.21+)
$wgHooks['SpecialSearchResultsPrepend'][] = 'GoogleSiteSearch';

function GoogleSiteSearch( $t, $out, $term ) {
	global $wgGoogleSiteSearchCSEID;
	global $wgGoogleSiteSearchOnly;
	global $wgGoogleSiteSearchCharset;

	# Return immediately if the CSE ID is not configured
	if ( !$wgGoogleSiteSearchCSEID ) {
		return true;
	}

	# Return immediately if no search term was supplied
	if ( !$term ) {
		return true;
	}

	$dir = dirname( __FILE__ ) . '/';
	$lang = $t->getLang();

	# Allow for local overrides of the base HTML
	if ( file_exists( $dir . 'GoogleSiteSearch.content.html' ) ) {
		$outhtml = file_get_contents ( $dir . 'GoogleSiteSearch.content.html' );
	} else {
		$outhtml = file_get_contents ( $dir . 'GoogleSiteSearch.content.default.html' );
	}

	# Replace variable data in the HTML
	$outhtml = str_replace( '_GSS_CSE_ID_', FormatJson::encode( $wgGoogleSiteSearchCSEID ), $outhtml );
	$outhtml = str_replace( '_GSS_TERM_ESCAPE_', FormatJson::encode( $term ), $outhtml );
	$outhtml = str_replace( '_GSS_LANG_', FormatJson::encode( $lang->getCode() ), $outhtml );
	$outhtml = str_replace( '_GSS_LOADING_', htmlentities( wfMessage( 'googlesitesearch-loading', $wgGoogleSiteSearchCharset ) ), $outhtml );

	# Add it!
	$out->addWikiText( '== ' . wfMessage( 'googlesitesearch-google-results' ) . ' ==' );
	$out->AddHTML( $outhtml );

	# Do not return wiki results if configured that way
	if ( $wgGoogleSiteSearchOnly ) {
		return false;
	} else {
		$out->addWikiText( '== ' . wfMessage( 'googlesitesearch-wiki-results' ) . ' ==' );
		return true;
	}
}
