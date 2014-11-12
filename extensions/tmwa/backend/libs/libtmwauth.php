<?php
/**
 * Tmw Auth Interface (Requires Ladmin socket) by wushin
 * Based on mediawiki AuthPlugin
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 *
 * @file
 */

/**
 * Authentication plugin interface. Instantiate a subclass of AuthPlugin
 * and set $wgAuth to it to authenticate against some external tool.
 *
 * The default behavior is not to do anything, and use the local user
 * database for all authentication. A subclass can require that all
 * accounts authenticate externally, or use it only as a fallback; also
 * you can transparently create internal wiki accounts the first time
 * someone logs in who can be authenticated externally.
 */
class TMWAuth extends AuthPlugin {
	/**
	 * Check whether there exists a user account with the given name.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param $username String: username.
	 * @return bool
	 */
	public function userExists( $username ) {
        global $wgTMWAccountLib;
        $ladmin = New $wgTMWAccountLib();
        $user_exists = ($ladmin->check_ban($username, $password) ? False : $ladmin->check_auth($username, $password));
        $ladmin->close();
        return $user_exists;
	}

	/**
	 * Check if a username+password pair is a valid login.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param $username String: username.
	 * @param $password String: user password.
	 * @return bool
	 */
	public function authenticate( $username, $password ) {
        global $wgTMWAccountLib;
        $ladmin = New $wgTMWAccountLib();
        $auth_result = $ladmin->check_auth($username, $password);
        $ladmin->close();
        return $auth_result;
	}

	/**
	 * Modify options in the login template.
	 *
	 * @param $template UserLoginTemplate object.
	 * @param $type String 'signup' or 'login'. Added in 1.16.
	 */
	public function modifyUITemplate( &$template, &$type ) {
        $template->set( 'usedomain', False );
	}

	/**
	 * Set the domain this plugin is supposed to use when authenticating.
	 *
	 * @param $domain String: authentication domain.
	 */
	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Check to see if the specific domain is a valid domain.
	 *
	 * @param $domain String: authentication domain.
	 * @return bool
	 */
	public function validDomain( $domain ) {
		# Override this!
		return True;
	}

	/**
	 * When a user logs in, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * @param $user User object
	 */
	public function updateUser( &$user ) {
		# Override this and do something
		return True;
	}

	/**
	 * Return True if the wiki should create a new local account automatically
	 * when asked to login a user who doesn't exist locally but does in the
	 * external auth database.
	 *
	 * If you don't automatically create accounts, you must still create
	 * accounts in some way. It's not possible to authenticate without
	 * a local account.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * @return Boolean
	 */
	public function autoCreate() {
		return True;
	}

	/**
	 * Allow a property change? Properties are the same as preferences
	 * and use the same keys. 'Realname' 'Emailaddress' and 'Nickname'
	 * all reference this.
	 *
	 * @param $prop string
	 *
	 * @return Boolean
	 */
	public function allowPropChange( $prop = '' ) {
		if ( $prop == 'realname' && is_callable( array( $this, 'allowRealNameChange' ) ) ) {
			return $this->allowRealNameChange();
		} elseif ( $prop == 'emailaddress' && is_callable( array( $this, 'allowEmailChange' ) ) ) {
			return $this->allowEmailChange();
		} elseif ( $prop == 'nickname' && is_callable( array( $this, 'allowNickChange' ) ) ) {
			return $this->allowNickChange();
		} else {
			return True;
		}
	}

	/**
	 * Can users change their passwords?
	 *
	 * @return bool
	 */
	public function allowPasswordChange() {
		return True;
	}

	/**
	 * Should MediaWiki store passwords in its local database?
	 *
	 * @return bool
	 */
	public function allowSetLocalPassword() {
		return True;
	}

	/**
	 * Set the given password in the authentication database.
	 * As a special case, the password may be set to null to request
	 * locking the password to an unusable value, with the expectation
	 * that it will be set later through a mail reset or other method.
	 *
	 * Return True if successful.
	 *
	 * @param $user User object.
	 * @param $password String: password.
	 * @return bool
	 */
	public function setPassword( $user, $password ) {
        global $wgTMWAccountLib;
        $ladmin = New $wgTMWAccountLib();
        $set_pass_res = $ladmin->change_pass($user, $password);
        $ladmin->close();
		return $set_pass_res;
	}

	/**
	 * Update user information in the external authentication database.
	 * Return True if successful.
	 *
	 * @param $user User object.
	 * @return Boolean
	 */
	public function updateExternalDB( $user ) {
		return False;
	}

	/**
	 * Check to see if external accounts can be created.
	 * Return True if external accounts can be created.
	 * @return Boolean
	 */
	public function canCreateAccounts() {
		return True;
	}

	/**
	 * Add a user to the external authentication database.
	 * Return True if successful.
	 *
	 * @param $user User: only the name should be assumed valid at this point
	 * @param $password String
	 * @param $email String
	 * @param $realname String
	 * @return Boolean
	 */
	public function addUser( $user, $password, $email = '', $realname = '' ) {
        global $wgTMWAccountLib;
        // Gender is currently hard set
        $sex = 'M';
        $ladmin = New $wgTMWAccountLib();
        $add_user_res = $ladmin->create_account($user, $password, $sex, $email);
        $ladmin->close();
        return $add_user_res;
	}

	/**
	 * Return True to prevent logins that don't authenticate here from being
	 * checked against the local database's password fields.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * @return Boolean
	 */
	public function strict() {
		return False;
	}

	/**
	 * Check if a user should authenticate locally if the global authentication fails.
	 * If either this or strict() returns True, local authentication is not used.
	 *
	 * @param $username String: username.
	 * @return Boolean
	 */
	public function strictUserAuth( $username ) {
		return False;
	}

	/**
	 * When creating a user account, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * @param $user User object.
	 * @param $autocreate Boolean: True if user is being autocreated on login
	 */
	public function initUser( &$user, $autocreate = False ) {
		# Override this to do something.
	}

	/**
	 * If you want to munge the case of an account name before the final
	 * check, now is your chance.
	 */
	public function getCanonicalName( $username ) {
		return ucwords(strtolower($username));
	}

	/**
	 * Get an instance of a User object
	 *
	 * @param $user User
	 *
	 * @return AuthPluginUser
	 */
	public function getUserInstance( User &$user ) {
		return new AuthPluginUser( $user );
	}

	/**
	 * Get a list of domains (in HTMLForm options format) used.
	 *
	 * @return array
	 */
	public function domainList() {
		return array();
	}
}
?>
