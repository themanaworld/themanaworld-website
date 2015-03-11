<?php

class ChatSendAPI extends ApiBase {

	public function execute() {
		global $wgChatFloodMessages, $wgChatFloodSeconds, $wgChatMaxMessageLength;

		$result = $this->getResult();
		$user = $this->getUser();

		if ( $user->isAllowed( 'chat' ) ) {
			$originalMessage = $this->getMain()->getVal( 'message' );
			$message = MediaWikiChat::parseMessage( $originalMessage );

			if ( $message != '' ) {
				$dbw = wfGetDB( DB_MASTER );

				$id = $user->getId();
				$timestamp = MediaWikiChat::now();

				if ( strlen( $message ) > $wgChatMaxMessageLength ) {
					$result->addValue( $this->getModuleName(), 'error', 'length' );
					return true;
				}

				// Flood check
				$res = $dbw->selectField(
					'chat',
					array( 'count(*)' ),
					array( "chat_timestamp > " . ( $timestamp - ( $wgChatFloodSeconds * 100 ) ), " chat_user_id = " . $id ),
					__METHOD__
				);
				if ( $res > $wgChatFloodMessages ) {
					$result->addValue( $this->getModuleName(), 'error', 'flood' );
					return true;
				}

				$dbw->insert(
					'chat',
					array(
						'chat_user_id' => $id,
						'chat_timestamp' => $timestamp,
						'chat_message' => $message,
						'chat_type' => MediaWikiChat::TYPE_MESSAGE
					)
				);

				$logEntry = new ManualLogEntry( 'chat', 'send' ); // Action bar in log foo
				$logEntry->setPerformer( $user ); // User object, the user who did this action
				$page = SpecialPage::getTitleFor( 'Chat' );
				$logEntry->setTarget( $page ); // The page that this log entry affects
				$logEntry->setParameters( array(
					'4::message' => $originalMessage, // we want the logs to show the source message, not the parsed one
				) );

				$logEntry->insert();

				MediaWikiChat::deleteEntryIfNeeded();
				MediaWikiChat::updateAway( $user );

				$result->addValue( $this->getModuleName(), 'timestamp', $timestamp );

			} else {
				$result->addValue( $this->getModuleName(), 'error', 'empty message' );
			}
		} else {
			$result->addValue( $this->getModuleName(), 'error', 'blockedfromchat' );
		}

		return true;
	}

	public function getDescription() {
		return 'Send a message to the chat.';
	}

	public function getAllowedParams() {
		return array(
			'message' => array (
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		);
	}

	public function getParamDescription() {
		return array(
			'message' => 'The message to send.'
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=chatsend&message=Hello%20World!'
				=> 'Send "Hello World!" to the chat'
		);
	}

	public function mustBePosted() {
		return true;
	}
}