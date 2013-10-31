<?php
/**
Plugin Name: Instagram Picture
Plugin URI: http://tb-webtec.de/instagram_picture/
Description: Your Instagram photos as a PHP-Code, Widget or Shortcode
Version: 2.0.2
Author: Tobias Bohn
Author URI: https://twitter.com/tobias_bohn
Requires at least: 3.0
Tested up to: 3.0
License: GPLv2 or later
*/



########################################################################################################################
/* 
*	variable definition
*/
	// create array
	$instagram_picture_variable = array();
	
	// class datebase
	global $wpdb;

	// infos
	$instagram_picture_variable["0"]  = "2.0.2"; 																// version
	$instagram_picture_variable["5"]  = "25"; 																// optimized header angular (end of style-id)
	$instagram_picture_variable["6"]  = "122";																// optimized header angular (round) (end of style-id)
	$instagram_picture_variable["7"]  = "226";																// optimized widget angular (end of style-id)
	
	// links
	$instagram_picture_variable["10"] = dirname(__FILE__); 												// File
	$instagram_picture_variable["11"] = plugins_url()."/instagram-picture/";						// URL
	
	// database tables
	$instagram_picture_variable["100"] = $wpdb->prefix . "instagram_info";
	$instagram_picture_variable["101"] = $wpdb->prefix . "instagram_bilder";
	$instagram_picture_variable["102"] = $wpdb->prefix . "instagram_user_info";
	
	// array in global $instagram_picture_variable
	global $instagram_picture_variable;
########################################################################################################################


########################################################################################################################
/*
*	Version check
*/
	// used version
	$used_version = $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='7'");
		
	// version used in file
	$file_version = $instagram_picture_variable["0"];
		
	// Check whether a version exists in the table
	// This is prior to version 2.0
	if(empty($used_version))
	{
		// And from version 2.0 there are updates to the database.
			
			// database instagram_info
			$table_name = $wpdb->prefix . "instagram_info";
				// Version in database record
				$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('7', '$file_version')"); // Version
				
			// databse instagram_user_info
			$table_name = $wpdb->prefix . "instagram_user_info";
  				// creating a table
   			$sql = "CREATE TABLE $table_name (
 				id int(1) NOT NULL,
  				username char(255) NOT NULL,
				full_name char(255) NOT NULL,
				media int(11) NOT NULL,
				followed int(11) NOT NULL,
				follows int(11) NOT NULL,
				profil_picture text NOT NULL,
  				UNIQUE KEY id (id)
				);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
	
				// predefined entries
				$wpdb->query("INSERT INTO $table_name (id) VALUES ('1')"); // ID setzung
				
			// table name instagram-bilder
			$table_name = $wpdb->prefix . "instagram_bilder";
   
   			// status add!
   			$sql = "CREATE TABLE $table_name (
  				id bigint NOT NULL,
  				status int(1) NOT NULL,
  				link text NOT NULL,
  				text text NOT NULL,
  				thumbnail text NOT NULL, 
  				low_resolution text NOT NULL, 
  				standard_resolution text NOT NULL, 
  				pic_like int(11) NOT NULL, 
  				pic_comment int(11) NOT NULL, 
  				UNIQUE KEY id (id)
				);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
	}

########################################################################################################################



