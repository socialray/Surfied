<?php
/*
 * Build a login button.
 */
function facebookall_render_facebook_button() {
  $fball_settings = get_option('fball_settings');
  $fball_button = '<div class="fball_ui">
        <div class="fball_form" title="Facebook All">';
		if (!empty($fball_settings ['custom_button'])) {
		  $fball_button .= '<a href="javascript:void(0);" title="Login using Facebook" onclick="FbAll.facebookLogin();" class="fball_login_facebook"><img src="'.$fball_settings ['custom_button'].'" style="cursor:pointer;"></a>';
		}
		else {
		  $fball_button .= '<span id="fball-facebook-login">
        <a href="javascript:void(0);" title="Login using Facebook" onclick="FbAll.facebookLogin();" class="fball_login_facebook"><span>'.$fball_settings ['fbicon_text'].'</span></a></span>';
		}
	$fball_button .= '</div>
        <div id="fball_facebook_auth">
          <input type="hidden" name="client_id" id="client_id" value="'.$fball_settings['apikey'].'"/>
          <input type="hidden" name="redirect_uri" id="redirect_uri" value="'.site_url().'"/>
        </div>
	    <input type="hidden" id="fball_login_form_uri" value=""/>
        </div>';
  echo $fball_button;
}

/*
 * Add login button to widget.
 */
function facebookall_render_widget_button() {
  $fball_settings = get_option('fball_settings');
  if (is_user_logged_in()) {
		global $user_ID;
		$size ='60';
		$user = get_userdata($user_ID);
		echo "<div><div style='float:left;'>";
		if(($fbavatar = get_user_meta($user_ID, 'facebookall_user_thumbnail', true)) !== false && strlen(trim($fbavatar)) > 0){
			echo '<img alt="user facebook avatar" src="'.$fbavatar.'" height = "'.$size.'" width = "'.$size.'" title="'.$user->user_login.'" style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;"/>';
		}else{
			echo @get_avatar($user_ID, $size, $default, $alt);   
		}
		echo "</div><div style='float:left; margin-left:10px;padding:1px;'>"; 
		echo $user->user_login;
		echo '<br/><a href="'.wp_logout_url().'">'; _e('Log Out', 'facebookall');'</a></div></div>'; 
	}
	else {
	  facebookall_render_facebook_button();
	}
}

/*
 * Add login to loginform.
 */
function facebookall_render_facebook_button_loginform() {
  $fball_settings = get_option('fball_settings');
  $login_caption = $fball_settings['login_title'];
  if (isset($fball_settings['loginpage']) AND $fball_settings['loginpage'] == '1') {
    if (!is_user_logged_in ()) {
	  echo '<div style="margin-bottom: 3px;"><b>' . __ ($login_caption) . '</b></div>';
      facebookall_render_facebook_button();
    }
  }
}
add_action('login_form', 'facebookall_render_facebook_button_loginform');
add_action ('bp_after_sidebar_login_form', 'facebookall_render_facebook_button_loginform');

/*
 * Add login to comment form.
 */
function facebookall_render_facebook_button_commentform() {
  $fball_settings = get_option('fball_settings');
  $login_caption = $fball_settings['login_title'];
  if (isset($fball_settings['commentpage']) AND $fball_settings['commentpage'] == '1') {
    if (!is_user_logged_in ()) {
      echo '<div style="margin-bottom: 3px;"><b>' . __ ($login_caption) . '</b></div>';
      facebookall_render_facebook_button();
    }
  }
}
if(get_option('comment_registration') == 1) {
  add_action ('comment_form_must_log_in_after', 'facebookall_render_facebook_button_commentform');
}
else {
  add_action ('comment_form_top', 'facebookall_render_facebook_button_commentform');
}

/*
 * Add login to register form.
 */
function facebookall_render_facebook_button_registerform() {
  $fball_settings = get_option('fball_settings');
  $login_caption = $fball_settings['login_title'];
  if (isset($fball_settings['registerpage']) AND $fball_settings['registerpage'] == '1') {
    if (!is_user_logged_in ()) {
	  echo '<div style="margin-bottom: 3px;"><b>' . __ ($login_caption) . '</b></div>';
      facebookall_render_facebook_button();
    }
  }
}
add_action('register_form', 'facebookall_render_facebook_button_registerform'); 
add_action('after_signup_form','facebookall_render_facebook_button_registerform');
add_action ('bp_before_account_details_fields', 'facebookall_render_facebook_button_registerform');

