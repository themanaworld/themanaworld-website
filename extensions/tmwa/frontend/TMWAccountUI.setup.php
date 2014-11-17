<?php
/**
 * Class containing hooked functions for a TMWAccount environment
 */
class TMWAccountUISetup {
	/**
	 * Register TMWAccount hooks.
	 * @param $hooks Array $wgHooks (assoc array of hooks and handlers)
	 * @return void
	 */
	public static function defineHookHandlers( array &$hooks ) {
		# Make sure "login / create account" notice still as "create account"
		$hooks['PersonalUrls'][] = 'TMWAccountUIHooks::setRequestLoginLinks';
		# Add notice of where to request an account at UserLogin
		$hooks['UserCreateForm'][] = 'TMWAccountUIHooks::addRequestLoginText';
		$hooks['UserLoginForm'][] = 'TMWAccountUIHooks::addRequestLoginText';
		# Status header like "new messages" bar
		$hooks['BeforePageDisplay'][] = 'TMWAccountUIHooks::tmwAccountsNotice';
		# Register admin pages for AdminLinks extension.
		$hooks['AdminLinks'][] = 'TMWAccountUIHooks::tmwAccountAdminLinks';
	}

	/**
	 * Register TMWAccount special pages as needed.
	 * @param $pages Array $wgSpecialPages (list of special pages)
	 * @param $groups Array $wgSpecialPageGroups (assoc array of special page groups)
	 * @return void
	 */
	public static function defineSpecialPages( array &$pages, array &$groups ) {
		$pages['GameAccount'] = 'GameAccountPage';
		$groups['GameAccount'] = 'login';
		$pages['GameNews'] = 'GameNewsPage';
		$groups['GameNews'] = 'changes';
	}
}
