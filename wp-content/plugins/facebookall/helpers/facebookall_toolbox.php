<?php
/*
 * Get the wall post settings.
 */
function facebookall_post_user_wall($access_token, $fbid, $newfbuser) {
  $fball_settings = get_option('fball_settings');
  if ($fball_settings['enablenewpost'] == '1' && $newfbuser == true) {
	$attachment =  array(
          'access_token' => $access_token,
          'message' => $fball_settings['new_post_message'],
          'name' => $fball_settings['new_post_title'],
          'link' => $fball_settings['new_post_url'],
          'description' => $fball_settings['new_post_desc'],
          'picture'=>$fball_settings['new_post_pic']
        );
     facebookall_wallpost_curl ($attachment,$fbid);
  }
  if ($fball_settings['enableoldpost'] == '1' && $newfbuser == false) {
	$attachment =  array(
          'access_token' => $access_token,
          'message' => $fball_settings['old_post_message'],
          'name' => $fball_settings['old_post_title'],
          'link' => $fball_settings['old_post_url'],
          'description' => $fball_settings['old_post_desc'],
          'picture'=>$fball_settings['old_post_pic']
        );
    facebookall_wallpost_curl ($attachment,$fbid);
  }
}

/*
 * Post to user wall.
 */
function facebookall_wallpost_curl ($attachment,$fbid) {
  if (function_exists('curl_init')) {
    $url = "https://graph.facebook.com/".$fbid."/feed";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close ($ch);
  }
}

/*
 * Adding a column in user list.
 */
function facebookall_add_user_column ($columns) {
  $fball_settings = get_option('fball_settings');
  if (!empty($fball_settings ['showcolumn']) AND $fball_settings ['showcolumn'] == 'yesshowcolumn'){
    $columns ['facebookall_user_column'] = __ ('FacebookAll User', 'facebookall');
  }
  return $columns;
}
add_filter ('manage_users_columns', 'facebookall_add_user_column');

/*
 * Show column in user list.
 */
function facebookall_show_user_column($value, $column_name, $user_id){
  $fballuser = get_user_meta($user_id, 'facebookall_user_id', true);
  $fballuser = ($fballuser == false) ? "-" : $fballuser;
  return '<a href ="http://www.facebook.com/profile.php?id='.$fballuser.'" target="_blank">'.$fballuser.'</a>';
}
add_action('manage_users_custom_column', 'facebookall_show_user_column', 10, 3);

/**
 * Replace default avatar with facebook avatar
 */
function facebookall_show_social_avatar($avatar, $avatar_user, $size, $default, $alt = '') {
	$user_id = null;
	if(is_numeric($avatar_user)){
		if($avatar_user > 0){
			$user_id = $avatar_user;
		}
	}
	elseif(is_object($avatar_user)){
		if(property_exists($avatar_user, 'user_id') AND is_numeric($avatar_user->user_id)){
			$user_id = $avatar_user->user_id;
		}
	}
	if(!empty($user_id)){
		if(($useravatar = get_user_meta($user_id, 'facebookall_user_thumbnail', true)) !== false AND strlen(trim($useravatar)) > 0){
			return '<img alt="' . esc_attr($alt) . '" src="' . $useravatar . '" class="avatar avatar-' . $size . ' " height="' . $size . '" width="' . $size . '" />';
		}
	}
	return $avatar;
}
add_filter('get_avatar', 'facebookall_show_social_avatar', 10, 5);

/** 
 * Auto approve comments.
 */ 
function facebookall_auto_approve_comment($approved){
  $fball_settings = get_option('fball_settings');
  if(empty($approved)){
    if($fball_settings['autoapprove'] == '1'){ 
      $user_id = get_current_user_id(); 
      if(is_numeric($user_id)){
        $comment_user = get_user_meta($user_id, 'facebookall_user_id', true); 
        if($comment_user !== false){ 
          $approved = 1; 
        } 
      } 
    } 
  } 
  return $approved; 
}
add_action('pre_comment_approved', 'facebookall_auto_approve_comment');

/*
 * Get avatar on buddypress.
 */
function facebookall_bp_custom_fetch_avatar ($text, $args) {
  $fball_settings = get_option('fball_settings');
  if (is_array ($args)) {
    if (!empty ($args ['object']) AND strtolower ($args ['object']) == 'user') {
      if (!empty ($args ['item_id']) AND is_numeric ($args ['item_id'])) {
        if (($user_data = get_userdata ($args ['item_id'])) !== false) {
          if (($user_thumbnail = get_user_meta ($args ['item_id'], 'facebookall_user_thumbnail', true)) !== false) {
            if (strlen (trim ($user_thumbnail)) > 0) {
              $img_alt = (!empty ($args ['alt']) ? 'alt="' .  $args ['alt'] . '" ' : '');
              $img_alt = sprintf ($img_alt, htmlspecialchars ($user_data->user_login));
              $img_class = ('class="' . (!empty ($args ['class']) ? ($args ['class'] . ' ') : '') . 'avatar-facebookall" ');
              $img_width = (!empty ($args ['width']) ? 'width="' . $args ['width'] . '" ' : '');
              $img_height = (!empty ($args ['height']) ? 'height="' . $args ['height'] . '" ' : '');
              $text = preg_replace ('#<img[^>]+>#i', '<img data-facebookall="bp-d1" src="' . $user_thumbnail . '" ' . $img_alt . $img_class . $img_height . $img_width . '/>', $text);
            }
          }
        }
      }
    }
  }
  return $text;
}
add_filter ('bp_core_fetch_avatar', 'facebookall_bp_custom_fetch_avatar', 10, 2);

/*
 * Make short code for login.
 */
function facebookall_login_shortcode() {
	return (is_user_logged_in () ? '' : facebookall_render_facebook_button());
}
add_shortcode ('facebookall_login', 'facebookall_login_shortcode');

/*
 * Make short code for likebox.
 */
function facebookall_likebox_shortcode() {
	return facebookall_render_fanbox();
}
add_shortcode ('facebookall_fanbox', 'facebookall_likebox_shortcode');

/*
 * Make short code for facepile.
 */
function facebookall_facepile_shortcode() {
	return facebookall_render_facepile();
}
add_shortcode ('facebookall_facepile', 'facebookall_facepile_shortcode');

/*
 * Make short code for share.
 */
function facebookall_share_shortcode() {
	return facebookall_get_socialshre();
}
add_shortcode ('facebookall_share', 'facebookall_share_shortcode');

/*
 * Make short code for comments.
 */
function facebookall_comment_shortcode() {
	return facebookall_get_fb_comments();
}
add_shortcode ('facebookall_comments', 'facebookall_comment_shortcode');