########################################################################################################################
/*
*	activation
*/
	if ( function_exists('register_activation_hook') )
		register_activation_hook(__FILE__, 'instagram_picture_activate');
	
	function instagram_picture_activate() 
	{
		
		// database connection
		global $wpdb;
		
		// table name
		$table_name = $wpdb->prefix . "instagram_info";
	   
   	// creating a table
   	$sql = "CREATE TABLE $table_name (
  			id int(2) NOT NULL AUTO_INCREMENT,
  			text text NOT NULL,
  			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		// predefined entries
		$wpdb->query("INSERT INTO $table_name (id) VALUES ('1')"); // Instagram-ID
		$wpdb->query("INSERT INTO $table_name (id) VALUES ('2')");	 // Access-Token
		$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('3', '01')"); // Style (for PHP-Code)
		$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('4', '0')"); // linkable or lightbox (for PHP-Code)
		$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('5', '0')"); // Image with title (for PHP-Code)
		$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('6', '0')"); // Border-Radius (for PHP-Code)
		$wpdb->query("INSERT INTO $table_name (id, text) VALUES ('7', '2.0')"); // Version
	
		// table name
		$table_name = $wpdb->prefix . "instagram_bilder";
   
   	// creating a table
   	$sql = "CREATE TABLE $table_name (
  			id bigint NOT NULL,
  			status int(1) NOT NULL,
  			link text NOT NULL,
  			text text NOT NULL,
  			thumbnail text NOT NULL, 
  			low_resolution text NOT NULL, 
  			standard_resolution text NOT NULL, 
  			pic_like int(11) NOT NULL, 
  			pic_comment int(11) NOT NULL, 
  			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
	
	
		// table name
		$table_name = $wpdb->prefix . "instagram_user_info";
   
  		// creating a table
   	$sql = "CREATE TABLE $table_name (
 			id int(1) NOT NULL,
  			username char(255) NOT NULL,
			full_name char(255) NOT NULL,
			media int(11) NOT NULL,
			followed int(11) NOT NULL,
			follows int(11) NOT NULL,
			profil_picture text NOT NULL,
  			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		// predefined entries
		$wpdb->query("INSERT INTO $table_name (id) VALUES ('1')"); // ID setzung
		
		
		// Mail send to admin
		$body = "Instagram-Picture Account

  		Many thanks that you are using our plugin.
  
  		If the first time you use our plugin, you should read the following tutorial: http://www.inmotionhosting.com/support/website/wordpress-plugins/instagram-picture-plugin-for-wordpress
  
  		Need help or have suggestions, please write us an e-mail to: support@tb-webtec.de
  
  		To stay current: Blog (http://tb-webtec.de/blog/), Facebook (http://www.facebook.com/TbWebtec) or Twitter (http://twitter.com/Tobias_Bohn)

		Sincerely yours
		Tobias Bohn";
  		wp_mail(get_bloginfo('admin_email'), "Thanks for using \"Instagram-Picture\"", $body);
	
	}
########################################################################################################################


########################################################################################################################
/*
* uninstall
*/
	if ( function_exists('register_uninstall_hook') )
		register_uninstall_hook(__FILE__, 'instagram_picture_deinstall');
	
	function instagram_picture_deinstall() 
	{
	
		delete_option('example');
	
		global $wpdb;

   	$table_name_info = $wpdb->prefix . "instagram_info";
   
   	$table_name_bilder = $wpdb->prefix . "instagram_bilder";
   
   	$table_name_user_info = $wpdb->prefix . "instagram_user_info";
   
		$wpdb->query("DROP TABLE ".$table_name_info);

		$wpdb->query("DROP TABLE ".$table_name_bilder);
	
		$wpdb->query("DROP TABLE ".$table_name_user_info);
   
	}
########################################################################################################################



########################################################################################################################
/*
*	Error when add_action not go
*/
	if ( !function_exists( 'add_action' ) ) 
	{
		echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
		exit;
	}
########################################################################################################################





########################################################################################################################
/*
*	Menu add
*/
add_action('admin_menu', 'instagram_picture_menu');
########################################################################################################################



########################################################################################################################
/*
*	Involvement of other files
*/

	define('INSTAGRAM_PICTURE_DIR', dirname(__FILE__));

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/style.php");									//	Integration of Styles

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/menu.php");									//	Integration of the menu

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/instagram.php");							//	Site -> Instagram

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/update.php");								//	Site -> Update

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/configuration.php");						//	Site -> Configuration

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/all_pictures.php");						//	Site -> All Pictures

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/php_code.php");								//	Site -> PHP-Code

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/widget.php");								//	Site -> Widget

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/shortcode.php");							//	Site -> Shortcode


	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_php_code.php");					//	Output of the PHP code

	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_shortcode.php");					//	Output of the Shortcode
	
	// Widget
	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_widget.php");						//	Widget: Instagram Picture
	
	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_widget_individually.php");		//	Widget: Instagram Picture individually
	
	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_widget_info.php");				//	Widget: Instagram Picture with Infos
	
	require_once(INSTAGRAM_PICTURE_DIR . "/inc/output_widget_user_info.php");			//	Widget: Instagram Picture User Infos
########################################################################################################################

?>