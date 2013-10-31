<?php
/*
Plugin Name: Facebook Friends Inviter
Plugin URI: http://journalxtra.com/websiteadvice/wordpress/plugins-wordpress/wordpress-invite-facebook-friends-button-plugin-5405/
Description: Add a Facebook friends inviter button to your site. Your visitors will be able to click the button and select friends to invite to your site with a Facebook popup message window. Use text links or buttons to open the friends inviter popup.
Version: 1.0.5
Author: Lee Hodson
Author URI: http://vizred.com/

---------------------------------------------------------------------------

Copyright 2013  Lee Hodson  (email : leehodson@vizred.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

---------------------------------------------------------------------------

*/
?>
<?php
function facebookinviter_style_fn()  
{  
    wp_register_style( 'facebookinviter-style', plugins_url( '/facebookinviter-style.css', __FILE__ ), array(), '20130308', 'all' );  
    wp_enqueue_style( 'facebookinviter-style' );
}

add_action( 'wp_enqueue_scripts', 'facebookinviter_style_fn' );

class facebookinviter_widget_class extends WP_Widget {
	function facebookinviter_widget_class() {
	 //Load Language
	 load_plugin_textdomain( 'facebookinviter-plugin-handle', false, dirname(plugin_basename(__FILE__)) .  '/lang' );
	 $widget_ops = array('description' => __('Shows Facebook invite button.', 'facebookinviter-plugin-handle') );
	 //Create widget
	 $this->WP_Widget('facebookinviter', __('Facebook Inviter', 'facebookinviter-plugin-handle'), $widget_ops);
	}


// Widget output

  function widget($args, $instance) {


	 		extract($args, EXTR_SKIP);
			echo $before_widget;
			$title = empty($instance['title']) ? __('Invite Friends', 'facebookinviter-plugin-handle') : apply_filters('widget_title', $instance['title']);
			$parameters = array(
				'title' => html_entity_decode($instance['title']), // Text
				'showtitle' => (bool) $instance['showtitle'], // Boolean
				'fbtitle' => html_entity_decode($instance['fbtitle']), // Text
				'message' => html_entity_decode($instance['message']), // Text
				'appid' => esc_html($instance['appid']), // Integer
				'buttontext' => html_entity_decode($instance['buttontext']), // Text
				'image' => esc_url($instance['image']), // URL
				'useimage' => (bool) $instance['useimage'], // Boolean
				'awidth' => esc_html($instance['awidth']), // Text
				'align' => esc_attr($instance['align']), // Text
				'unit' => esc_attr($instance['unit']), // Text
			);

			$showtitle = (bool) $instance['showtitle'];
			if ( $showtitle == '0' ) { $title=''; }

			if ( !empty( $title ) ) {
	 		echo $before_title . $title . $after_title;
			};

			// Call function that does the work

				facebookinviter($parameters);

			// End Work

			echo $after_widget;


  }

// End of widget output

	
//Update widget options
  function update($new_instance, $old_instance) {

		$instance = $old_instance;
		//get old variables
		$instance['title'] = htmlspecialchars($new_instance['title']);
		$instance['showtitle'] = $new_instance['showtitle'] ? 1 : 0;
		$instance['fbtitle'] = htmlspecialchars($new_instance['fbtitle']);
		$instance['message'] = htmlspecialchars($new_instance['message']);
		$instance['appid'] = esc_html($new_instance['appid']);
		$instance['buttontext'] = htmlspecialchars($new_instance['buttontext']);
		$instance['image'] = esc_url($new_instance['image']);
		$instance['useimage'] = $new_instance['useimage'] ? 1 : 0;
		$instance['awidth'] = esc_html($new_instance['awidth']);
		$instance['align'] = esc_attr($new_instance['align']);
		$instance['unit'] = esc_attr($new_instance['unit']);
		return $instance;
  } //end of update
	
//Widget options form
  function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('FacebookInviter','facebookinviter-plugin-handle'), 'title' => 'Invite Friends', 'fbtitle' => 'Invite Friends', 'message' => 'Invite Friends',  'appid' => 'App ID', 'showtitle'=>'0', 'buttontext' => '', 'image' => '', 'useimage'=>'0', 'awidth'=>'0', 'align'=>'center', 'unit'=>'px' ) );
	
		$title = html_entity_decode($instance['title']);
		$showtitle = (bool) $instance['showtitle'];
		$fbtitle = html_entity_decode($instance['fbtitle']);
		$message = html_entity_decode($instance['message']);
		$appid = esc_html($instance['appid']);
		$buttontext = html_entity_decode($instance['buttontext']);
		$image = esc_url($instance['image']);
		$useimage = (bool) $instance['useimage'];
		$awidth = esc_html($instance['awidth']);
		$align = esc_attr($instance['align']);
		$unit = esc_attr($instance['unit']);

