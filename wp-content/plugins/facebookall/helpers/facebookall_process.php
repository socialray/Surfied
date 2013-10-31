<?php
function facebookall_make_userlogin() {
  $fball_settings = get_option('fball_settings');
  if (isset($_GET['code']) AND !empty($_GET['code'])) {
    $code = $_GET['code'];
    parse_str(facebookall_get_fb_contents("https://graph.facebook.com/oauth/access_token?" . 'client_id=' . $fball_settings ['apikey'] . '&redirect_uri=' . urlencode(site_url()) .'&client_secret=' .  $fball_settings ['apisecret'] . '&code=' . urlencode($code)));
	if(empty($access_token)) {
	  parse_str(facebookall_get_fb_contents("https://graph.facebook.com/oauth/access_token?" . 'client_id=' . $fball_settings ['apikey'] . '&redirect_uri=' . urlencode(site_url().'/') .'&client_secret=' .  $fball_settings ['apisecret'] . '&code=' . urlencode($code)));
    }
	?>
	<script>
         window.opener.FbAll.parentRedirect({'action' : 'fball', 'fball_access_token' : '<?php echo $access_token?>'});
         window.close();
		 </script>
  <?php }
  if(!empty($_REQUEST['fball_access_token']) AND isset($_REQUEST['fball_redirect'])) {
    $fbuser_info = json_decode(facebookall_get_fb_contents("https://graph.facebook.com/me?access_token=".$_REQUEST['fball_access_token']));
    $fbdata = facebookall_get_fbuserprofile_data($fbuser_info);
    if (!empty($fbdata['email']) AND !empty($fbdata['id'])) {
      // Filter username form data.
      if(!empty($fbdata['name'])) {
        $username = $fbdata['name'];
      }
      else if (!empty($fbdata['first_name']) && !empty($fbdata['last_name'])) {
        $username = $fbdata['first_name'].$fbdata['last_name'];
      }
      else {
		$user_emailname = explode('@', $fbdata['email']);
        $username = $user_emailname[0];
      }
	  $user_login = sanitize_user($username, true);
	  $new_user = false;
      $user_id = facebookall_get_userid($fbdata['id']);
      if (!is_numeric ($user_id) AND empty($user_id)) {
        if (($user_id_tmp = email_exists ($fbdata['email'])) !== false) {
          $user_data = get_userdata ($user_id_tmp);
          if ($user_data !== false) {
            $user_id = $user_data->ID;
            $user_login = $user_data->user_login;
            if (!isset ($fball_settings ['linkaccount']) OR $fball_settings ['linkaccount'] == 'link') {
              delete_metadata ('user', null, 'facebookall_user_id', $fbdata['id'], true);
              update_user_meta ($user_id, 'facebookall_user_id', $fbdata['id']);
              update_user_meta ($user_id, 'facebookall_user_email', $fbdata['email']);
              if (!empty ($fbdata['thumbnail'])) {
                update_user_meta ($user_id, 'facebookall_user_thumbnail', $fbdata['thumbnail']);
              }
              wp_cache_delete ($user_id, 'users');
              wp_cache_delete ($user_login, 'userlogins');
            }
          }
        }
        else {
		  $new_user = true;
          $user_login = facebookall_usernameexists($user_login);
          $user_password = wp_generate_password ();
		  $user_role = get_option('default_role');
          $user_data = array (
							'user_login' => $user_login,
							'display_name' => (!empty ($fbdata['name']) ? $fbdata['name'] : $user_login),
							'user_email' => $fbdata['email'],
							'first_name' => $fbdata['first_name'],
							'last_name' => $fbdata['last_name'],
							'user_url' => $fbdata['website'],
							'user_pass' => $user_password,
							'description' => $fbdata['aboutme'],
			                'role' => $user_role
						);
           $user_id = wp_insert_user ($user_data);
           if (is_numeric ($user_id)) {
             delete_metadata ('user', null, 'facebookall_user_id', $fbdata['id'], true);
             update_user_meta ($user_id, 'facebookall_user_id', $fbdata['id']);
             update_user_meta ($user_id, 'facebookall_user_email', $fbdata['email']);
             if (!empty ($fbdata['thumbnail'])) {
               update_user_meta ($user_id, 'facebookall_user_thumbnail', $fbdata['thumbnail']);
             }
             wp_cache_delete ($user_id, 'users');
             wp_cache_delete ($user_login, 'userlogins');
             do_action ('user_register', $user_id);
           }
         }
       }
       $user_data = get_userdata ($user_id);
       if ($user_data !== false) {
	     facebookall_post_user_wall($_REQUEST['fball_access_token'], $fbdata['id'], $new_user);
         wp_clear_auth_cookie ();
         wp_set_auth_cookie ($user_data->ID, true);
         do_action ('wp_login', $user_data->user_login, $user_data);
		 // Redirect user.
         if (!empty ($_GET['redirect_to'])) {
           $redirect_to = $_GET['redirect_to'];
           wp_safe_redirect ($redirect_to);
         }
         else {
           $redirect_to = facebookall_redirect_loggedin_user();
           wp_redirect ($redirect_to);
         }
         exit();
       }
     }
   }
 }

