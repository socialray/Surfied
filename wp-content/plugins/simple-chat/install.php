<?php

function schat_install() {
	
	global $wpdb;
	
	$schat_ver = get_option( 'simple_chat_version', 0 );
	
	// exit
	if( !defined('SIMPLE_CHAT') OR SIMPLE_CHAT < $schat_ver )
		return true;
	
	// setup table names
	$wpdb->chat_users = $wpdb->prefix.SIMPLE_CHAT_DB_CHAT_USERS;
	$wpdb->chat_channels = $wpdb->prefix.SIMPLE_CHAT_DB_CHAT_CHANNELS;
    $wpdb->channel_users = $wpdb->prefix.SIMPLE_CHAT_DB_CHANNEL_USERS;
    $wpdb->chat_messages = $wpdb->prefix.SIMPLE_CHAT_DB_USER_MESSAGES;
	
	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	
	// table for users
	$sql[] = "CREATE TABLE {$wpdb->chat_users} (
		user_id bigint(20) NOT NULL,
		is_online tinyint(4) NOT NULL,
		last_active_time datetime NOT NULL,
		last_fetch_time datetime NOT NULL
		) {$charset_collate};";

	// table for channels
	$sql[] = "CREATE TABLE {$wpdb->chat_channels} (
	  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  last_message_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  is_multichat tinyint(3) NOT NULL,
	  UNIQUE KEY id (id)
	) {$charset_collate};";
	
	// table for user's channel
	$sql[] = "CREATE TABLE {$wpdb->channel_users} (
	channel_id bigint(20) NOT NULL,
		user_id bigint(20) NOT NULL,
		status varchar(32) NOT NULL
	) {$charset_collate};";

	// table for chat messages
	$sql[] = "CREATE TABLE {$wpdb->chat_messages} (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
		  sender_id bigint(20) NOT NULL,
		  channel_id bigint(20) NOT NULL,
		  message text NOT NULL,
		  sent_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  is_read tinyint(3) NOT NULL,
		  UNIQUE KEY id (id)
	) {$charset_collate};";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	return add_option( 'simple_chat_version', SIMPLE_CHAT );
}
