<?php
if ( !defined( 'MEDIAWIKI' ) ) {
    echo "TMWAccount extension\n";
    exit( 1 ) ;
}
$wgExtensionCredits['validextensionclass'][] = array(
    'path' => __FILE__,
    'name' => 'TMWA Mediawiki',
    'author' => 'wushin', 
    'url' => 'https://github.com/themanaworld/themanaworld-website', 
    'description' => 'Ladmin in Mediawiki',
    'version'  => 0.5,
    'license-name' => "GPL 3.0",
);

# Define were PHP files and i18n files are located
require( dirname( __FILE__ ) . '/TMWAccount.setup.php' );
TMWAccountSetup::defineSourcePaths( $wgAutoloadClasses, $wgExtensionMessagesFiles );

# Actually register special pages
TMWAccountUISetup::defineSpecialPages( $wgSpecialPages, $wgSpecialPageGroups );

# UI-related hook handlers
TMWAccountUISetup::defineHookHandlers( $wgHooks );

# Schema updates for tmwa.php
$wgHooks['LoadExtensionSchemaUpdates'][] = 'TMWAccountSql';
function TMWAccountSql( DatabaseUpdater $updater ) {
    $updater->addExtensionTable( 'tmw_accounts', dirname( __FILE__ ) . '/sql/createTables.sql', true );
    return true;
}

# Load the extension after setup is finished
$wgExtensionFunctions[] = 'efLoadTMWAccount';

/**
 * This function is for setup that has to happen in Setup.php
 * when the functions in $wgExtensionFunctions get executed.
 * @return void
 */
function efLoadTMWAccount() {
    global $wgEnableEmail;
    # This extension needs email enabled!
    # Otherwise users can't get their passwords...
    if ( !$wgEnableEmail ) {
        echo "TMWAccount extension requires \$wgEnableEmail set to true.\n";
        exit( 1 ) ;
    }
}
?>
