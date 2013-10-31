<?php

add_action( 'admin_init', 'schat_settings' );

# ADD MENU SETTINGS ITEM
add_action('admin_menu', 'schat_settings_menu');
function schat_settings_menu() {
	add_options_page('Simple Chat', 'Simple Chat', 'manage_options', 'simple-chat', 'schat_admin_page');
}

# TEMPLATE PAGE FUNCTION
function schat_admin_page() {
	include SIMPLE_CHAT_PLUGIN_DIR. 'admin-template.php';
}

function schat_settings() {
	
	// color pallet
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
    
    // save fields
	register_setting( 'simple-chat', 'schat_color' );
	register_setting( 'simple-chat', 'schat_theme' );
	register_setting( 'simple-chat', 'schat_notification' );
	
}
