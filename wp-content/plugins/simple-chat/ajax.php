<?php

//send updated online users list
add_action("wp_ajax_update_online_users_list", "schat_get_online_users_list");

//send the updated online users count
//add_action("wp_ajax_get_online_users_count", "schat_get_online_users_count");

//process request for new channel
add_action("wp_ajax_request_channel", "schat_request_channel");

//save messages
add_action("wp_ajax_save_chat_msg", "schat_save_messages");


//get updates for user
add_action("wp_ajax_chat_check_updates", "schat_get_updates_for_user");

//close channel for user
add_action("wp_ajax_close_channel", "schat_close_chat_channel");


//return channel_id whether existing open channel or create a new one
function schat_request_channel(){
	
	global $wpdb;
    
    $initiator = get_current_user_ID(); //the person who request a new chat
    $requested = $_POST['user_id']; //the user id of the person being chatting
	
	if( !$initiator or !$requested )
		return;
	
	$query = $wpdb->prepare("
		SELECT i.channel_id FROM {$wpdb->channel_users} i, {$wpdb->channel_users} o
		WHERE
			i.channel_id=o.channel_id
			AND i.user_id IN (%d,%d)
			AND o.user_id IN (%d,%d)
			AND i.user_id <> o.user_id
			ORDER BY channel_id
		", $initiator, $requested, $initiator, $requested );
			
    $channel_id = $wpdb->get_var($query);

	
	if( !$channel_id ) {
		global $wpdb,$bp;
		
		// create chat channel
		$wpdb->query("INSERT into {$wpdb->chat_channels} (is_multichat) values(0)");
		
		// get channel id
		$channel_id = $wpdb->insert_id;
		
		$query = "INSERT into {$wpdb->channel_users} (channel_id, user_id, status) values(%d,%d,%s)";
		
		// create channel users
		$wpdb->query( $wpdb->prepare( $query, $channel_id, $initiator, 'open' ) );
		$wpdb->query( $wpdb->prepare( $query, $channel_id, $requested, 'requested' ) );
		
	} else {
		$query = "UPDATE {$wpdb->channel_users} SET status = 'open' WHERE channel_id=%d AND user_id=%d";
		$wpdb->query( $wpdb->prepare( $query, $channel_id, $initiator ) );
	}
	
	echo $channel_id;
	exit;
}

function schat_close_chat_channel(){
	global $wpdb;
	
	$user_id = get_current_user_ID();
	$channel_id = (int) $_POST['channel_id'];
	
    $query = $wpdb->prepare( "UPDATE {$wpdb->channel_users} SET status='closed' WHERE channel_id=%d AND user_id=%d", $channel_id, $user_id );
    
    return $wpdb->query($query);
}

/*
 * List online users
 */

function schat_get_all_users() {
	global $wpdb;
	
	$query = $wpdb->prepare("
		SELECT
			users.ID as user_id,
			users.display_name,
			users.user_email,
			online.last_active_time,
			online.is_online
		FROM {$wpdb->users} users
		LEFT JOIN {$wpdb->chat_users} online
			ON users.ID = online.user_id
		WHERE users.ID!='%d'
		ORDER BY online.last_active_time, users.display_name
	", get_current_user_ID());
	
	return $wpdb->get_results( $query );
}

function schat_get_user_status( $last_active_time=0, $is_online=1 ) {
	
	if( !$is_online )
		return 'off';
	
	$time = strtotime(CURRENT_MYSQL_TIME);
	
	// check if user is on/off/afk
	if( $time-strtotime($last_active_time)< (60 * SIMPLE_CHAT_AFK_TIME) ) // less then 1 minute afk, the user is online
		return 'on';
		
	elseif( $time-strtotime($last_active_time)< (60 * SIMPLE_CHAT_OFF_TIME) ) // more then 1min afk and less then 5min the user is off
		return 'afk';
		
	else  // more then 5min afk, the user is offline
		return 'off';
}

function schat_get_online_users_list($echo =true) {

	global $wpdb;

	$users = schat_get_all_users(); //$users;
	//$total = schat_get_online_users_count();//total online users
	
	//something to sniff only those who are allowed to chat
	$my_id = get_current_user_id();
	$html = "";
	
    if(!empty($users))
		foreach ($users as $u) {
			
			$user_status = schat_get_user_status( $u->last_active_time, $u->is_online );
			
			$html.="<div class='friend_list_item'>";
			$html.='<a class="online_friend '.$user_status.'" id="chat_with_' . $u->user_id . '">';
			$html.=get_avatar( $u->user_email, 50); #, 'height' => 32, 'class' => 'friend-avatar'));
			//??? $html.="<span class='disabled friend_list_item_orig_avatar_src'>" . get_avatar( $u->user_email, 50 ) . "</span>";
			$html.='<span class="friend_list_item_name">' . $u->display_name . '</span>';
			#$html.=bp_core_fetch_avatar(array('item_id' => $u->user_id, 'type' => 'thumb', 'width' => 32, 'height' => 32, 'class' => 'friend-avatar'));
			#$html.="<span class='disabled friend_list_item_orig_avatar_src'>" . bp_core_fetch_avatar(array('item_id' => $u->user_id, 'type' => 'thumb', 'width' => 50, 'height' => 50, 'html' => false)) . "</span>";
			#$html.='<span class="friend_list_item_name">' . schat_get_user_displayname($u->user_id) . '</span>';
			$html.="<span class='clear'></span>";
			$html.="</a><div class='clear'></div></div>";
		}
	
	echo $html;

	if( is_admin () )
        die();
}


/**
 * Create a new channel for the user  if a channel is not allocated or if already a channel exists, jsut return the channel id
 */

/* save chat message to database */
function schat_save_messages() {
    global $wpdb;
    
    $channel_id = $_POST["channel_id"];
    $user_id = get_current_user_ID();
    
    
    // save message
    $new_message_id = schat_save_message( $_POST["channel_id"], trim($_POST["message"]) );
	
	//echo '->'.$new_message->channel_id;
	
	// update users status
	$query = $wpdb->prepare("UPDATE {$wpdb->channel_users} SET status = 'requested' WHERE channel_id=%d AND user_id<>%d", $channel_id, $user_id);
	$wpdb->query( $query );
	
	echo json_encode(array("name"=>schat_get_user_displayname( get_current_user_ID() ),"id"=>$new_message_id));
    if(is_admin ())
        die();
}


/** check for the new chat requests, list which which we are chatting currently or the messages we have recieved for the user*/
function schat_get_updates_for_user() {
    
    global $wpdb;
    
    $user_id = get_current_user_id();
    @$last_fetch_time = $_POST["fetch_time"];

	// infinte scroll
    @$limit_id = intval($_POST["last_message_id"])? "AND msg.id<".intval($_POST["last_message_id"]) : '';
    @$channel_where = intval($_POST["channel_id"])? "AND channels.channel_id = ".intval($_POST["channel_id"]) : '';
    
	$limit_time = ($last_fetch_time)? "and msg.sent_at >= '".$last_fetch_time."'" : '';
	
	// ???
    //if( $last_id && $channel_id )
		//$limit_time = "and msg.sent_at >= '".$last_fetch_time."'";
	
	// Get channels to popup/update (opened minimized windows and new messages)
	$query = $wpdb->prepare("
		SELECT channels.channel_id, channels.status, channels.user_id, user.last_active_time, user.is_online
		FROM {$wpdb->channel_users} channels
		INNER JOIN {$wpdb->channel_users} channel_status
			ON channel_status.channel_id=channels.channel_id AND channel_status.user_id = %d
		LEFT JOIN {$wpdb->chat_users} user
			ON channels.user_id = user.user_id
		WHERE
			channels.user_id != %d
			 AND channel_status.status != 'closed'
			 {$channel_where}
		LIMIT 5
		", $user_id, $user_id );

	//echo $query;
	
	$channels = $wpdb->get_results($query); //array of message objects

	//print_r($channels);
	
	foreach( $channels as $key=>$channel ) {
		$query = $wpdb->prepare("
			SELECT msg.id,msg.channel_id, msg.message, msg.sender_id, users.display_name as name, msg.message, msg.sent_at
			FROM {$wpdb->chat_messages} msg
			INNER JOIN {$wpdb->users} users
				ON users.ID = msg.sender_id
			WHERE
				msg.channel_id = %d
				{$limit_time}
				{$limit_id}
			ORDER BY msg.sent_at DESC
			LIMIT 5
			", $channel->channel_id );

		//echo $query;
		
		$channels[$key]->messages = $wpdb->get_results($query); //array of message objects
		
		// reverse order if not infinity scroll
		if( empty($channel_where) )
			$channels[$key]->messages = array_reverse($channels[$key]->messages);
		
		// data for popup window settings
		$userdata = get_userdata($channel->user_id);
		$channels[$key]->userdata = array(
			'id'		=> $channel->user_id,
			'name'		=> $userdata->display_name,
			'thumbnail' => get_gravatar_url( $userdata->user_email ),
			'status' 	=> schat_get_user_status( $channel->last_active_time, $channel->is_online ),
		);
		
		unset( $channels[$key]->is_online, $channels[$key]->last_active_time  );
	}
	
	// no messages; exit and send users online counter
	if( !$channels ) {
		echo json_encode(array('users_online'=>schat_get_online_users_count()));
		exit;
	}
	
	// no send the same messages
	$channels['0']->fetch_time = CURRENT_MYSQL_TIME;

	// users online counter
	$channels['0']->users_online = schat_get_online_users_count();

    echo json_encode($channels);
    
    if(is_admin ())
        die();

}

?>
