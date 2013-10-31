<?php
/**
 * Add option page tabs script.
 */
function facebookall_options_page_scripts(){
  $jsscript = apply_filters('facebookall_adminjs', plugins_url('assets/js/adminoption.js?v=4.0.0', __FILE__));
  wp_enqueue_script('facebookall_option_adminjs', $jsscript, array('jquery-ui-tabs'));
}

/**
 * Add option page css.
 */
function facebookall_options_page_style(){
  $adminstyle = apply_filters('facebookall_admincss', plugins_url('assets/css/adminoption.css', __FILE__));
  wp_enqueue_style('facebookall_options_adminstyle', $adminstyle.'?v=4.0.0');
}

/**
 * Register admin settings.
 */
function facebookall_register_admin_options(){
	register_setting('facebookall_admin_configration', 'fball_settings', 'facebookall_save_admin_settings');
}
add_action('admin_init', 'facebookall_register_admin_options');

/**
 * Function that getting app result from facebook.
 */
function facebookall_getapp_result($apikey) {
  $url = "https://graph.facebook.com/".$apikey;
  if (function_exists('curl_init')) {
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url);
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
    $app_result = curl_exec( $curl );
    curl_close($curl);
    $app_result = json_decode($app_result);
  }
  else {
    $app_result = @file_get_contents($url);
    $app_result = json_decode($app_result);
  }
  return $app_result;
}
/**
 * Function that save all option page admin settings.
 */
function facebookall_save_admin_settings($fball_settings) {
  $fball_settings['apikey'] = trim($fball_settings['apikey']);
  $fball_settings['apisecret'] = trim($fball_settings['apisecret']);
  $fball_settings['connection_handler'] = ((isset($fball_settings['connection_handler']) && in_array($fball_settings['connection_handler'], array('curl', 'fopen'))) ? $fball_settings['connection_handler'] : 'curl');
  $fball_settings['login_title'] = trim($fball_settings['login_title']);
  $fball_settings['fbicon_text'] = trim($fball_settings['fbicon_text']);
  $fball_settings['custom_button'] = trim($fball_settings['custom_button']);
  $fball_settings['fbavatar'] = ((isset($fball_settings['fbavatar']) && in_array($fball_settings['fbavatar'], array('fbavatar', 'default'))) ? $fball_settings['fbavatar'] : 'fbavatar');
  $fball_settings['showcolumn'] = ((isset($fball_settings['showcolumn']) && in_array($fball_settings['showcolumn'], array('yesshowcolumn', 'noshowcolumn'))) ? $fball_settings['showcolumn'] : 'yesshowcolumn');
  $fball_settings['linkaccount'] = ((isset($fball_settings['linkaccount']) && in_array($fball_settings['linkaccount'], array('link', 'nolink'))) ? $fball_settings['linkaccount'] : 'link');
  $fball_settings['autoapprove'] = ((isset($fball_settings['autoapprove']) && in_array($fball_settings['autoapprove'], array('1', '0'))) ? $fball_settings['autoapprove'] : '1');
  $fball_settings['redirect'] = ((isset($fball_settings['redirect']) && in_array($fball_settings['redirect'], array('home', 'current', 'account', 'custom'))) ?     $fball_settings['redirect'] : 'current');
  $fball_settings['custom_url'] = trim($fball_settings['custom_url']);
  $fball_settings['enablenewpost'] = ((isset($fball_settings['enablenewpost']) && in_array($fball_settings['enablenewpost'], array('1', '0'))) ? $fball_settings['enablenewpost'] : '0');
  $fball_settings['new_post_title'] = trim($fball_settings['new_post_title']);
  $fball_settings['new_post_url'] = trim($fball_settings['new_post_url']);
  $fball_settings['new_post_message'] = trim($fball_settings['new_post_message']);
  $fball_settings['new_post_pic'] = trim($fball_settings['new_post_pic']);
  $fball_settings['new_post_desc'] = trim($fball_settings['new_post_desc']);
  $fball_settings['enableoldpost'] = ((isset($fball_settings['enableoldpost']) && in_array($fball_settings['enableoldpost'], array('1', '0'))) ? $fball_settings['enableoldpost'] : '0');
  $fball_settings['old_post_title'] = trim($fball_settings['old_post_title']);
  $fball_settings['old_post_url'] = trim($fball_settings['old_post_url']);
  $fball_settings['old_post_message'] = trim($fball_settings['old_post_message']);
  $fball_settings['old_post_pic'] = trim($fball_settings['old_post_pic']);
  $fball_settings['old_post_desc'] = trim($fball_settings['old_post_desc']);
  $fball_settings['enablecomments'] = ((isset($fball_settings['enablecomments']) && in_array($fball_settings['enablecomments'], array('1', '0'))) ? $fball_settings['enablecomments'] : '0');
  $fball_settings['comment_title'] = trim($fball_settings['comment_title']);
  $fball_settings['comment_appid'] = trim($fball_settings['comment_appid']);
  $fball_settings['comment_width'] = trim($fball_settings['comment_width']);
  $fball_settings['comment_numpost'] = trim($fball_settings['comment_numpost']);
  $fball_settings['comment_color'] = ((isset($fball_settings['comment_color']) && in_array($fball_settings['comment_color'], array('1', '0'))) ? $fball_settings['comment_color'] : '1');
  $fball_settings['enableshare'] = ((isset($fball_settings['enableshare']) && in_array($fball_settings['enableshare'], array('1', '0'))) ? $fball_settings['enableshare'] : '0');
  $fball_settings['share_title'] = trim($fball_settings['share_title']);
  $fball_settings['share_layout'] = ((isset($fball_settings['share_layout']) && in_array($fball_settings['share_layout'], array('1', '0'))) ? $fball_settings['share_layout'] : '1');
  $fball_settings['fanbox_pageurl'] = trim($fball_settings['fanbox_pageurl']);
  $fball_settings['fanbox_width'] = trim($fball_settings['fanbox_width']);
  $fball_settings['fanbox_height'] = trim($fball_settings['fanbox_height']);
  $fball_settings['fanbox_color'] = ((isset($fball_settings['fanbox_color']) && in_array($fball_settings['fanbox_color'], array('1', '0'))) ? $fball_settings['fanbox_color'] : '1');
  $fball_settings['fanbox_faces'] = ((isset($fball_settings['fanbox_faces']) && in_array($fball_settings['fanbox_faces'], array('1', '0'))) ? $fball_settings['fanbox_faces'] : '1');
  $fball_settings['fanbox_stram'] = ((isset($fball_settings['fanbox_stram']) && in_array($fball_settings['fanbox_stram'], array('1', '0'))) ? $fball_settings['fanbox_stram'] : '0');
  $fball_settings['fanbox_header'] = ((isset($fball_settings['fanbox_header']) && in_array($fball_settings['fanbox_header'], array('1', '0'))) ? $fball_settings['fanbox_header'] : '0');
  $fball_settings['facepile_pageurl'] = trim($fball_settings['facepile_pageurl']);
  $fball_settings['facepile_width'] = trim($fball_settings['facepile_width']);
  $fball_settings['facepile_numrows'] = trim($fball_settings['facepile_numrows']);
  $fball_settings['facepile_color'] = ((isset($fball_settings['facepile_color']) && in_array($fball_settings['facepile_color'], array('1', '0'))) ? $fball_settings['facepile_color'] : '1');
  $fball_settings['facepile_size'] = ((isset($fball_settings['facepile_size']) && in_array($fball_settings['facepile_size'], array('0', '1', '2'))) ? $fball_settings['facepile_size'] : '1');
  $fball_settings['enable_recbar'] = ((isset($fball_settings['enable_recbar']) && in_array($fball_settings['enable_recbar'], array('1', '0'))) ? $fball_settings['enable_recbar'] : '0');
  $fball_settings['recbar_pageurl'] = trim($fball_settings['recbar_pageurl']);
  $fball_settings['recbar_readtime'] = trim($fball_settings['recbar_readtime']);
  $fball_settings['recbar_appid'] = trim($fball_settings['recbar_appid']);
  $fball_settings['recbar_verb'] = ((isset($fball_settings['recbar_verb']) && in_array($fball_settings['recbar_verb'], array('1', '0'))) ? $fball_settings['facepile_color'] : '1');
  $fball_settings['recbar_side'] = ((isset($fball_settings['recbar_side']) && in_array($fball_settings['recbar_side'], array('0', '1'))) ? $fball_settings['facepile_size'] : '1');

  
	foreach(array('loginpage', 'registerpage', 'commentpage', 'comment_top', 'comment_bottom', 'comment_homepage', 'comment_posts', 'comment_pages', 'comment_postecerpts', 'comment_archives', 'comment_feed', 'share_facebook','share_linkedin', 'share_twitter', 'share_pin', 'share_gplus', 'share_digg', 'share_top', 'share_bottom', 'share_home','share_posts', 'share_pages', 'share_postexcerpts', 'share_archives', 'share_feed') as $val){
		
	}
	return $fball_settings;
}