// Helpful URLs

		$helpurl = esc_url('http://journalxtra.com/websiteadvice/wordpress/plugins-wordpress/wordpress-invite-facebook-friends-button-plugin-5405/');
		$rateurl = esc_url('http://wordpress.org/extend/plugins/facebook-friends-inviter/');
		$donateurl = esc_url('https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=lee@wpservicemasters.com&currency_code=USD&amount=&item_name=Donation%20to%20JournalXtra&return=http://journalxtra.com/thank-you/&notify_url=&cbt=Thank%20you%20for%20your%20donation,%20it%20is%20greatly%20appreciated&page_style=');

// Widget Settings Form

		?>


		<p>
			<a href="<?php echo $helpurl; ?>" target="_blank">Help</a> | <a href="<?php echo $rateurl; ?>" target="_blank">Rate</a> | <a href="<?php echo $donateurl; ?>" target="_blank">Donate</a>
		</p>

		<p>
		   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('<strong>Widget Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showtitle'); ?>"><?php _e('Show widget title?', 'facebookinviter-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showtitle'); ?>" name="<?php echo $this->get_field_name('showtitle'); ?>"<?php checked( $showtitle ); ?> />
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('fbtitle'); ?>"><?php _e('<strong>Dialogue Box Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('fbtitle'); ?>" name="<?php echo $this->get_field_name('fbtitle'); ?>" type="text" value="<?php echo $fbtitle; ?>" />
		   </label>
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('<strong>Message:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
		   </label>
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('appid'); ?>"><?php _e('<strong>Facebook App ID:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('appid'); ?>" name="<?php echo $this->get_field_name('appid'); ?>" type="text" value="<?php echo $appid; ?>" />
		   </label>
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('buttontext'); ?>"><?php _e('<strong>Button Text: </strong><small><br />(leave empty to use button image)</small>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buttontext'); ?>" name="<?php echo $this->get_field_name('buttontext'); ?>" type="text" value="<?php echo $buttontext; ?>" />
		   </label>
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('<strong>Button Image: </strong><small><br />(leave empty to use default image)</small>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('useimage'); ?>"><?php _e('<strong>Force image use?</strong>', 'facebookinviter-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('useimage'); ?>" name="<?php echo $this->get_field_name('useimage'); ?>"<?php checked( $useimage ); ?> />
		</p>

		<p>
		   <label for="<?php echo $this->get_field_id('awidth'); ?>"><?php _e('<strong>Button Width (px):</strong><small><br />(set to 0 for automatic width)</small>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('awidth'); ?>" name="<?php echo $this->get_field_name('awidth'); ?>" type="text" style="width:100px;" value="<?php echo $awidth; ?>" />
		   </label>
		  <select class="select" id="<?php echo $this->get_field_id('unit'); ?>" name="<?php echo $this->get_field_name('unit'); ?>" selected="<?php echo $align; ?>">
		    <option value="<?php echo $unit ?>" selected="<?php echo $unit; ?>"><?php echo $unit; ?></option>
		    <option value="px">px</option>
		    <option value="%">%</option>
		  </select>
		</p>

		<p>
		  <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('<strong>Alignment:</strong>', 'facebookinviter-plugin-handle');?></label>
		  <select class="select" id="<?php echo $this->get_field_id('align'); ?>" name="<?php echo $this->get_field_name('align'); ?>" selected="<?php echo $align; ?>">
		    <option value="<?php echo $align ?>" selected="<?php echo $align; ?>"><?php echo $align; ?></option>
		    <option value="left">Left</option>
		    <option value="center">Center</option>
		    <option value="right">Right</option>
		  </select>
		</p>



		<p>
			<a href="<?php echo $helpurl; ?>" target="_blank">Help</a> | <a href="<?php echo $rateurl; ?>" target="_blank">Rate</a> | <a href="<?php echo $donateurl; ?>" target="_blank">Donate</a>
		</p>


   <?php
  } //end of form
}

add_action( 'widgets_init', create_function('', 'return register_widget("facebookinviter_widget_class");') );
//Register Widget


// Code for the widget's output
 function facebookinviter($args = '') {
  global $wpdb;
	$defaults = array( 'title' => 'Invite Friends', 'fbtitle' => 'Invite Friends', 'message' => 'Invite Friends', 'appid' => 'App ID', 'showtitle'=>'0', 'buttontext' => 'Button Text', 'image' => '', 'useimage'=>'0', 'awidth'=>'0', 'align'=>'center' );
	$args = wp_parse_args( $args, $defaults );
	extract($args);

		$stitle = (bool) $showtitle;
		$fbtitle = $fbtitle;	
		$message = $message;
		$appid = $appid;
		$buttontext = $buttontext;
		$image = $image;
		$useimage = (bool) $useimage;
		$plugind = plugins_url( '/', __FILE__ );
		$fbi=$plugind.'img/facebook_invite.jpg';
		$fbi2=$plugind.'img/facebook.png';
		if ($image==''){$image=$fbi;}
		$style='';
		if ($awidth=='0' || $awidth=='0px' || $awidth=='0%') { $awidth='width:auto;';} else {$awidth = 'width:'.$awidth.$unit.';'; }
		$align = 'text-align:'.$align.';';
?>

	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script>
	FB.init({
	appId:'<?php echo $appid; ?>',
	cookie:true,
	status:true,
	frictionlessRequests:true,
	xfbml:true
	});
	function FacebookInviteFriends()
	{
	FB.ui({
	method: 'apprequests',
	title: '<?php echo $fbtitle; ?>',
	message: '<?php echo $message; ?>'
	});
	}
	</script>
	<div id="fib-container" style="<?php echo $align; ?>">
	<span class="fbfriendsinviter">

	    <?php if( $buttontext=='' && $useimage=='0' ) {

		echo '<a class="fiblinkimage" href="#" onclick="FacebookInviteFriends();" title="'.$message.'"><img src="'.$fbi.'" alt="'.$message.'" style="'.$awidth.'"></a>';

	    }

	    elseif( $useimage=='1' ) {

		echo '<a class="fiblinkimage" href="#" onclick="FacebookInviteFriends();" title="'.$message.'"><img src="'.$image.'" alt="'.$message.'" style="'.$awidth.'"></a>';

	    }

	    else

	    {
		echo '<a class="fiblink" style="'.$awidth.'background: url('.$fbi2.') left center no-repeat;" href="#" onclick="FacebookInviteFriends();" title="'.$message.'">'.$buttontext.'</a>';
	    }

	    ?>

	</span>
	</div>
	<script type='text/javascript'>
	if (top.location!= self.location)
	{
	top.location = self.location
	}
	</script>


<?php

 } // End code for the widget's output




// Do Invite Shortcode

  function fibbutton($atts) {

	 	// Set shortcode variable defaults


		    extract(shortcode_atts(array(
			    'title' => 'Invite Friends',
			    'message' => 'Invite Friends',
			    'text' => '',
			    'image' => '',
			    'appid' => '0',
			    'width' => '',
			    'align' => 'center',
		    ), $atts));


	 	// Sanitize


		    $sctitle=htmlspecialchars($title);
		    $scmessage=htmlspecialchars($message);
		    $sctext=htmlspecialchars($text);
		    $scimage=esc_url($image);
		    $scappid=esc_html($appid);
		    $scwidth='width:'.esc_attr($width).';';
		    $scalign='text-align:'.esc_attr($align).';';
		    $scplugind = plugins_url( '/', __FILE__ );
		    $scfbi=$scplugind.'img/facebook_invite.jpg';
		    $scfbi2=$scplugind.'img/facebook.png';
		    if($scimage==''){$scimage=$scfbi;}

?>

		    <script src="http://connect.facebook.net/en_US/all.js"></script>
		    <script>
		    FB.init({
		    appId:'<?php echo $scappid; ?>',
		    cookie:true,
		    status:true,
		    frictionlessRequests:true,
		    xfbml:true
		    });
		    function SCFacebookInviteFriends()
		    {
		    FB.ui({
		    method: 'apprequests',
		    title: '<?php echo $sctitle; ?>',
		    message: '<?php echo $scmessage; ?>'
		    });
		    }
		    </script>
		    <div id="fib-container" style="<?php echo $scalign; ?>>
		    <span class="fib">

			  <?php if( $sctext=='' && $scimage==$scfbi ) {

			      echo '<a class="fiblinkimage" href="#" onclick="SCFacebookInviteFriends();" title="'.$scmessage.'"><img src="'.$scfbi.'" alt="'.$scmessage.'" style="'.$scwidth.'"></a>';

			  }

			  elseif( $sctext=='' && $scimage!=$scfbi ) {

			      echo '<a class="fiblinkimage" href="#" onclick="SCFacebookInviteFriends();" title="'.$scmessage.'"><img src="'.$scimage.'" alt="'.$scmessage.'" style="'.$scwidth.'"></a>';

			  }

			  else

			  {
			      echo '<a class="fiblink" style="'.$scwidth.'background: url('.$scfbi2.') left center no-repeat;" href="#" onclick="SCFacebookInviteFriends();" title="'.$scmessage.'">'.$sctext.'</a>';
			  }

			  ?>

		    </span>
		    </div>
		    <script type='text/javascript'>
		    if (top.location!= self.location)
		    {
		    top.location = self.location
		    }
		    </script>

<?php

  }


// Clean shortcode() Function to return it instead of echoing it

  function cleanfib($atts){
    ob_start();
    fibbutton($atts);
    $output_menu=ob_get_contents();
    ob_end_clean();

  return $output_menu;

  }

  add_shortcode('fib', 'cleanfib');

// End Menu Shortcode

 
?>