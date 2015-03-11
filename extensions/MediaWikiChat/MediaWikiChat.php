<?php
/**
 * An extension to integrate a special page with a built in chat.
 *
 * @file
 * @ingroup Extensions
 * @author Adam Carter
 * @copyright Copyright 2013, Adam Carter
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is not a valid entry point to MediaWiki.' );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'MediaWikiChat',
	'version' => '2.11.2',
	'author' => 'Adam Carter/UltrasonicNXT',
	'url' => 'https://www.mediawiki.org/wiki/Extension:MediaWikiChat',
	'descriptionmsg' => 'chat-desc',
);

// ResourceLoader support for MediaWiki 1.17+
$wgResourceModules['ext.mediawikichat.css'] = array(
	'styles' => 'MediaWikiChat.css',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'MediaWikiChat',
	'position' => 'top' // available since r85616
);
$wgResourceModules['ext.mediawikichat.js'] = array(
	'scripts' => 'MediaWikiChat.js',
	'messages' => array(
		'january', 'february', 'march', 'april', 'may', 'june',
		'july', 'august', 'september', 'october', 'november', 'december',
		'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday',
		'sunday', 'chat-type-your-private-message', 'chat-no-other-users',
		'chat-blocked-from-chat', 'chat-just-now', 'chat-a-minute-ago',
		'chat-quarter-of-an-hour-ago', 'chat-half-an-hour-ago',
		'chat-an-hour-ago', 'minutes-ago', 'chat-youve-been-kicked',
		'chat-you-kicked', 'chat-kicked', 'chat-kick',
		'chat-youve-been-blocked', 'chat-you-blocked', 'chat-blocked',
		'chat-block', 'chat-private-message', 'chat-user-is-moderator',
		'chat-you-are-moderator', 'chat-joined', 'chat-left',
		'chat-mod-image', 'chat-yesterday', 'chat-flood', 'chat-too-long',
		'chat-idle-minutes', 'chat-idle-hours', 'chat-idle-more'
	),
	'dependencies' => 'mediawiki.jqueryMsg',
	'localBasePath' => dirname( __FILE__ ),
	'remoteExtPath' => 'MediaWikiChat',
);

// Set up everything
$dir = dirname( __FILE__ ) . '/';
$wgAutoloadClasses['SpecialChat'] = $dir . 'SpecialChat.php';
$wgAutoloadClasses['MediaWikiChat'] = $dir. 'MediaWikiChatClass.php';
$wgAutoloadClasses['MediaWikiChatHooks'] = $dir. 'MediaWikiChat.hooks.php';
$wgSpecialPages['Chat'] = 'SpecialChat';
$wgMessagesDirs['MediaWikiChat'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['MediaWikiChat'] = $dir . 'MediaWikiChat.i18n.php';

// Config vars
$wgChatKicks = false; // allow 'kicking' of users?
$wgChatSocialAvatars = true; // use SocialProfile avatars?
$wgChatRichMessages = true; // allow the use of wikitext and smileys in chat?
$wgChatSidebarPortlet = true; // show the online users module in the sidebar?
$wgChatOnlineTimeout = 1 * 60 * 100; // how long to wait before a user is considered ofline? (in 100ths of a second)
$wgChatFloodMessages = 3; // maximum messages per...
$wgChatFloodSeconds = 5; // ...seconds.
$wgChatLinkUsernames = false; // link to user pages?
$wgChatMeCommand = false; // enable "/me <text>" command?
$wgChatUseStyleAttribute = true; // allow use of the style attribute on html tags?
$wgChatMaxMessageLength = 1000; // maximum length (characters) of messages

// Hooks
$wgHooks['ParserBeforeInternalParse'][] = 'MediaWikiChatHooks::onParserBeforeInternalParse';
$wgHooks['UserRights'][] = 'MediaWikiChatHooks::onUserRights';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'MediaWikiChatHooks::onLoadExtensionSchemaUpdates';
$wgHooks['SkinBuildSidebar'][] = 'MediaWikiChatHooks::fnNewSidebarItem';
$wgHooks['GetPreferences'][] = 'MediaWikiChatHooks::wfPrefHook';

//API
$wgAutoloadClasses['ChatGetNewAPI'] = $dir . 'GetNew.api.php';
$wgAutoloadClasses['ChatSendAPI'] = $dir . 'Send.api.php';
$wgAutoloadClasses['ChatSendPMAPI'] = $dir . 'SendPM.api.php';
$wgAutoloadClasses['ChatKickAPI'] = $dir . 'Kick.api.php';
$wgAutoloadClasses['ChatAwayAPI'] = $dir . 'Away.api.php';
$wgAPIModules['chatgetnew'] = 'ChatGetNewAPI';
$wgAPIModules['chatsend'] = 'ChatSendAPI';
$wgAPIModules['chatsendpm'] = 'ChatSendPMAPI';
$wgAPIModules['chatkick'] = 'ChatKickAPI';
$wgAPIModules['chataway'] = 'ChatAwayAPI';

// Logs
$wgLogTypes[] = 'chat';
$wgLogActionsHandlers['chat/*'] = 'LogFormatter';
$wgFilterLogTypes['chat'] = true;
$wgLogTypes[] = 'privatechat';
$wgLogActionsHandlers['privatechat/*'] = 'LogFormatter';
$wgFilterLogTypes['privatechat'] = true;
$wgLogRestrictions['privatechat'] = 'viewpmlog';

// Permissions
$wgGroupPermissions['user']['chat'] = true;
$wgRevokePermissions['blockedfromchat']['chat'] = true;

$wgGroupPermissions['chatmod']['modchat'] = true;
$wgGroupPermissions['sysop']['modchat'] = true;
$wgAddGroups['sysop'][] = 'chatmod';
$wgRemoveGroups['sysop'][] = 'chatmod';

$wgAddGroups['chatmod'][] = 'blockedfromchat';
$wgRemoveGroups['chatmod'][] = 'blockedfromchat';
$wgAddGroups['sysop'][] = 'blockedfromchat';
$wgRemoveGroups['sysop'][] = 'blockedfromchat';
