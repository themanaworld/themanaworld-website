<?php
/**
 * Class containing basic setup functions.
 */
class TMWAccountSetup {
	/**
	 * Register source code paths.
	 * This function must NOT depend on any config vars.
	 * 
	 * @param $classes Array $classes
	 * @param $messagesFiles Array $messagesFiles
	 * @return void
	 */
	public static function defineSourcePaths( array &$classes, array &$messagesFiles ) {
		$dir = dirname( __FILE__ );

		# Basic directory layout
		$backendDir       = "$dir/backend";
		$frontendDir      = "$dir/frontend";
		$langDir          = "$dir/frontend/language/";

		# Main i18n file and special page alias file
		$messagesFiles['TMWAccount'] = "$langDir/TMWAccount.i18n.php";
		$messagesFiles['TMWAccountAliases'] = "$langDir/TMWAccount.alias.php";

		# UI setup class
		$classes['TMWAccountUISetup'] = "$frontendDir/TMWAccountUI.setup.php";
		# UI event handler classes
		$classes['TMWAccountUIHooks'] = "$frontendDir/TMWAccountUI.hooks.php";

		# UI to request an account
		$classes['GameAccountPage'] = "$frontendDir/registration.php";
		$classes['GameNewsPage'] = "$frontendDir/news.php";

		# Utility Models
        $classes['TMWA'] = "$backendDir/models/packets.php";
		$classes['TMWAccount'] = "$backendDir/models/account.php";
		$classes['TMWMail'] = "$backendDir/models/email.php";

		# Ladmin Class
		$classes['phpLadmin'] = "$backendDir/libs/libladmin.php";
		$classes['dbLadmin'] = "$backendDir/libs/libladmin-db.php";

        # TMW to Wiki Auth / Accounts Modules
        $classes['TMWAuth'] = "$backendDir/libs/libtmwauth.php";
	}
}
