<?php
/**
 * Spoilers
 * Spoilers Mediawiki Settings
 *
 * @author: Telshin
 * @license: LGPLv3 http://opensource.org/licenses/lgpl-3.0.html
 * @package: Spoilers
 * @link: http://www.mediawiki.org/wiki/Extension:Spoilers
 */
 
/******************************************/
/* Credits                                */
/******************************************/
$wgExtensionCredits['specialpage'][] = array (
												'path'				=>	__FILE__,
												'name'				=>	'Spoilers',
												'author'			=>	'Tim Aldridge',
												'descriptionmsg'	=>	'spoilers_description',
												'version'			=>	'1.1',
												'license-name'		=>	'LGPLv3'
											);

/******************************************/
/* Language Strings, Page Aliases, Hooks  */
/******************************************/
$extDir = dirname( __FILE__ );

$wgMessagesDirs['Spoilers']				= __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Spoilers']	= $extDir . "/Spoilers.i18n.php";

$wgAutoloadClasses['Spoilers']			= $extDir . "/Spoilers.hooks.php";

$wgResourceModules['ext.spoilers'] = array (
											'styles'		=> array( 'css/spoilers.css' ),
											'scripts'		=> array( 'js/spoilers.js' ),
											'localBasePath'	=> __DIR__,
											'remoteExtPath'	=> 'Spoilers',
											'dependencies'	=> array()
											);

$wgHooks['ParserFirstCallInit'][]		= "Spoilers::onParserFirstCallInit";
?>
