<?php
/**
 * Class containing hooked functions for a TMWAccount environment
 */
class TMWAccountUIHooks {
	/**
	 * @param $template
	 * @return bool
	 */
	public static function addRequestLoginText( &$template ) {
		$context = RequestContext::getMain();
		# Add a link to GameAccount from UserLogin
		if ( !$context->getUser()->isAllowed( 'createaccount' ) ) {
			$template->set( 'header', wfMessage( 'gameaccount-loginnotice')->parse());
		}
		return true;
	}

	/**
	 * @param $personal_urls
	 * @param $title
	 * @return bool
	 */
	public static function setRequestLoginLinks( array &$personal_urls, &$title ) {
		if ( isset( $personal_urls['anonlogin'] ) ) {
			$personal_urls['anonlogin']['text'] = wfMessage( 'nav-login-createaccount' );
		} elseif ( isset( $personal_urls['login'] ) ) {
			$personal_urls['login']['text'] = wfMessage( 'nav-login-createaccount' );
		}
		return true;
	}

    /**
     * Add "x email-tmwed open account requests" notice
     * @param $notice
     * @return bool
     */
    public static function tmwAccountsNotice( OutputPage &$out, Skin &$skin ) {
        global $wgTMWAccountNotice;

        $context = $out->getContext();
        if ( !$wgTMWAccountNotice || !$context->getUser()->isAllowed( 'tmwaccount' ) ) {
            return true;
        }
        # Only show on some special pages
        $title = $context->getTitle();
        if ( !$title->isSpecial( 'Recentchanges' ) && !$title->isSpecial( 'Watchlist' ) ) {
            return true;
        }
        $count = TMWAccount::getOpenEmailTMWedCount( '*' );
        if ( $count > 0 ) {
            $out->prependHtml( // parsemag for PLURAL
                '<div id="mw-tmwaccount-msg" class="plainlinks mw-tmwaccount-bar">' .
                $out->parse( wfMessage( 'tmwaccount-newrequests', $count )->text(), false ) .
                '</div>'
            );
        }
        return true;
    }
}
