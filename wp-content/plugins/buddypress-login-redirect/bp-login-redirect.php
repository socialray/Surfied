<?php
/*
Plugin Name: BuddyPress login redirect
Description: allows the buddypress site admins to decide where to redirect their users after login
Author: Jatinder Pal Singh
Author URI: http://www.appinstore.com
Plugin URI: http://www.appinstore.com/buddypress-login-redirect/
Version: 2.1
*/
function buddypress_login_redirection($redirect_url,$request_url,$user)
{
	global $bp;
	$selected_option = get_option('blr_select_redirection');
	if($selected_option == 'one')
	{
		return bp_core_get_user_domain($user->ID);
	}
	elseif($selected_option=='two')
	{
		$activity_slug = bp_get_activity_root_slug();
		$redirect_url = $bp->root_domain."/".$activity_slug;
		return $redirect_url;
	}
	elseif($selected_option=='four')
	{
		//$activity_slug = bp_get_activity_root_slug();
		//$redirect_url = $bp->root_domain."/".$activity_slug;
		$redirect_url = $_SERVER['HTTP_REFERER'];
		return $redirect_url;
	}
	else
	{
		$activity_slug = bp_get_activity_root_slug();
		$friends_activity = bp_core_get_user_domain($user->ID).$activity_slug."/friends/";
		return $friends_activity;
	}
}
function buddypress_login_redirection_menu()
{
	add_options_page(__('BP Login Redirect Settings','blr-menu'), __('BP Login Redirect Settings','blr-menu'), 'manage_options', 'blrmenu', 'blr_settings_page');
}
function blr_settings_page()
{
	if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$opt_name = 'blr_select_redirection';
	$hidden_field_name = 'blr_submit_hidden';
	$data_field_name = 'blr_select_redirection';
	
	$opt_val = get_option($opt_name);
	
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
	{
		$opt_val = $_POST[ $data_field_name ];
		update_option( $opt_name, $opt_val );
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'blr-menu' ); ?></strong></p></div>
<?php

    }
	    echo '<div class="wrap">';
		echo "<h2>" . __( 'BuddyPress Login Redirect Settings', 'blr-menu' ) . "</h2>";
?>
<p>Using following option, you can decide where to redirect the users after login.</p>
<form name="bpahp-settings-form" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p><b>You have selected:</b> 
<?php 
	if($opt_val=='one')
		echo 'Personal Profile / Personal Activity';
	elseif($opt_val=='two')
		echo 'Site Wide Activity';
	elseif($opt_val=='four')
		echo 'Same page before login';
	else
		echo "Friends' Activity";
	
?><br /> <hr />
<?php _e("Where to redirect:", 'bpahp-menu' ); ?> 
<select name="<?php echo $data_field_name; ?>">
	<option value="one">Personal Profile / Personal Activity</option>
	<option value="two">Site Wide Activity</option>
    <option value="three">Friends' Activity</option>
<option value="four">Same page before login</option>
</select>
</p>
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
</form>
<hr />
<b> If you like my work, kindly support me to keep my blog working by donating a small amount. For helping me and donation, <a href="http://www.appinstore.com/p/donate.html">click here</a></b>
<p><h2><u>My other plugins:</u></h2></p>
<ul>
<li>BP Login Redirect - Decide where to send your users after login</li>
<li>Force Post Category Selection - No More Uncategoriezed Posts, No More forgetting category selections</li>
<li>Force Post Title - No More Untitled Posts</li>
<li>AutoSet Featured Images for Posts - No need to set featured images manually.It will do it for you automatically.</li>
<li>Wordpress QRCODE Widget - Share your website with Style. It will generate dynamic QR Codes for whole website.</li>
<li>Wordpress Version Remover - Save your wordpress website from hackers. It will remove the wordpress version.</li>
<li>Schedule your Posts - Do not schedule posts now. Just schedule the content of the Post.One Post can show different content daily.</li>
<li><a href="http://www.appinstore.com/search/label/Plugins" alt="www.appinstore.com">Click here to see my plugins.</a></li>
</ul>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-5002381353665916";
/* BP Login Redirect */
google_ad_slot = "7082067397";
google_ad_width = 320;
google_ad_height = 50;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<?php
}	
add_action('admin_menu','buddypress_login_redirection_menu');
add_filter("login_redirect","buddypress_login_redirection",100,3);
?>