/*
 * Check https.
 */
function facebookall_is_https_on () {
  if (!empty ($_SERVER ['SERVER_PORT'])) {
    if (trim ($_SERVER ['SERVER_PORT']) == '443') {
      return true;
    }
  }
  if (!empty ($_SERVER ['HTTP_X_FORWARDED_PROTO'])) {
    if (strtolower (trim ($_SERVER ['HTTP_X_FORWARDED_PROTO'])) == 'https') {
      return true;
    }
  }
  if (!empty ($_SERVER ['HTTPS'])) {
    if (strtolower (trim ($_SERVER ['HTTPS'])) == 'on' OR trim ($_SERVER ['HTTPS']) == '1') {
      return true;
    }
  }
  return false;
}

/*
 * getting current url.
 */
function facebookall_get_current_url() {
  $request_uri = ((!isset ($_SERVER ['REQUEST_URI'])) ? $_SERVER ['PHP_SELF'] : $_SERVER ['REQUEST_URI']);
  $request_port = ((!empty ($_SERVER ['SERVER_PORT']) AND $_SERVER ['SERVER_PORT'] <> '80') ? (":" . $_SERVER ['SERVER_PORT']) : '');
  $request_protocol = (facebookall_is_https_on () ? 'https' : 'http') . "://";
  $current_url = $request_protocol . $_SERVER ['SERVER_NAME'] . $request_port . $request_uri;
  return $current_url;
}

/*
 * Create fanbox.
 */
function facebookall_render_fanbox() {
  $fball_settings = get_option('fball_settings');?>
 <iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($fball_settings['fanbox_pageurl']);?>&amp;width=<?php echo $fball_settings['fanbox_width'];?>&amp;height=<?php echo $fball_settings['fanbox_height'];?>&amp;colorscheme=<?php if($fball_settings['fanbox_color'] == '1') { echo 'light';}else {echo 'dark';}?>&amp;show_faces=<?php if($fball_settings['fanbox_faces'] == '0') { echo 'false';}else {echo 'true';}?>&amp;stream=<?php if($fball_settings['fanbox_stram'] == '0') { echo 'false';}else {echo 'true';}?>&amp;header=<?php if($fball_settings['fanbox_header'] == '0') { echo 'false';}else {echo 'true';}?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $fball_settings['fanbox_width'];?>px; height:<?php echo $fball_settings['fanbox_height'];?>px;" allowTransparency="true"></iframe>
<?php }

/*
 * Create facepile.
 */
function facebookall_render_facepile() {
  $fball_settings = get_option('fball_settings');?>
<iframe src="//www.facebook.com/plugins/facepile.php?href=<?php echo urlencode($fball_settings['facepile_pageurl'])?>&amp;size=<?php if($fball_settings['facepile_size'] == '0') { echo 'small';}elseif($fball_settings['facepile_size'] == '1') {echo 'medium';}else {echo 'large';}?>&amp;max_rows=<?php echo $fball_settings['facepile_numrows']?>&amp;width=<?php echo $fball_settings['facepile_width']?>&amp;colorscheme=<?php if($fball_settings['facepile_color'] == '1') { echo 'light';}else {echo 'dark';}?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:<?php echo $fball_settings['facepile_width']?>px;" allowTransparency="true"></iframe>
<?php }

/*
 * Create recommendations bar.
 */
function facebookall_render_recommendbar() {
  $fball_settings = get_option('fball_settings');
  if ($fball_settings['enable_recbar'] == '1') {
  ?>
 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $fball_settings['recbar_appid'];?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-recommendations-bar" data-site="<?php echo $fball_settings['recbar_pageurl'];?>" data-trigger="onvisible" data-read-time="<?php echo $fball_settings['recbar_readtime'];?>" data-action="<?php if($fball_settings['recbar_verb'] == '1') { echo 'like';}else {echo 'recommend';}?>" data-side="<?php if($fball_settings['recbar_side'] == '1') { echo 'left';}else {echo 'right';}?>"></div>
<?php }}
add_action('wp_footer','facebookall_render_recommendbar');