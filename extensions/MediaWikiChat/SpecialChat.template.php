<?php
/**
 * @file
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

/**
 * Main user interface for Special:Chat.
 *
 * @ingroup Templates
 */
class SpecialChatTemplate extends QuickTemplate {
	public function execute() {
?>
		<div id="mwchat-topic">
			<?php echo wfMessage( 'chat-topic' )->parse(); ?>
		</div>
		<div id="mwchat-container">
			<div id="mwchat-main" >
				<div id="mwchat-content">
					<table id="mwchat-table">
						<tr class="mwchat-message"> <?php //ensures columns are appropriately wide ?>
							<td class="mwchat-item-user"></td>
							<td class="mwchat-item-avatar"></td>
							<td class="mwchat-item-messagecell"></td>
						</tr>
					</table>
				</div>
				<div id="mwchat-type">
					<input type="text" placeholder="<?php echo wfMessage( 'chat-type-your-message' )->plain() ?>" />
				</div>
				<div id="mwchat-options">
					<a target="_blank" href="<?php echo SpecialPage::getTitleFor( 'Preferences', false, 'mw-prefsection-misc' )->getFullURL(); ?>"><?php echo wfMessage( 'chat-change-preferences' ); ?></a>
				</div>
			</div>
			<div id="mwchat-users">
				<div id="mwchat-no-other-users" style="display:none;">
					<?php echo wfMessage( 'chat-no-other-users' )->plain() ?>
				</div>
			</div>
			<div id="mwchat-me">
				<img src="" alt="" />
				<span class="mwchat-useritem-user"></span>
			</div>
		</div>
<?php
	}
}