/**
 * Function that getting api settings.
 */
  function facebookall_get_fb_contents($url) {
    $fball_settings = get_option('fball_settings');
    if ($fball_settings['connection_handler'] == 'curl') {
      $curl = curl_init();
	  curl_setopt( $curl, CURLOPT_URL, $url );
	  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
      $response = curl_exec( $curl );
      curl_close( $curl );
      return $response;
    }
	else {
	   $response = @file_get_contents($url);
	   return $response;
	}
  }

/*
 * Function that checking username exist then adding index to it.
 */
  function facebookall_usernameexists($username) {
    $nameexists = true;
    $index = 0;
    $userName = $username;
    while($nameexists == true){
      if (username_exists($userName) != 0) {
        $index++;
        $userName = $username.$index;
      }
      else {
        $nameexists = false;
      }
    }
	return $userName;
  }

/*
 * Function getting user data from facebook.
 */
  function facebookall_get_fbuserprofile_data($fbuser_info) {
     $fbdata['id'] = (!empty($fbuser_info->id) ? $fbuser_info->id : '');
	 $fbdata['first_name'] = (!empty($fbuser_info->first_name) ? $fbuser_info->first_name : '');
     $fbdata['last_name'] = (!empty($fbuser_info->last_name) ? $fbuser_info->last_name : '');
	 $fbdata['name'] = (!empty($fbuser_info->name) ? $fbuser_info->name : '');
	 $fbdata['email'] = (!empty($fbuser_info->email) ? $fbuser_info->email : '');
     $fbdata['thumbnail'] = "https://graph.facebook.com/" . $fbdata['id'] . "/picture";
     $fbdata['aboutme'] = (!empty($fbuser_info->bio) ? $fbuser_info->bio : "");
	 $fbdata['website'] = (!empty( $fbuser_info->link) ? $fbuser_info->link : "");
	 return $fbdata;
  }
  
/**
 * Get the userid.
 */
function facebookall_get_userid ($id) {
  global $wpdb;
  $find_id = "SELECT u.ID FROM " . $wpdb->usermeta . " AS um	INNER JOIN " . $wpdb->users . " AS u ON (um.user_id=u.ID)	WHERE um.meta_key = 'facebookall_user_id' AND um.meta_value=%s";
  return $wpdb->get_var ($wpdb->prepare ($find_id, $id));
}

/**
 * Redirect user after login.
 */
function facebookall_redirect_loggedin_user() {
  $fball_settings = get_option('fball_settings');
  switch ($fball_settings['redirect']) {
    case 'current':
      $redirect_to = facebookall_get_current_url();
	  if($redirect_to == wp_login_url() OR $redirect_to == site_url().'/wp-login.php?action=register' OR $redirect_to == site_url().'/wp-login.php?loggedout=true'){ 
		$redirect_to = home_url();
	  }
      break;
    case 'home':
      $redirect_to = home_url();
      break;
    case 'account':
      $redirect_to = admin_url();
      break;
    case 'custom':
	  if (isset ($fball_settings['custom_url']) AND strlen (trim ($fball_settings ['custom_url'])) > 0) {        
        $redirect_to = trim ($fball_settings ['custom_url']);
      }
      break;
  }
  return $redirect_to;
}
