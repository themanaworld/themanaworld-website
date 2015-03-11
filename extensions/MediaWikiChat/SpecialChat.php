<?php

class SpecialChat extends SpecialPage {

	/**
	 * Constructor -- set up the new special page
	 */
	public function __construct() {
		parent::__construct( 'Chat', 'chat' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the special page or null
	 */
	public function execute( $par ) {
		global $wgChatSocialAvatars, $wgChatKicks, $wgChatLinkUsernames, $wgChatMeCommand, $wgChatMaxMessageLength;

		$out = $this->getOutput();
		$user = $this->getUser();

		// Set the page title, robot policies, etc.
		$this->setHeaders();

		if ( !$this->getUser()->isAllowed( 'chat' ) ) {
			$groups = $this->getUser()->getGroups();
			if ( in_array( 'blockedfromchat', $groups ) ) {
				$out->addWikiMsg( 'chat-blocked-from-chat' );
			} else {
				$out->addWikiMsg( 'chat-not-allowed' );
			}

		} else {
			// Load the GUI (from its own, separate file)
			include( 'SpecialChat.template.php' );
			$template = new SpecialChatTemplate;

			$mention = $user->getOption( 'chat-ping-mention' );
			$pm = $user->getOption( 'chat-ping-pm' );
			$message = $user->getOption( 'chat-ping-message' );
			$joinleave = $user->getOption( 'chat-ping-joinleave' );

			// Load modules via ResourceLoader
			$modules = array(
					'ext.mediawikichat.css',
					'ext.mediawikichat.js',
			);
			$out->addModules( $modules );

			$out->addJsConfigVars(
				array(
					'wgChatKicks' => $wgChatKicks,
					'wgChatSocialAvatars' => $wgChatSocialAvatars,
					'wgChatLinkUsernames' => $wgChatLinkUsernames,
					'wgChatPingMentions' => $mention,
					'wgChatPingPMs' => $pm,
					'wgChatPingMessages' => $message,
					'wgChatPingJoinLeaves' => $joinleave,
					'wgChatMeCommand' => $wgChatMeCommand,
					'wgChatMaxMessageLength' => $wgChatMaxMessageLength,
				)
			);

			if ( !$user->getOption( 'chat-fullscreen' ) ) {
				$out->addTemplate( $template ); // Output the GUI HTML

			} else {
				$out->disable();

				echo $out->headElement( $this->getSkin() );

				echo "<div id='wrapper' style='background-color: white; margin: 2em; padding: 1em; border:1px solid #ccc;'>";
				$template->execute(); // print template
				echo "</div>";

				echo $this->getSkin()->bottomScripts();
				echo "</body></html>";
			}
		}
	}
}