/**
 * Function displays option/settings page.
 */
function facebookall_admin_settings() {
  $fball_settings = get_option("fball_settings");?>
  <div class="wrapper">
    <div class="wrap">
      <div id="icon-options-general" class="icon32">
      <br>
      </div>
      <h2><?php _e('Facebook All Admin Settings', 'facebookall');?></h2><br />
	  <div style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin-bottom:5px; width: 1060px; font-size:13px;">
			 <?php _e('Please upgrade your plugin with more new feature ...', 'facebookall') ?> <a target="_blank" href="http://www.sourceaddons.com/wp_feature.php" >  <?php _e('click here', 'facebookall') ?> </a>.
		</div>
      <div id="fballwelcome-panel" class="fballwelcome-panel">
        <div class="fballwelcome-panel-content">
        <h3><?php _e('Thank you for having Facebook All!', 'facebookall');?></h3>
        <div class="fballwelcome-panel-column-container">
        <div class="fballwelcome-panel-column">
        <h4><?php _e('Help Links:', 'facebookall');?></h4>
		<ul>
		<li><a href="http://www.sourceaddons.com/" target="_blank"><?php _e('Installation Documentation', 'facebookall');?></a></li>
		<li><a href="http://www.sourceaddons.com/" target="_blank"><?php _e('Plugin site', 'facebookall');?></a></li>
		<li><a href="http://www.sourceaddons.com/contact_us.php" target="_blank"><?php _e('Tech Support', 'facebookall');?></a></li>
		</ul>
        <a class="button button-primary button-hero hide-if-customize" target="_blank" href="http://www.sourceaddons.com/contact_us.php"><?php _e('Feedback To Plugin Site', 'facebookall');?></a>
        </div>
		<div class="fballwelcome-panel-column">
        <h4><?php _e('Your Facebook App Info:', 'facebookall');?></h4>
		<?php if (!empty($fball_settings['apikey'])) {
		        $app_result = facebookall_getapp_result($fball_settings['apikey']);
				$app_id = (!empty($app_result->id) ? $app_result->id : "");
                $app_name = (!empty($app_result->name) ? $app_result->name : "");
                $app_url = (!empty($app_result->link) ? $app_result->link : "");
                $app_icon = (!empty($app_result->icon_url) ? $app_result->icon_url : "");
                $app_logo = (!empty($app_result->logo_url) ? $app_result->logo_url : "");
                $app_daily_users = (!empty($app_result->daily_active_users) ? $app_result->daily_active_users : "0");
                $app_weak_users = (!empty($app_result->weekly_active_users) ? $app_result->weekly_active_users : "0");
                $app_month_users = (!empty($app_result->monthly_active_users) ? $app_result->monthly_active_users : "0");
              }?>
              <div style="float:left; width:615px;">
                <div>
                    
					<?php if (!empty($app_id)) {?>
                     <div style="float:left;width:80px"> <img src="<?php echo $app_logo;?>" /> </div>
                      <div style="float: right; margin: 0 0 0 20px">
                       <p style="margin:0 0 5px 0;"><?php _e('Application ID:', 'facebookall');?> <b><?php echo $app_id;?></b></p>
                       <p style="margin:0 0 5px 0;"><?php _e('Application Name:', 'facebookall');?> <b><?php echo $app_name;?></b></p>
                       <p style="margin:0 0 5px 0;"><?php _e('Site URL:', 'facebookall');?> <b><?php echo site_url();?></b></p>
                       <p style="margin:0 0 5px 0;"><?php _e('Site Domain(s):', 'facebookall');?> <b><?php echo $_SERVER['HTTP_HOST'];?></b></p>
                       <p style="margin:0 0 5px 0;"><?php _e('Application Url:', 'facebookall');?> <a href="<?php echo $app_url;?>" target="_blank"><?php echo $app_url;?></a></p>
                      </div>
                      
                    <?php } else {?>
                       <p style="margin:0 0 5px 0; color:#FF0000; width:500px;"><?php printf (__ ('Not get any configured app info for your site. You have not saved api key and secret still or please %s and make sure cURL/FSOCKOPEN is enabled in your php.ini', 'facebookall'),'<a href="http://developers.facebook.com/" target="_blank">configure facebook app</a>');?></p>
                    <?php }?>
					</div>
                 </div>
			</div>
       </div>
     </div>
    </div>
    </div>
	<?php if (!empty ($_REQUEST ['settings-updated']) AND strtolower ($_REQUEST ['settings-updated']) == 'true') { ?>
            <div class="fball_updatemsg_box" id="fball_box_updated">
            <?php _e ('Your modifications have been saved successfully!'); ?>
            </div>
    <?php }?>
     <div id="tabs">
			<h2 class="nav-tab-wrapper" style="height:36px">
				<ul>
						<li style="margin-left:9px"><a style="margin:0" class="nav-tab" href="#tabs-1"><?php _e('Basic Settings', 'facebookall');?></a></li>
						<li><a style="margin:0" class="nav-tab" href="#tabs-2"><?php _e('Wall Post', 'facebookall') ?></a></li>
						<li><a style="margin:0" class="nav-tab" href="#tabs-3"><?php _e('Comment/Share', 'facebookall') ?></a></li>
						<li><a style="margin:0" class="nav-tab" href="#tabs-4"><?php _e('Fanbox/Faceplie', 'facebookall') ?></a></li>
						<li><a style="margin:0" class="nav-tab" href="#tabs-5"><?php _e('Recommendations Bar', 'facebookall') ?></a></li>
                </ul>
			</h2>
				<div style="float:left; width:70%;">
				<form action="options.php" method="post">
				<?php settings_fields('facebookall_admin_configration'); ?>
					<div id="tabs-1">
                     <!-- Form basic Box -->
                        <table class="facebookall_table">
                         <tr>
   	                     <th class="head" colspan="2"><?php _e('Facebook All API Configuration', 'facebookall');?></th>
                         </tr>
                         <tr>
                         <th scope="fballrow"><?php _e('Facebook API ID', 'facebookall');?></th>
                         <td><input type="text" name="fball_settings[apikey]" id="apikey" value="<?php echo (isset($fball_settings['apikey']) ? htmlspecialchars ($fball_settings['apikey']) : ''); ?>" size="90"/></td>
                         </tr>
                         <tr>
                         <th scope="fballrow"><?php _e('Facebook API Secret', 'facebookall');?></th>
                         <td>
                         <input type="text" name="fball_settings[apisecret]" id="apisecret" value="<?php echo (isset($fball_settings['apisecret']) ? htmlspecialchars ($fball_settings['apisecret']) : ''); ?>" size="90"/></td>
                         </tr>
                          <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('API Connection Handler', 'facebookall');?></th>
                         <td>
						 <?php $curl = "";
                               $fopen = "";
                               if($fball_settings["connection_handler"] == "curl") $curl = "checked='checked'";
                               elseif($fball_settings["connection_handler"] == "fopen") $fopen = "checked='checked'";
                               else $curl = "checked='checked'";?>
                         <input name="fball_settings[connection_handler]" id ="curl" type="radio" <?php echo $curl;?>value="curl" />&nbsp;&nbsp;<?php _e('Use CURL to communicate with Facebook API', 'facebookall');?>  <br />
                         <input name="fball_settings[connection_handler]" id ="fopen" type="radio" <?php echo $fopen;?>value="fopen" />&nbsp;&nbsp;<?php _e('Use FSOCKOPEN to communicate with Facebook API', 'facebookall');?> 
                        </td>
                        </tr>
                        <tr class="fballrow_white">
                        <td>
                        <div class="fballrow fballrow_fbbutton">
                        <div class="blank">
		                 <input id="sitebase_url" type="hidden" value="<?php echo FACEBOOKALL_PLUGIN_URL.'/helpers/facebookall_detectapi.php';?>" />
                         <a href="javascript:void(0);" onclick="MakeApiRequest();"><b style="color:#FFFFFF !important;"><?php _e('Verify API Settings', 'facebookall');?></b></a>
		               </div>
                       </div>
                       </td>
                       <td><div id="showmsg" style="font-weight:bold;"></div></td>
                        </tr>
                       </table>
					   <table class="facebookall_table">
                       <tr>
                        <th class="head" colspan="2"><?php _e('Facebook All Display Configuration', 'facebookall');?></th>
                       </tr>
                      
                       <tr>
                       <th scope="fballrow"><?php _e('Title shows Above Facebook Button', 'facebookall');?></th>
                       <td>
                       <input type="text" name="fball_settings[login_title]" value="<?php echo (isset($fball_settings['login_title']) ? htmlspecialchars ($fball_settings['login_title']) : ''); ?>" size="90"/>
					   </td>
                       </tr>
					   <tr>
                       <th scope="fballrow"><?php _e('Text shows on Facebook Button', 'facebookall');?></th>
                       <td>
                       <input type="text" name="fball_settings[fbicon_text]" value="<?php echo (isset($fball_settings['fbicon_text']) ? htmlspecialchars ($fball_settings['fbicon_text']) : 'Login with Facebook'); ?>" size="90"/>
					   </td>
                       </tr>
					   <tr  class="fballrow_white">
                       <th scope="fballrow"><?php _e('Use Custom Facebook Button', 'facebookall');?><br /><small><?php _e('Enter full url of your custom image', 'facebookall');?></small></th>
                       <td>
                       <input type="text" name="fball_settings[custom_button]" value="<?php echo (isset($fball_settings['custom_button']) ? htmlspecialchars ($fball_settings['custom_button']) : ''); ?>" size="90" placeholder="http://example.com/images/myimage.png"/>
					   </td>
                       </tr>
					   <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('Show Facebook Avatar', 'facebookall');?></th>
                         <td>
						 <?php $fbavatar = "";
                               $noavatar = "";
                               if($fball_settings["fbavatar"] == "fbavatar") $fbavatar = "checked='checked'";
                               elseif($fball_settings["fbavatar"] == "default") $noavatar = "checked='checked'";
                               else $fbavatar = "checked='checked'";?>
                         <input name="fball_settings[fbavatar]" type="radio" <?php echo $fbavatar;?> value="fbavatar" />&nbsp;&nbsp;<?php _e('Yes, show user avatar from Facebook if available', 'facebookall');?>  <br />
                         <input name="fball_settings[fbavatar]" type="radio" <?php echo $noavatar;?>value="default" />&nbsp;&nbsp;<?php _e('No, display the default avatar', 'facebookall');?>
                        </td>
                        </tr>
						 <tr>
                         <th scope="fballrow"><?php _e('Show Facebook Login Button On', 'facebookall');?></th>
                         <td>
                         <input type="checkbox"  name="fball_settings[loginpage]" value="1" <?php echo isset($fball_settings['loginpage']) && $fball_settings['loginpage'] == 1 ? 'checked' : '' ?> /> &nbsp;&nbsp;<label><?php _e('On User Login Page', 'facebookall');?></label><br />
						 <input type="checkbox"  name="fball_settings[registerpage]" value="1" <?php echo isset($fball_settings['registerpage']) && $fball_settings['registerpage'] == 1 ? 'checked' : '' ?> /> &nbsp;&nbsp;<label><?php _e('On User Register Page', 'facebookall');?></label><br />
                         <input type="checkbox"  name="fball_settings[commentpage]" value="1" <?php echo isset($fball_settings['commentpage']) && $fball_settings['commentpage'] == 1 ? 'checked' : '' ?> /> &nbsp;&nbsp;<label><?php _e('On Comment Form', 'facebookall');?></label>
                        </td>
                        </tr>
						 <tr>
                         <th scope="fballrow"><?php _e('Show Facebook User in User List','facebookall');?></th>
                         <td>
						 <?php $yesshowcolumn = "";
                               $noshowcolumn = "";
                               if($fball_settings["showcolumn"] == "yesshowcolumn") $yesshowcolumn = "checked='checked'";
                               elseif($fball_settings["showcolumn"] == "noshowcolumn") $noshowcolumn = "checked='checked'";
                               else $yesshowcolumn = "checked='checked'";?>
                         <input name="fball_settings[showcolumn]" type="radio" <?php echo $yesshowcolumn;?>value="yesshowcolumn" />&nbsp;&nbsp;<?php _e('Yes, add a new column to the user list and display Facebook profile id','facebookall');?>   <br />
                         <input name="fball_settings[showcolumn]" type="radio" <?php echo $noshowcolumn;?>value="noshowcolumn" />&nbsp;&nbsp;<?php _e('No, not display Facebook profile id','facebookall');?> 
                        </td>
                        </tr>
                       </table>
                       <table class="facebookall_table">
                       <tr>
                        <th class="head" colspan="2"><?php _e('Facebook All Basic Configuration','facebookall');?></th>
                       </tr>
                      
                       <tr>
                       <th scope="fballrow"><?php _e('Link Exist Account','facebookall');?></th>
                       <td>
					    <?php $link = "";
                               $nolink = "";
                               if($fball_settings["linkaccount"] == "link") $link = "checked='checked'";
                               elseif($fball_settings["linkaccount"] == "nolink") $nolink = "checked='checked'";
                               else $link = "checked='checked'";?>
                       <input name="fball_settings[linkaccount]" type="radio" <?php echo $link;?>value="link"/>&nbsp;&nbsp;<?php _e('Yes, try to link verified facebook profile to existing accounts','facebookall');?>  <br />
					   <input name="fball_settings[linkaccount]" type="radio" <?php echo $nolink;?>value="nolink" />&nbsp;&nbsp;<?php _e('No, disable account linking','facebookall');?> 
                       </td>
                       </tr>
					   
						 <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('Auto Approve Comments','facebookall');?></th>
                         <td>
						 <?php $autoapprove = "";
                               $noautoapprove = "";
                               if($fball_settings["autoapprove"] == "1") $autoapprove = "checked='checked'";
                               elseif($fball_settings["autoapprove"] == "0") $noautoapprove = "checked='checked'";
                               else $autoapprove = "checked='checked'";?>
                         <input name="fball_settings[autoapprove]" type="radio" <?php echo $autoapprove;?>value="1" />&nbsp;&nbsp;<?php _e('Yes, automatically approve comments made by users that connected with Facebook','facebookall');?>  <br />
                         <input name="fball_settings[autoapprove]" type="radio" <?php echo $noautoapprove;?>value="0" />&nbsp;&nbsp;<?php _e('No, do not automatically approve','facebookall');?>
                        </td>
                        </tr>
						
						 <tr>
                         <th scope="fballrow"><?php _e('Redirect User After Login','facebookall');?></th>
                         <td>
						 <?php 
						$samepage = "";
						$homepage = "";
						$dashboard = "";
						$custom = "";
						if ($fball_settings["redirect"] == "home") $homepage = "checked='checked'";
						elseif($fball_settings["redirect"] == "current") $samepage = "checked='checked'";
						elseif($fball_settings["redirect"] == "account") $dashboard = "checked='checked'";
						elseif($fball_settings["redirect"] == "custom") $custom = "checked='checked'";
						else $samepage = "checked='checked'";
						?>
                         <input name="fball_settings[redirect]" type="radio" <?php echo $homepage;?>value="home" />&nbsp;&nbsp;<?php _e('Redirect users to home page of my blog','facebookall');?> <br />
                         <input name="fball_settings[redirect]" type="radio" <?php echo $samepage;?>value="current" />&nbsp;&nbsp;<?php _e('Redirect users back to the current page','facebookall');?><br />
						 <input name="fball_settings[redirect]" type="radio" <?php echo $dashboard;?>value="account" />&nbsp;&nbsp;<?php _e('Redirect to their account dashboard','facebookall');?>  <br />
                         <input name="fball_settings[redirect]" type="radio" <?php echo $custom;?>value="custom" />&nbsp;&nbsp;<?php _e('Redirect to following url:','facebookall');?><br />
						 <input type="text" name="fball_settings[custom_url]" value="<?php if($fball_settings["redirect"]=='custom'){echo htmlspecialchars($fball_settings["custom_url"]);} ?>" placeholder="http://myblog.com/?page_id=3" size="90" />	
                        </td>
                        </tr>
                       </table>
					 <!-- Form basic ends-->
                    </div>

               <!-- Wall post settings-->
				<div id="tabs-2">
                        <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('New User Wall/Status Settings','facebookall');?></th>
                         </tr>
                         <tr>
                         <th><?php _e('Post on the new users wall','facebookall');?></th>
                          <td>
						  <?php $enablenewpost = "";
                               $noenablenewpost = "";
                               if($fball_settings["enablenewpost"] == "1") $enablenewpost = "checked='checked'";
                               elseif($fball_settings["enablenewpost"] == "0") $noenablenewpost = "checked='checked'";
                               else $noenablenewpost = "checked='checked'";?>
                         <input name="fball_settings[enablenewpost]" type="radio" <?php echo $enablenewpost;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[enablenewpost]" type="radio" <?php echo $noenablenewpost;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
                          <tr>
                        <th scope="fballrow"><?php _e('Post Title','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[new_post_title]" value="<?php echo (isset($fball_settings['new_post_title']) ? htmlspecialchars ($fball_settings['new_post_title']) : ''); ?>"/></td>
                       </tr>
                        <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('Post Url(link)','facebookall');?></th>
                       <td>
                         <input size="90" type="text" name="fball_settings[new_post_url]" value="<?php echo (isset($fball_settings['new_post_url']) ? htmlspecialchars ($fball_settings['new_post_url']) : ''); ?>"/></td>
                       </tr>
                      <tr class="fballrow_white">
                       <th scope="fballrow"><?php _e('Post Message','facebookall');?></th>
                       <td><textarea rows="3" cols="87"  name="fball_settings[new_post_message]" value="<?php echo (isset($fball_settings['new_post_message']) ? htmlspecialchars ($fball_settings['new_post_message']) : ''); ?>"/></textarea></td>
                       </tr>
                       <tr >
                        <th scope="fballrow"><?php _e('Post Picture Url','facebookall');?></th>
                         <td>
                         <input size="90" type="text" name="fball_settings[new_post_pic]" value="<?php echo (isset($fball_settings['new_post_pic']) ? htmlspecialchars ($fball_settings['new_post_pic']) : ''); ?>"/></td>
                         </tr>
                         <tr >
                         <th scope="fballrow"><?php _e('Post Description','facebookall');?></th>
                         <td>
                         <textarea rows="3" cols="87"  name="fball_settings[new_post_desc]" value="<?php echo (isset($fball_settings['new_post_desc']) ? htmlspecialchars ($fball_settings['new_post_desc']) : ''); ?>"/></textarea></td>
                        </tr>
                         </table>
						 <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Returning User Wall/Status Settings','facebookall');?></th>
                         </tr>
                         <tr>
                         <th><?php _e('Post to the returning users wall','facebookall');?></th>
                          <td>
						  <?php $enableoldpost = "";
                               $noenableoldpost = "";
                               if($fball_settings["enableoldpost"] == "1") $enableoldpost = "checked='checked'";
                               elseif($fball_settings["enableoldpost"] == "0") $noenableoldpost = "checked='checked'";
                               else $noenableoldpost = "checked='checked'";?>
                         <input name="fball_settings[enableoldpost]" type="radio" <?php echo $enableoldpost;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[enableoldpost]" type="radio" <?php echo $noenableoldpost;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
                          <tr >
                        <th scope="fballrow"><?php _e('Post Title','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[old_post_title]" value="<?php echo (isset($fball_settings['old_post_title']) ? htmlspecialchars ($fball_settings['old_post_title']) : ''); ?>"/></td>
                       </tr>
                        <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('Post Url(link)','facebookall');?></th>
                       <td>
                         <input size="90" type="text" name="fball_settings[old_post_url]" value="<?php echo (isset($fball_settings['old_post_url']) ? htmlspecialchars ($fball_settings['old_post_url']) : ''); ?>"/></td>
                       </tr>
                      <tr class="fballrow_white">
                       <th scope="fballrow"><?php _e('Post Message','facebookall');?></th>
                       <td><textarea rows="3" cols="87"  name="fball_settings[old_post_message]" value="<?php echo (isset($fball_settings['old_post_message']) ? htmlspecialchars ($fball_settings['old_post_message']) : ''); ?>"/></textarea></td>
                       </tr>
                       <tr >
                        <th scope="fballrow"><?php _e('Post Picture Url','facebookall');?></th>
                         <td>
                         <input size="90" type="text" name="fball_settings[old_post_pic]" value="<?php echo (isset($fball_settings['old_post_pic']) ? htmlspecialchars ($fball_settings['old_post_pic']) : ''); ?>"/></td>
                         </tr>
                         <tr >
                         <th scope="fballrow"><?php _e('Post Description','facebookall');?></th>
                         <td>
                         <textarea rows="3" cols="87"  name="fball_settings[old_post_desc]" value="<?php echo (isset($fball_settings['old_post_desc']) ? htmlspecialchars ($fball_settings['old_post_desc']) : ''); ?>"/></textarea></td>
                        </tr>
                         </table>
                         </div>
					 <!-- Wall post settings ends-->
					 
					 <!-- comment settings -->				
						<div id="tabs-3">
                        <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Facebook All Comments Settings','facebookall');?></th>
                         </tr>
                         <tr>
                         <th><?php _e('Enable facebook comments','facebookall');?></th>
                          <td>
						   <?php $enablecomments = "";
                               $noenablecomments = "";
                               if($fball_settings["enablecomments"] == "1") $enablecomments = "checked='checked'";
                               elseif($fball_settings["enablecomments"] == "0") $noenablecomments = "checked='checked'";
                               else $noenablecomments = "checked='checked'";?>
                         <input name="fball_settings[enablecomments]" type="radio" <?php echo $enablecomments;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[enablecomments]" type="radio" <?php echo $noenablecomments;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Title displays above comment box','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[comment_title]" value="<?php echo (isset($fball_settings['comment_title']) ? htmlspecialchars ($fball_settings['comment_title']) : ''); ?>"/></td>
                       </tr>
					   <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('App Id','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[comment_appid]" value="<?php echo (isset($fball_settings['comment_appid']) ? htmlspecialchars ($fball_settings['comment_appid']) : ''); ?>"/></td>
                       </tr>
                          <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('Comment Box Width','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[comment_width]" value="<?php echo (isset($fball_settings['comment_width']) ? htmlspecialchars ($fball_settings['comment_width']) : ''); ?>"/></td>
                       </tr>
                        <tr>
                         <th scope="fballrow"><?php _e('Number of posts displays in box','facebookall');?></th>
                       <td>
                         <input size="90" type="text" name="fball_settings[comment_numpost]" value="<?php echo (isset($fball_settings['comment_numpost']) ? htmlspecialchars ($fball_settings['comment_numpost']) : ''); ?>"/></td>
                       </tr>
                      
                      <tr>
                         <th><?php _e('Color Scheme','facebookall');?></th>
                          <td>
						  <?php $light = "";
                               $dark = "";
                               if($fball_settings["comment_color"] == "1") $light = "checked='checked'";
                               elseif($fball_settings["comment_color"] == "0") $dark = "checked='checked'";
                               else $light = "checked='checked'";?>
                         <input name="fball_settings[comment_color]" type="radio" <?php echo $light;?>value="1" />&nbsp;&nbsp;<?php _e('Light','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[comment_color]" type="radio" <?php echo $dark;?>value="0" />&nbsp;&nbsp;<?php _e('Dark','facebookall');?>
						 </td>
                        </tr>
						
						<tr>
                         <th><?php _e('Select position','facebookall');?></th>
                          <td>
                         <input type="checkbox" name="fball_settings[comment_top]" value="1" <?php echo isset($fball_settings['comment_top']) && $fball_settings['comment_top'] == 1 ? 'checked' : '' ?> /> <?php _e('Show at top of the content','facebookall');?> <br /> 
					     <input type="checkbox" name="fball_settings[comment_bottom]" value="1" <?php echo isset($fball_settings['comment_bottom']) && $fball_settings['comment_bottom'] == 1 ? 'checked' : '' ?> /> <?php _e('Show at bottom of the content','facebookall');?>
						 </td>
                        </tr>
                       
					     <tr class="fballrow_white">
                         <th><?php _e('Select Area For Display Comments','facebookall');?></th>
                          <td>
                         <input type="checkbox" name="fball_settings[comment_homepage]" value="1"<?php echo isset($fball_settings['comment_homepage']) && $fball_settings['comment_homepage'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Home Page','facebookall');?> <br /> 
					     <input type="checkbox" name="fball_settings[comment_posts]" value="1"<?php echo isset($fball_settings['comment_posts']) && $fball_settings['comment_posts'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Posts','facebookall');?><br />
                         <input type="checkbox" name="fball_settings[comment_pages]" value="1"<?php echo isset($fball_settings['comment_pages']) && $fball_settings['comment_pages'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Pages','facebookall');?><br /> 
					   <input type="checkbox" name="fball_settings[comment_postecerpts]" value="1"<?php echo isset($fball_settings['comment_postecerpts']) && $fball_settings['comment_postecerpts'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Post Excerpts','facebookall');?><br />
                         <input type="checkbox" name="fball_settings[comment_archives]" value="1" <?php echo isset($fball_settings['comment_archives']) && $fball_settings['comment_archives'] == 1 ? 'checked' : '' ?>/> <?php _e('Show On Archive Pages','facebookall');?><br /> 
					     <input type="checkbox" name="fball_settings[comment_feed]" value="1"<?php echo isset($fball_settings['comment_feed']) && $fball_settings['comment_feed'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Feed','facebookall');?>

						 </td>
                        </tr>
                        
                         </table>
						 <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Facebook All Share Settings','facebookall');?></th>
                         </tr>
                         <tr>
                         <th><?php _e('Enable Social Sharing','facebookall');?></th>
                          <td>
						  <?php $enableshare = "";
                               $noenableshare = "";
                               if($fball_settings["enableshare"] == "1") $enableshare = "checked='checked'";
                               elseif($fball_settings["enableshare"] == "0") $noenableshare = "checked='checked'";
                               else $noenableshare = "checked='checked'";?>
                         <input name="fball_settings[enableshare]" type="radio" <?php echo $enableshare;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[enableshare]" type="radio" <?php echo $noenableshare;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Title displays above share widget','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[share_title]" value="<?php echo (isset($fball_settings['share_title']) ? htmlspecialchars ($fball_settings['share_title']) : ''); ?>"/></td>
                       </tr>
                          <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('Layout Style','facebookall');?></th>
                         <td>
						  <?php $buttoncount = "";
                               $boxcount = "";
                               if($fball_settings["share_layout"] == "1") $buttoncount = "checked='checked'";
                               elseif($fball_settings["share_layout"] == "0") $boxcount = "checked='checked'";
                               else $buttoncount = "checked='checked'";?>
						 <input name="fball_settings[share_layout]" type="radio" <?php echo $buttoncount;?>value="1" />&nbsp;&nbsp;<?php _e('Button Count','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[share_layout]" type="radio" <?php echo $boxcount;?>value="0" />&nbsp;&nbsp;<?php _e('Box Count','facebookall');?>
                        </td>
                       </tr>
                        <tr class="fballrow_white">
                         <th scope="fballrow"><?php _e('Enable Social Providers in Share','facebookall');?> </th>
                       <td>
                         <input type="checkbox" name="fball_settings[share_facebook]" value="1"<?php echo isset($fball_settings['share_facebook']) && $fball_settings['share_facebook'] == 1 ? 'checked' : '' ?> />  <?php _e('Facebook Like Button','facebookall');?> <br /> 
					   <input type="checkbox" name="fball_settings[share_linkedin]" value="1"<?php echo isset($fball_settings['share_linkedin']) && $fball_settings['share_linkedin'] == 1 ? 'checked' : '' ?> />  <?php _e('Linkedin Share Button','facebookall');?><br />
                         <input type="checkbox" name="fball_settings[share_twitter]" value="1"<?php echo isset($fball_settings['share_twitter']) && $fball_settings['share_twitter'] == 1 ? 'checked' : '' ?> />  <?php _e('Twitter Button','facebookall');?> <br /> 
					     <input type="checkbox" name="fball_settings[share_pin]" value="1" <?php echo isset($fball_settings['share_pin']) && $fball_settings['share_pin'] == 1 ? 'checked' : '' ?> />  <?php _e('Pinterest Button','facebookall');?> <br />
                         <input type="checkbox" name="fball_settings[share_gplus]" value="1"<?php echo isset($fball_settings['share_gplus']) && $fball_settings['share_gplus'] == 1 ? 'checked' : '' ?> />  <?php _e('Googleplus Button','facebookall');?>  <br /> 
					     <input type="checkbox" name="fball_settings[share_digg]" value="1"<?php echo isset($fball_settings['share_digg']) && $fball_settings['share_digg'] == 1 ? 'checked' : '' ?> />  <?php _e('Digg Button','facebookall');?>
                       </td>
                       </tr>
                      
						<tr>
                         <th><?php _e('Select position','facebookall');?></th>
                          <td>
                    <input type="checkbox" name="fball_settings[share_top]" value="1"<?php echo isset($fball_settings['share_top']) && $fball_settings['share_top'] == 1 ? 'checked' : '' ?> /> <?php _e('Show at top of the content','facebookall');?> <br />                   
					<input type="checkbox" name="fball_settings[share_bottom]" value="1"<?php echo isset($fball_settings['share_bottom']) && $fball_settings['share_bottom'] == 1 ? 'checked' : '' ?> /> <?php _e('Show at bottom of the content','facebookall');?>
						 </td>
                        </tr>
                       
					     <tr>
                         <th><?php _e('Select Area For Display Share','facebookall');?></th>
                          <td>
                         <input type="checkbox" name="fball_settings[share_home]" value="1"<?php echo isset($fball_settings['share_home']) && $fball_settings['share_home'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Home Page','facebookall');?> <br /> 
					     <input type="checkbox" name="fball_settings[share_posts]" value="1" <?php echo isset($fball_settings['share_posts']) && $fball_settings['share_posts'] == 1 ? 'checked' : '' ?>/> <?php _e('Show On Posts','facebookall');?><br />
                         <input type="checkbox" name="fball_settings[share_pages]" value="1"<?php echo isset($fball_settings['share_pages']) && $fball_settings['share_pages'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Pages','facebookall');?> <br /> 
					     <input type="checkbox" name="fball_settings[share_postexcerpts]" value="1"<?php echo isset($fball_settings['share_postexcerpts']) && $fball_settings['share_postexcerpts'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Post Excerpts','facebookall');?> <br />
                         <input type="checkbox" name="fball_settings[share_archives]" value="1"<?php echo isset($fball_settings['share_archives']) && $fball_settings['share_archives'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Archive Pages','facebookall');?>  <br /> 
					     <input type="checkbox" name="fball_settings[share_feed]" value="1"<?php echo isset($fball_settings['share_feed']) && $fball_settings['share_feed'] == 1 ? 'checked' : '' ?> /> <?php _e('Show On Feed','facebookall');?>

						 </td>
                        </tr>
                        
                         </table>
                        </div>
					<!-- comment settings ends-->
					<!-- Fanbox settings -->
						<div id="tabs-4">
                        <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Facebook All Fanbox Settings','facebookall');?></th>
                         </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Facebook Page Url','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[fanbox_pageurl]" value="<?php echo (isset($fball_settings['fanbox_pageurl']) ? htmlspecialchars ($fball_settings['fanbox_pageurl']) : ''); ?>"/></td>
                       </tr>
                          <tr >
                        <th scope="fballrow"><?php _e('Fanbox Width','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[fanbox_width]" value="<?php echo (isset($fball_settings['fanbox_width']) ? htmlspecialchars ($fball_settings['fanbox_width']) : ''); ?>"/></td>
                       </tr>
					   <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('Fanbox Height','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[fanbox_height]" value="<?php echo (isset($fball_settings['fanbox_height']) ? htmlspecialchars ($fball_settings['fanbox_height']) : ''); ?>"/></td>
                       </tr>
                      <tr class="fballrow_white">
                         <th><?php _e('Color Scheme','facebookall');?></th>
                          <td>
						  <?php $fanboxlight = "";
                               $fanboxdark = "";
                               if($fball_settings["fanbox_color"] == "1") $fanboxlight = "checked='checked'";
                               elseif($fball_settings["fanbox_color"] == "0") $fanboxdark = "checked='checked'";
                               else $fanboxlight = "checked='checked'";?>
                         <input name="fball_settings[fanbox_color]" type="radio" <?php echo $fanboxlight;?>value="1" />&nbsp;&nbsp;<?php _e('Light','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[fanbox_color]" type="radio" <?php echo $fanboxdark;?>value="0" />&nbsp;&nbsp;<?php _e('Dark','facebookall');?>
						 </td>
                        </tr>
						<tr>
                         <th><?php _e('Show Faces','facebookall');?></th>
                          <td>
						  <?php $showfaces = "";
                               $noshowfaces = "";
                               if($fball_settings["fanbox_faces"] == "1") $showfaces = "checked='checked'";
                               elseif($fball_settings["fanbox_faces"] == "0") $noshowfaces = "checked='checked'";
                               else $showfaces = "checked='checked'";?>
                         <input name="fball_settings[fanbox_faces]" type="radio" <?php echo $showfaces;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[fanbox_faces]" type="radio" <?php echo $noshowfaces;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
                         <tr class="fballrow_white">
                         <th><?php _e('Show Stream','facebookall');?></th>
                          <td>
						   <?php $showstram = "";
                               $noshowstram = "";
                               if($fball_settings["fanbox_stram"] == "1") $showstram = "checked='checked'";
                               elseif($fball_settings["fanbox_stram"] == "0") $noshowstram = "checked='checked'";
                               else $noshowstram = "checked='checked'";?>
                         <input name="fball_settings[fanbox_stram]" type="radio" <?php echo $showstram;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[fanbox_stram]" type="radio" <?php echo $noshowstram;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
                          <tr class="fballrow_white">
                         <th><?php _e('Show Header','facebookall');?></th>
                          <td>
						   <?php $showheader = "";
                               $noshowheader = "";
                               if($fball_settings["fanbox_header"] == "1") $showheader = "checked='checked'";
                               elseif($fball_settings["fanbox_header"] == "0") $noshowheader = "checked='checked'";
                               else $noshowheader = "checked='checked'";?>
                         <input name="fball_settings[fanbox_header]" type="radio" <?php echo $showheader;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[fanbox_header]" type="radio" <?php echo $noshowheader;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?>
						 </td>
                        </tr>
                         </table>
						 
						 <table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Facebook All Facepile Settings','facebookall');?></th>
                         </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Facebook Page Url','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[facepile_pageurl]" value="<?php echo (isset($fball_settings['facepile_pageurl']) ? htmlspecialchars ($fball_settings['facepile_pageurl']) : ''); ?>"/></td>
                       </tr>
                          <tr >
                        <th scope="fballrow"><?php _e('Facebile Box Width','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[facepile_width]" value="<?php echo (isset($fball_settings['facepile_width']) ? htmlspecialchars ($fball_settings['facepile_width']) : ''); ?>"/></td>
                       </tr>
					   <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('Num Rows To Display','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[facepile_numrows]" value="<?php echo (isset($fball_settings['facepile_numrows']) ? htmlspecialchars ($fball_settings['facepile_numrows']) : ''); ?>"/></td>
                       </tr>
                      <tr class="fballrow_white">
                         <th><?php _e('Color Scheme','facebookall');?></th>
                          <td>
						  <?php $faceplielight = "";
                               $facepliedark = "";
                               if($fball_settings["facepile_color"] == "1") $faceplielight = "checked='checked'";
                               elseif($fball_settings["facepile_color"] == "0") $facepliedark = "checked='checked'";
                               else $faceplielight = "checked='checked'";?>
                         <input name="fball_settings[facepile_color]" type="radio" <?php echo $faceplielight;?>value="1" />&nbsp;&nbsp;<?php _e('Light','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[facepile_color]" type="radio" <?php echo $facepliedark;?>value="0" />&nbsp;&nbsp;<?php _e('Dark','facebookall');?>
						 </td>
                        </tr>
						<tr>
                         <th><?php _e('Size','facebookall');?></th>
                          <td>
						  <?php $facepliesmall = "";
                                $facepliemedium = "";
							    $faceplielarge = "";
                               if($fball_settings["facepile_size"] == "0") $facepliesmall = "checked='checked'";
                               elseif($fball_settings["facepile_size"] == "1") $facepliemedium = "checked='checked'";
							   elseif($fball_settings["facepile_size"] == "2") $faceplielarge = "checked='checked'";
                               else $facepliemedium = "checked='checked'";?>
                         <input name="fball_settings[facepile_size]" type="radio" <?php echo $facepliesmall;?>value="0" />&nbsp;&nbsp;<?php _e('Small','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[facepile_size]" type="radio" <?php echo $facepliemedium;?>value="1" />&nbsp;&nbsp;<?php _e('Medium','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 <input name="fball_settings[facepile_size]" type="radio" <?php echo $faceplielarge;?>value="2" />&nbsp;&nbsp;<?php _e('Large','facebookall');?>
						 </td>
                        </tr>
                        </table>
					</div>
					<!-- Fanbox settings ends-->
					
					<!-- Bar settings -->
						<div id="tabs-5">
						<table class="form-table facebookall_table">
                         <tr>
                         <th class="head" colspan="2"><?php _e('Facebook All Recommendations Bar Settings','facebookall');?></th>
                         </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Enable Recommendations Bar','facebookall');?></th>
						 <?php $yesenable_recbar = "";
                               $noenable_recbar = "";
                               if($fball_settings["enable_recbar"] == "1") $yesenable_recbar = "checked='checked'";
                               elseif($fball_settings["enable_recbar"] == "0") $noenable_recbar = "checked='checked'";
                               else $noenable_recbar = "checked='checked'";?>
                         <td><input name="fball_settings[enable_recbar]" type="radio" <?php echo $yesenable_recbar;?>value="1" />&nbsp;&nbsp;<?php _e('Yes','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[enable_recbar]" type="radio" <?php echo $noenable_recbar;?>value="0" />&nbsp;&nbsp;<?php _e('No','facebookall');?></td>
                       </tr>
						 <tr >
                        <th scope="fballrow"><?php _e('Domain','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[recbar_pageurl]" value="<?php echo (isset($fball_settings['recbar_pageurl']) ? htmlspecialchars ($fball_settings['recbar_pageurl']) : ''); ?>"/></td>
                       </tr>
                          <tr >
                        <th scope="fballrow"><?php _e('Read Time','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[recbar_readtime]" value="<?php echo (isset($fball_settings['recbar_readtime']) ? htmlspecialchars ($fball_settings['recbar_readtime']) : '30'); ?>"/></td>
                       </tr>
					   <tr class="fballrow_white">
                        <th scope="fballrow"><?php _e('App Id','facebookall');?></th>
                         <td><input size="90" type="text" name="fball_settings[recbar_appid]" value="<?php echo (isset($fball_settings['recbar_appid']) ? htmlspecialchars ($fball_settings['recbar_appid']) : ''); ?>"/></td>
                       </tr>
                      <tr class="fballrow_white">
                         <th><?php _e('Verb To Display','facebookall');?></th>
                          <td>
						  <?php $like = "";
                               $recommend = "";
                               if($fball_settings["recbar_verb"] == "1") $like = "checked='checked'";
                               elseif($fball_settings["recbar_verb"] == "0") $recommend = "checked='checked'";
                               else $like = "checked='checked'";?>
                         <input name="fball_settings[recbar_verb]" type="radio" <?php echo $like;?>value="1" />&nbsp;&nbsp;<?php _e('like','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[recbar_verb]" type="radio" <?php echo $recommend;?>value="0" />&nbsp;&nbsp;<?php _e('recommend','facebookall');?>
						 </td>
                        </tr>
						<tr>
                         <th><?php _e('Side','facebookall');?></th>
                          <td>
						  <?php $left = "";
                                $right = "";
							   if($fball_settings["recbar_side"] == "1") $left = "checked='checked'";
                               elseif($fball_settings["recbar_side"] == "0") $right = "checked='checked'";
							   else $left = "checked='checked'";?>
                         <input name="fball_settings[recbar_side]" type="radio" <?php echo $left;?>value="1" />&nbsp;&nbsp;<?php _e('left','facebookall');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input name="fball_settings[recbar_side]" type="radio" <?php echo $right;?>value="0" />&nbsp;&nbsp;<?php _e('right','facebookall');?> 					 </td>
                        </tr>
                        </table>
                        </div>
					<!-- Bar settings ends-->
					<p class="submit">
		<input type="submit" name="save" class="button-primary" value="<?php _e("Save Changes", 'facebookall'); ?>" />   			
		</p>
		</form>
		  </div>
		  <div style="width:27%; float:left; margin-left:15px;">
		  <div>
           <div class="fballwelcome-panel" style="margin:0; padding:5px; line-height: 24px;">
		   
		  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="XLZSDLXWGA5DA">
<table>
<tr><td><input type="hidden" name="on0" value="if this extension useful to you. would you buy me a coffee/beer"><?php _e('If this plugin is useful to you and for help to improve would you buy me a coffee/beer.', 'facebookall'); ?></td></tr><tr><td><select name="os0">
	<option value="Coffee">Coffee $5.00 USD</option>
	<option value="Special coffee">Special coffee $10.00 USD</option>
	<option value="Beer">Beer $20.00 USD</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="<?php echo FACEBOOKALL_PLUGIN_URL;?>/assets/img/paypal-buyme.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>

        </div>
      </div>
    <div>
       <div class="fballwelcome-panel" style="margin-top:12px; padding:5px; line-height:24px;">
	   <table><tr><td style="border-bottom: 1px solid #dfdfdf;">
           <b><?php _e('Your App Statistics:', 'facebookall'); ?></b></td></tr>
             <?php if (!empty($app_id)) {?>
               <tr><td><?php _e('Active Monthly Users:', 'facebookall'); ?> <?php echo $app_month_users;?></td></tr>
               <tr><td><?php _e('Active Weekly Users:', 'facebookall'); ?> <?php echo $app_weak_users;?></td></tr>
              <tr><td><?php _e('Active Daily Users:', 'facebookall'); ?> <?php echo $app_daily_users;?></td></tr>
               <tr><td><?php _e('For more information about your statistics', 'facebookall'); ?> <a target="_BLANK" href="http://www.facebook.com/insights/?sk=ao_<?php echo $app_id;?>"><?php _e('Visit Facebook Insights', 'facebookall'); ?></a></td></tr>
			 <?php } else {?>
               <tr><td> <p style="margin:0 0 5px 0; color:#FF0000;"><?php printf (__ ('Not get any configured app info for your site. You have not saved api key and secret still or please %s and make sure cURL/FSOCKOPEN is enabled in your php.ini', 'facebookall'),'<a href="http://developers.facebook.com/" target="_blank">configure facebook app</a>');?></p></td></tr>
             <?php }?></table>
        </div>
     </div>
    </div>
   </div>
</div>
<?php }?>