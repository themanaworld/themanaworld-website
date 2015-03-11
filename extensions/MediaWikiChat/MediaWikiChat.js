/* global $, mw */
var MediaWikiChat = {
	users: [],
	amIMod: false,
	amI: false,
	firstTime: true,
	interval: 7000,
	newInterval: null,
	redoInterval: null,
	userData: [],
	focussed: true,
	title: document.title,
	away: false,

	pad: function( num, size ) {
		var s = num + '';
		while ( s.length < size ) {
			s = '0' + s;
		}
		return s;
	},

	safe: function( string ) {
		return string.replace( /[^\w\s]|/g, '' ).replace( / /g, '' );
	},

	ie: function() {
		return navigator.appVersion.indexOf( "MSIE" ) != -1;
	},

	unique: function( array ) {
		var a = array.concat();

		for ( var i = 0; i < a.length; ++i ) {
			for ( var j = i + 1; j < a.length; ++j ) {
				if ( a[i] === a[j] ) {
					a.splice( j--, 1 );
				}
			}
		}

		return a;
	},

	now: function() {
		return Math.round( new Date().getTime() / 10 ); // we need it in 10 millisecond sizes
	},

	realTimestamp: function( timestamp ) {
		var d = new Date();

		d.setTime( timestamp * 10 );

		var months = [ 'january', 'february', 'march', 'april', 'may', 'june',
			'july', 'august', 'september', 'october', 'november', 'december' ];
		var month = mw.message( months[d.getMonth()] ).text();

		var date = d.getDate();
		var hours = MediaWikiChat.pad( d.getHours(), 2 );
		var mins = MediaWikiChat.pad( d.getMinutes(), 2 );

		return month + ' ' + date + ', ' + hours + ':' + mins;
	},

	prettyTimestamp: function( timestamp ) {
		var dateThen = new Date();
		dateThen.setTime( timestamp * 10 );
		var dayThen = dateThen.getDate();

		var dateNow = new Date();
		var tsNow = parseInt( dateNow.getTime() / 10, 10 );
		var dayNow = dateNow.getDate();

		var diff = ( tsNow - timestamp ) / 100;

		if ( diff < 30 ) {
			return mw.message( 'chat-just-now' ).text();
		} else if ( diff < 2 * 60 ) {
			return mw.message( 'chat-a-minute-ago' ).text();
		} else if ( diff < 60 * 60 ) {
			return mw.message( 'minutes-ago', Math.floor( diff / 60 ) ).text();
		} else {
			if ( dayNow == dayThen ) {
				return MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
			} else {
				if ( dayNow == dayThen + 1 ) { // @TODO handle 31s
					return mw.message( 'chat-yesterday' ).text().toLowerCase() + ', ' + MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' + MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				} else {
					var day;
					var days = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
					day = mw.message( days[dateThen.getDay()] ).text().toLowerCase();
					return day + ', ' +
						MediaWikiChat.pad( dateThen.getHours(), 2 ) + ':' +
						MediaWikiChat.pad( dateThen.getMinutes(), 2 );
				}
			}
		}
	},

	redoTimestamps: function() {
		$.each( $( '.mwchat-item-timestamp.pretty' ), function( index, item ) {
			item = $( item );
			var timestamp = item.attr( 'data-timestamp' );
			var oldPretty = item.html();
			var newPretty = MediaWikiChat.prettyTimestamp( timestamp );
			if ( oldPretty != newPretty ) {
				item.fadeOut( 250, function() {
					item.html( newPretty );
					item.fadeIn( 250 );
				});
			}
		});
	},

	htmlTimestamp: function( timestamp ) {
		var html = '<span class="mwchat-item-timestamp-container">';
		html += '<span class="mwchat-item-timestamp pretty" data-timestamp="' + timestamp + '">';
		html += MediaWikiChat.prettyTimestamp( timestamp );
		html += '</span><span class="mwchat-item-timestamp real" style="display:none;">';
		html += MediaWikiChat.realTimestamp( timestamp );
		html += '</span></span>';
		return html;
	},

	getNew: function() {
		var focussed = '';
		if ( MediaWikiChat.focussed ) {
			focussed = 'true';
		}
		$.ajax( {
			url: mw.config.get( 'wgScriptPath' ) + '/api.php',
			data: { 'action': 'chatgetnew', 'format': 'json', 'focussed': focussed },
			cache: false
		} ).done( function( response ) {
			var data = response.chatgetnew;

			var onlineUsers = [];

			for ( var userId in data.users ) {
				var user = data.users[userId];
				MediaWikiChat.userData[userId] = { 'name': user.name, 'avatar': user.avatar, 'gender': user.gender };
				if ( user.mod ) {
					MediaWikiChat.userData[userId].mod = true;
				}
				if ( user.online && user.id != mw.config.get( 'wgUserId' ) ) {
					onlineUsers[onlineUsers.length] = userId;
					MediaWikiChat.userData[userId].away = user.away;
				}
			}

			MediaWikiChat.amIMod = data.users[mw.config.get( 'wgUserId' )].mod;

			MediaWikiChat.doUsers( onlineUsers );

			for ( var userId in data.users ) { // has to be done after doUsers
				user = MediaWikiChat.userData[userId];
				if ( user.id != mw.config.get( 'wgUserId' ) ) {
					userE = MediaWikiChat.safe( user.name );
					MediaWikiChat.greyscale( $( '#mwchat-users #' + userE ), user.away );
				}
			}

			for ( var messageTimestamp in data.messages ) {
				var message = data.messages[messageTimestamp];
				MediaWikiChat.addMessage(
					message.from,
					message['*'],
					messageTimestamp
				);
			}

			for ( var pmTimestamp in data.pms ) {
				var pm = data.pms[pmTimestamp];

				MediaWikiChat.addPrivateMessage(
					pm.from,
					pm.conv,
					pm['*'],
					pmTimestamp
				);
				var div = $( '#' + MediaWikiChat.safe( pm.conv ) + ' .mwchat-useritem-content' );
				var objDiv = $( '#' + MediaWikiChat.safe( pm.conv ) + ' .mwchat-useritem-content' );
				objDiv.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
			}

			for ( var kickTimestamp in data.kicks ) {
				var kick = data.kicks[kickTimestamp];
				MediaWikiChat.showKickMessage( MediaWikiChat.userData[kick.from], MediaWikiChat.userData[kick.to], kickTimestamp );
			}
			for ( var blockTimestamp in data.blocks ) {
				var block = data.blocks[blockTimestamp];
				MediaWikiChat.showBlockMessage( MediaWikiChat.userData[block.from], MediaWikiChat.userData[block.to], blockTimestamp );
			}
			for ( var unblockTimestamp in data.unblocks ) {
				var unblock = data.unblocks[unblockTimestamp];
				MediaWikiChat.showUnblockMessage( MediaWikiChat.userData[unblock.from], MediaWikiChat.userData[unblock.to], unblockTimestamp );
			}

			if ( data.kick ) {
				$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
				$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
				clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.getNew();
			}

			if ( data.messages || data.kicks || data.blocks || data.unblocks ) {
				MediaWikiChat.scrollToBottom();
			}
			MediaWikiChat.addMe();

		} );
	},

	scrollToBottom: function() {
		var div = $( '#mwchat-content' );
		div.animate( { 'scrollTop': div[0].scrollHeight }, 1000 );
	},

	showKickMessage: function( from, to, timestamp ) {
		var message;
		if ( to.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-youve-been-kicked', from.name, mw.user ).text();
		} else if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-kicked', to.name, mw.user ).text();
		} else {
			message = mw.message( 'chat-kicked', from.name, to.name, from.gender ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showBlockMessage: function( from, to, timestamp ) {
		var message;
		if ( to.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-youve-been-blocked', from.name, mw.user ).text();
			$( '#mwchat-type input' ).attr( 'disabled', 'disabled' );
			$( '#mwchat-users div input' ).attr( 'disabled', 'disabled' );
		} else if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-blocked', to.name, mw.user ).text();
		} else {
			message = mw.message( 'chat-blocked', from, to.name, from.gender ).text();
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	showUnblockMessage: function( from, to, timestamp ) {
		var message = '';
		if ( from.name == mw.config.get( 'wgUserName' ) ) {
			message = mw.message( 'chat-you-unblocked', to.name, mw.user );
		} else {
			message = mw.message( 'chat-unblocked', from.name, to.name, from.gender );
		}
		MediaWikiChat.addSystemMessage( message, timestamp );
	},

	addSystemMessage: function( text, timestamp ) {
		var html = '<tr class="mwchat-message system">';
		html += '<td colspan="3" class="mwchat-item-messagecell"><span class="mwchat-item-message">';
		html += text;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	addMessage: function( userId, message, timestamp ) {

		if ( message.substring( 0, 4 ) == '/me ' && mw.config.get( 'wgChatMeCommand' ) ) {
			return MediaWikiChat.addSystemMessage ( '* ' + MediaWikiChat.userData[userId].name + message.substring( 3 ), timestamp );
		}

		var user = MediaWikiChat.userData[userId];
		var html = '<tr class="mwchat-message">';
		var mention = false;

		if ( message.toLowerCase().indexOf( mw.config.get( 'wgUserName' ).toLowerCase() ) != -1 ) {
			mention = true;
			MediaWikiChat.flashMention();
		} else {
			MediaWikiChat.flash();
		}

		html += '<td class="mwchat-item-user">';
		if ( mw.config.get( 'wgChatLinkUsernames' ) ) {
			html += '<a href="' + mw.config.get( 'wgScriptPath' ) + '/index.php?title=user:' + user.name + '" target="_blank">' + user.name + '</a>';
		} else {
			html += user.name;
		}
		html += '</td>';
		html += '<td class="mwchat-item-avatar">';
		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" /></td>';
		}
		html += '<td class="mwchat-item-messagecell"><span class="mwchat-item-message"';
		if ( mention ) {
			html += ' data-read="true"';
		}
		html += '>';
		html += message;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</td></tr>';

		MediaWikiChat.addGeneralMessage( html, timestamp );
	},

	addGeneralMessage: function( html, timestamp ) {
		var post = true;

		$( '.mwchat-item-timestamp.pretty' ).each( function( index, value ) {
			if ( $( value ).attr( 'data-timestamp' ) == timestamp ) {
				post = false;
			}
		} );

		if ( post ) {
			var elem = $( html ).appendTo( $( '#mwchat-table' ) );

			elem.hover( function() {
				elem.find( '.pretty' ).css( 'visibility', 'hidden' );
				elem.find( '.real' ).show();
			}, function() {
				elem.find( '.real' ).hide();
				elem.find( '.pretty' ).css( 'visibility', 'visible' );
			} );

			elem.find( 'a' ).attr( 'target', '_blank' );
		}
	},

	addPrivateMessage: function( userId, convwith, message, timestamp ) {
		var user = MediaWikiChat.userData[userId];
		var convwithE = MediaWikiChat.safe( convwith );

		var html = '<div class="mwchat-message">';
		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" alt="' + user.name + '" name="' + user.name + '" title="' + user.name + '" />';
		}
		html += '<span class="mwchat-item-message">';
		html += message;
		html += '</span>';
		html += MediaWikiChat.htmlTimestamp( timestamp );
		html += '</div>';

		$( '#' + convwithE + ' .mwchat-useritem-content' ).append( html );

		if ( user.name != mw.config.get( 'wgUserName' ) ) {
			$( '#' + convwithE ).attr( 'data-read', 'true' );
		}

		MediaWikiChat.flashPrivate();
	},

	greyscale: function( element, microseconds ) {
		element = element.children( '.mwchat-useritem-header' );

		var hours = microseconds / 360000.0; // 360000 = 1 hour.
		var tooltip = '';

		if ( hours > 1 ) {
			if ( hours > 24 ) {
				tooltip = mw.message( 'chat-idle-more' ).text();
			} else {
				tooltip = mw.message( 'chat-idle-hours', Math.round( hours ) ).text();
			}
			hours = 1;
		} else {
			var minutes = microseconds / 6000;
			if ( minutes > 10 ) {
				tooltip = mw.message( 'chat-idle-minutes', Math.round( minutes ) ).text();
			} else {
				tooltip = mw.message( 'chat-private-message' ).text();
			}
		}

		$( element ).attr( 'title', tooltip );

		// Make it so anything under 10 mins will give 0, and then the colouring happens between 10 and 60 mins
		hours = ( hours * 1.1 ) - 0.1;
		if ( hours < 0 ) {
			hours = 0;
		}

		var percent = Math.round( hours * 100 );

		$( element ).children( 'img' ).css( {
			'-webkit-filter': 'grayscale(' + hours + ')', /* old webkit */
			'-webkit-filter': 'grayscale(' + percent + '%)', /* new webkit */
			'-moz-filter': 'grayscale(' + percent + '%)', /* mozilla */
			'-ms-filter': 'progid:DXImageTransform.Microsoft.BasicImage(grayscale=' + hours + ')', /* maybe ie */
			'filter': 'progid:DXImageTransform.Microsoft.BasicImage(grayscale=' + hours + ')', /* maybe ie */
			'filter': 'grayscale(' + percent + '%)' /* future */
		} );

		var b = Math.round( hours * 10 + 238 ); // 238 > 248 Useritem header background gets lighter
		$( element ).css( 'background-color', 'rgb(' + b + ', ' + b + ', ' + b + ')' );

		var c = Math.round( hours * 85 ); // 0 > 85 User name text gets lighter
		$( element ).children( 'span' ).css( 'color', 'rgb(' + c + ', ' + c + ', ' + c + ')' );
	},

	doUsers: function( newusers ) {
		var allusers = MediaWikiChat.users.concat( newusers );
		allusers = MediaWikiChat.unique( allusers );

		allusers.forEach( function( userId ) {
			if ( newusers.indexOf( userId ) == -1 ) {
				MediaWikiChat.removeUser( userId );
			} else if ( newusers.indexOf( userId ) != -1 && MediaWikiChat.users.indexOf( userId ) == -1 ) {
				MediaWikiChat.addUser(
					userId,
					MediaWikiChat.firstTime
				);
			}
		});

		if ( allusers.length ){
			$( "#mwchat-no-other-users" ).hide();
		} else {
			$( "#mwchat-no-other-users" ).show();
		}

		MediaWikiChat.users = newusers;
		MediaWikiChat.firstTime = false;
	},

	addUser: function( userId, firstTime ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		var html = '<div class="mwchat-useritem noshow" data-unread="" data-name="' + user.name + '" data-id="' + userId + '" id="' + userE + '">';
		html += '<div class="mwchat-useritem-header">';

		if ( mw.config.get( 'wgChatSocialAvatars' ) ) {
			html += '<img src="' + user.avatar + '" />';
		}
		html += '<span class="mwchat-useritem-user">';
		html += user.name;
		html += '</span>';
		if ( user.mod ) {
			html += '<img src="' + mw.message( 'chat-mod-image').escaped() + '" height="16px" alt="" title="';
			html += mw.message( 'chat-user-is-moderator' ).text() + '" />';
		}
		html += '</div><span class="mwchat-useritem-header-links">';

		if ( MediaWikiChat.amIMod && ( !user.mod ) ) {
			html += '<a class="mwchat-useritem-blocklink" href="' + mw.config.get( 'wgArticlePath' ).replace( '$1', 'Special:UserRights/' + user.name );
			html += '" target="_blank">' + mw.message( 'chat-block' ).text() + '</a>';

			if ( mw.config.get( 'wgChatKicks' ) ) {
				html += '&ensp;<a class="mwchat-useritem-kicklink" href="javascript:;">';
				html += mw.message( 'chat-kick' ).text() + '</a>';
			}
		}

		html += '</span>';
		html += '<div class="mwchat-useritem-window" style="display:none;">';
		html += '<div class="mwchat-useritem-content"></div>';
		html += '<input type="text" placeholder="' + mw.message( 'chat-type-your-private-message' ).text() + '" />';
		html += '</div>';
		html += '</div>';

		$( '#mwchat-users' ).append( html );
		$( '#mwchat-users #' + userE ).fadeIn();

		if ( !firstTime ) {
			MediaWikiChat.addSystemMessage( mw.message( 'chat-joined', user.name, user.gender ).text(), MediaWikiChat.now() );
			MediaWikiChat.scrollToBottom();
		}

		// Setup user actions
		$( '#mwchat-users #' + userE + ' .mwchat-useritem-header' ).click( MediaWikiChat.clickUser );

		$( '#mwchat-users #' + userE ).click( function() {
			$( this ).attr( 'data-read', '' );
		} );

		$( '#mwchat-users #' + userE + ' input' ).keypress( MediaWikiChat.userKeypress );

		$( '#mwchat-users #' + userE + ' .mwchat-useritem-kicklink' ).click( function() {
			var parent = $( this ).parent().parent();

			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatkick', 'id': parent.attr( 'data-id' ), 'format': 'json' }
			} ).done( function() {
				MediaWikiChat.getNew();
			} );
		} );

		MediaWikiChat.flashJoinLeave();
	},

	removeUser: function( userId ) {
		var user = MediaWikiChat.userData[userId];
		var userE = MediaWikiChat.safe( user.name );

		$( '#mwchat-users #' + userE ).remove();

		MediaWikiChat.addSystemMessage( mw.message( 'chat-left', user.name, user.gender ).text(), MediaWikiChat.now() );
		MediaWikiChat.scrollToBottom();
		MediaWikiChat.flashJoinLeave();
	},

	clickUser: function() {
		var parent = $( this ).parent();

		parent.children( '.mwchat-useritem-window' ).slideToggle();

		if ( parent.hasClass( 'noshow' ) ) {
			$( '.mwchat-useritem.show .mwchat-useritem-window' ).slideUp();
			$( '.mwchat-useritem.show' ).toggleClass( 'show noshow' );

			parent.toggleClass( 'show noshow' );
		}
		$( '.mwchat-useritem.show' ).toggleClass( 'show noshow' );
	},

	userKeypress: function( e ) {
		$( this ).parents( '.mwchat-useritem' ).attr( 'data-read', '' );

		var toid = $( this ).parents( '.mwchat-useritem' ).attr( 'data-id' );

		if ( e.which == 13 ) {

			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsendpm', 'message': $( this )[0].value, 'id': toid, 'format': 'json' }
			} ).done( function() {
				MediaWikiChat.getNew();
				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
			} );

			$( this ).val( '' );
		}
	},

	addMe: function() {
		if ( !MediaWikiChat.amI ) {

			var me = MediaWikiChat.userData[mw.config.get( 'wgUserId' )];

			$( '#mwchat-me span' ).html( me.name );
			$( '#mwchat-me img' ).attr( 'src', me.avatar );

			if ( me.mod ) {
				$( '#mwchat-me' ).append(
					'<img src="' + mw.message( 'chat-mod-image' ).escaped() + '" height="20px" alt="" title="' +
						mw.message( 'chat-you-are-moderator', mw.user ).text() + '" />'
				);
			}
			MediaWikiChat.amI = true;
		}
	},

	flash: function() {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.config.get( 'wgChatPingMessages' ) ) {
				MediaWikiChat.audio( 'message' );
			}
			document.title = "* " + MediaWikiChat.title;
		}
	},

	flashPrivate: function() {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.config.get( 'wgChatPingPMs' ) ) {
				MediaWikiChat.audio( 'pm' );
			}
			document.title = "> " + MediaWikiChat.title;
		}
	},

	flashMention: function() {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.config.get( 'wgChatPingMentions' ) ) {
				MediaWikiChat.audio( 'mention' );
			} else if ( mw.config.get( 'wgChatPingMessages' ) ) { // user may want pinging on all msgs, but not mentions
				MediaWikiChat.audio( 'message' );
			}
			document.title = "! " + MediaWikiChat.title;
		}
	},

	flashJoinLeave: function() {
		if ( !MediaWikiChat.focussed ) {
			if ( mw.config.get( 'wgChatPingJoinLeaves' ) ) {
				MediaWikiChat.audio( 'message' );
			}
			document.title = "+ " + MediaWikiChat.title;
		}
	},

	clearMentions: function() {
		$( '.mwchat-item-message[data-read="true"]' ).attr( 'data-read', '' );
	},

	audio: function( filename ) {
		var audio = document.createElement( 'audio' );
		var path = mw.config.get( 'wgScriptPath') + '/extensions/MediaWikiChat/assets/' + filename;

		var source = document.createElement( 'source' );
		source.type = 'audio/ogg';
		source.src = path + '.ogg';
		audio.appendChild( source );

		source = document.createElement( 'source' );
		source.type = 'audio/mpeg';
		source.src = path + '.mp3';
		audio.appendChild( source );

		audio.play();
	},
};

