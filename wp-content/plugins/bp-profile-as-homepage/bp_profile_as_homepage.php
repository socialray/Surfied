<?php
/*
Plugin Name: BP Profile as Homepage
Description: Logged in users will be redirected to their profile page if they try to move to HomePage anywhere within buddypress installation same as FACEBOOK do. And as the user logs out, he/she is redirected to homepage again. This is tested successfully with Wordpress 3.0 and Buddypress 1.2.5.
Author: Jatinder Pal Singh
Author URI: http://www.appinstore.com
Plugin URI: http://www.appinstore.com/2013/07/wordpress-bp-profile-as-homepage.html
Version: 1.0
*/
function bp_profile_homepage()
{
	global $bp;
	$selected_role = get_option('bpahp_role_choice');
	if($selected_role == '')
	{
		if(is_user_logged_in() && bp_is_front_page())
		{
			wp_redirect( $bp->loggedin_user->domain );
		}
	}
	else
	{
		if(!current_user_can($selected_role) && bp_is_front_page())
		{
			wp_redirect( $bp->loggedin_user->domain );
		}
	}
}
function logout_redirection()
{
	global $bp;
	$redirect = $bp->root_domain;
	wp_logout_url( $redirect );	
}
function bp_profile_as_homepage_menu()
{
	add_options_page(__('BP Profile as Homepage Settings','bpahp-menu'), __('BP Profile as Homepage Settings','bpahp-menu'), 'manage_options', 'bpahpmenu', 'bpahp_settings_page');
}
function bpahp_settings_page()
{
	if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$opt_name = 'bpahp_role_choice';
	$hidden_field_name = 'bpahp_submit_hidden';
	$data_field_name = 'bpahp_role_choice';
	
	$opt_val = get_option($opt_name);
	
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
	{
		$opt_val = $_POST[ $data_field_name ];
		update_option( $opt_name, $opt_val );
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'bpahp-menu' ); ?></strong></p></div>
<?php

    }
	    echo '<div class="wrap">';
		echo "<h2>" . __( 'BP Profile as Homepage Settings', 'bpahp-menu' ) . "</h2>";
?>
<p>Using following option, you can disable the redirection for a particular user role.</p>
<form name="bpahp-settings-form" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p><b>You have selected:</b> 
<?php 
if ($opt_val=='')
	echo 'No One';
else
	echo $opt_val; 
?> <hr />
<?php _e("Who can view Homepage:", 'bpahp-menu' ); ?> 
<select name="<?php echo $data_field_name; ?>">
	<option value="">None</option>
	<option value="administrator">Administrators</option>
    <option value="editor">Editors</option>
    <option value="author">Authors</option>
    <option value="contributor">Contributors</option>
    <option value="subscriber">Subscribers</option>
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
</div>
<?php
}	

add_action('admin_menu','bp_profile_as_homepage_menu');
add_action('wp','bp_profile_homepage');
add_action('wp_logout','logout_redirection');
?>