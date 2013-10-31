<?php
/*
Plugin Name: Facebook All
Plugin URI: http://www.sourceaddons.com/
Description: Allow your visitors to <strong>comment, login, register and share with facebook </strong> also post on their facebook wall.
Version: 1.2
Author: sourceaddons
Author URI: http://www.sourceaddons.com/
License: GPL2
*/

define ('FACEBOOKALL_PLUGIN_URL', plugins_url () . '/' . basename (dirname (__FILE__)));
require_once(dirname (__FILE__) . '/facebookall_admin.php');
require_once(dirname (__FILE__) . '/helpers/facebookall_view.php');
require_once(dirname (__FILE__) . '/helpers/facebookall_widgets.php');
require_once(dirname (__FILE__) . '/helpers/facebookall_process.php');
require_once(dirname (__FILE__) . '/helpers/facebookall_toolbox.php');
require_once(dirname (__FILE__) . '/helpers/facebookall_share.php');

/**
 * Add js to front side.
 */
function facebookall_front_scripts() {
  $fball_settings = get_option('fball_settings');
  if ($fball_settings['share_pin'] == '1') {
    wp_register_script('pinjs', 'http://assets.pinterest.com/js/pinit.js', false, '1.4.2');
    wp_enqueue_script('pinjs');
  }
  if ($fball_settings['share_linkedin'] == '1') {
    wp_register_script('linkedinjs', 'http://platform.linkedin.com/in.js', false, '1.4.2');
    wp_enqueue_script('linkedinjs');
  }
  if ($fball_settings['share_twitter'] == '1') {
    wp_register_script('twitterjs', 'http://platform.twitter.com/widgets.js', false, '1.4.2');
    wp_enqueue_script('twitterjs');
  }
  if ($fball_settings['share_gplus'] == '1') {
    wp_register_script('gplusjs', 'https://apis.google.com/js/plusone.js', false, '1.4.2');
    wp_enqueue_script('gplusjs');
  }
  if( !wp_script_is( 'connect_js', 'registered' ) ) {
    wp_register_script('connect_js', plugins_url('assets/js/fball_connect.js', __FILE__), false, '1.0.0');
  }
  wp_print_scripts( "connect_js" );
  //wp_enqueue_script('connect_js');
}
add_action ('login_head', 'facebookall_front_scripts');
add_action ('wp_head', 'facebookall_front_scripts');

/**
 * Add front end style.
 */
function facebookall_add_fbbutton_style() {
  wp_register_style('facebookall-button-style', plugins_url('assets/css/fball_fbbutton.css', __FILE__));
  wp_enqueue_style( 'facebookall-button-style' );
}
add_action( 'wp_enqueue_scripts', 'facebookall_add_fbbutton_style' );
add_action ('login_head', 'facebookall_add_fbbutton_style');
/**
 * Add administration area links
 **/
function facebookall_admin_menu () {
  $page = add_menu_page ('Facebook All ', 'Facebook All', 'manage_options', 'facebookall', 'facebookall_admin_settings');
  add_action('admin_print_scripts-' . $page, 'facebookall_options_page_scripts');
  add_action('admin_print_styles-' . $page, 'facebookall_options_page_style');
}
add_action ('admin_menu', 'facebookall_admin_menu');


/**
 * Set default settings on plugin activation.
 */
function facebookall_default_options() {
   global $fball_settings;
    add_option( 'fball_settings',
	    array( 'login_title' => 'Or',
		       'fbicon_text' => 'Login with Facebook',
		       'loginpage' => '1',
               'registerpage' => '1',
               'commentpage' => '1',
               'fanbox_pageurl' => 'http://www.facebook.com/pages/Source-addons/162763307197548',
               'fanbox_width' => '200',
               'fanbox_height' => '200',
               'facepile_pageurl' => 'http://www.facebook.com/pages/Source-addons/162763307197548',
               'facepile_width' => '200',
               'facepile_numrows' => '2',
        ));
}
register_activation_hook(__FILE__, 'facebookall_default_options');

/**
 * Initialise
 */
add_action ('init', 'facebookall_make_userlogin', 9);