$( document ).ready( function() {
	$( $( '#mwchat-type input' )[0] ).keydown( function( e ) { // Send text
		MediaWikiChat.clearMentions();

		var message = $( '#mwchat-type input' )[0].value;

		if ( e.which == 13 && e.shiftKey ) {
			return false;
		} else if ( e.which == 13 ) { // Enter
			if ( message.length > mw.config.get( 'wgChatMaxMessageLength' ) ) {
				alert( mw.message( 'chat-too-long' ).text() );
			} else {
				$( '#mwchat-type input' ).val( '' );
			}

			$.ajax( {
				type: 'POST',
				url: mw.config.get( 'wgScriptPath' ) + '/api.php',
				data: { 'action': 'chatsend', 'message': message, 'format': 'json' }
			} ).done( function( msg ) {
				MediaWikiChat.getNew();
				window.clearInterval( MediaWikiChat.newInterval );
				MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );

				// Error: flood
				if ( msg.chatsend.error == 'flood' ) {
					$( '#mwchat-type input' ).val( message );
					alert( mw.message( 'chat-flood' ).text() );
				}

			} );

		} else if ( e.which == 9 ) { // Tab - autocompletion
			for ( var userId in MediaWikiChat.userData ) {
				if ( userId != mw.config.get( 'wgUserId' ) ) {
					if ( MediaWikiChat.userData[userId].name.toLowerCase().indexOf( $( '#mwchat-type input' )[0].value.toLowerCase() ) === 0 ) {
						$( '#mwchat-type input' )[0].value = MediaWikiChat.userData[userId].name + ': ';
						return false;
					}
				}
			}
			return false;
		}
	} );

	MediaWikiChat.getNew();

	setTimeout( MediaWikiChat.getNew, 2500 );

	MediaWikiChat.newInterval = setInterval( MediaWikiChat.getNew, MediaWikiChat.interval );
	MediaWikiChat.redoInterval = setInterval( MediaWikiChat.redoTimestamps, MediaWikiChat.interval / 2 );

	$( '#mwchat-content' ).click( MediaWikiChat.clearMentions );

	$( '#mwchat-container' ).mouseup( function() { // resize user area on content resize
		var height = $( '#mwchat-content' ).height();
		$( '#mwchat-users' ).animate( { 'height': height }, 'fast' );
		$( '#mwchat-me' ).animate( { 'top': height }, 'fast' );
	} );
} );

$( window ).blur( function() {
	MediaWikiChat.focussed = false;
} );

$( window ).focus( function() {
	MediaWikiChat.focussed = true;
	document.title = MediaWikiChat.title; // restore title
} );
