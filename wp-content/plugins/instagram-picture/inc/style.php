<?php

add_action('wp_head', 'instagram_picture_style');

/*
* action admin panel
*/
add_action('admin_head', 'instagram_picture_style');
add_action('admin_head', 'instagram_picture_style_admin');

/*
* Style
*/
function instagram_picture_style() {
	
	########################################################################################################################
	/* 
	*	variable definition
   */
	global $instagram_picture_variable;
	########################################################################################################################
	
	echo '<link rel="stylesheet" id="instagram"  href="'.$instagram_picture_variable["11"].'css/instagram_style.css" type="text/css" media="all" />';
}


/*
* Admin Style
*/
function instagram_picture_style_admin() {
	
	########################################################################################################################
	/* 
	*	variable definition
   */
	global $instagram_picture_variable;
	########################################################################################################################
	
	echo '<link rel="stylesheet" id="instagram"  href="'.$instagram_picture_variable["11"].'css/instagram_style_admin.css" type="text/css" media="all" />';
}
?>