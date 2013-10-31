<?php
/*
Plugin Name: BP Show Friends
Plugin URI: http://imathi.eu/2010/12/11/bp-show-friends/
Description: Displays the friends of the logged in user or of the displayed user once in BuddyPress Member area
Version: 1.2
Requires at least: 3.0
Tested up to: 3.4.2
License: GNU/GPL 2
Author: imath
Author URI: http://imathi.eu/
Network: true
*/

define ( 'BP_SF_PLUGIN_NAME', 'bp-show-friends' );
define ( 'BP_SF_PLUGIN_URL', WP_PLUGIN_URL . '/' . BP_SF_PLUGIN_NAME );
define ( 'BP_SF_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . BP_SF_PLUGIN_NAME );
define ( 'BP_SF_VERSION', '1.2' );

/**
 * bp_show_friends_register_widgets
 * register widget.
 */

function bp_show_friends_register_widgets() {
	add_action('widgets_init', create_function('', 'return register_widget("BP_Show_Friends_Widget");') );
}
add_action( 'plugins_loaded', 'bp_show_friends_register_widgets' );

class BP_Show_Friends_Widget extends WP_Widget {
	
	function bp_show_friends_widget() {
		$widget_ops = array('classname' => 'widget_show_friends', 'description' => __( "Show the friends of the loggedin user or of the displayed user if in the member area", "bp-show-friends") );
		parent::WP_Widget( false, $name = __('Friends','bp-show-friends'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $bp;
		if(bp_show_friends_is_user()){
			$user_id = $bp->displayed_user->id;
			$user_all_friends_url = $bp->displayed_user->domain . BP_FRIENDS_SLUG;
			$user_name = $bp->displayed_user->fullname;
		}
		elseif(is_user_logged_in()){
			$user_id = $bp->loggedin_user->id;
			$user_all_friends_url = $bp->loggedin_user->domain . BP_FRIENDS_SLUG;
			$user_name = $bp->loggedin_user->fullname;
		}
		?>
		<?php if(bp_show_friends_is_user() || is_user_logged_in()):?>
			<?php
			extract( $args );
			echo $before_widget;
			echo $before_title;
			if(!bp_show_friends_is_user() || $bp->loggedin_user->id == $bp->displayed_user->id) printf( __( "My Friends - <a href='%s'>All</a>", 'bp-show-friends' ), $user_all_friends_url);
			else printf( __( '%1$s&apos;s Friends - <a href="%2$s">All</a>', 'bp-show-friends' ), $user_name, $user_all_friends_url);
		    echo $after_title; ?>

			<div class="item-options" id="bpsf-list-options">
				<a href="javascript:imathSwitchToOnlineFriends('offline',<?php echo $instance['per_page'];?>)"><?php _e('Recently Actives','bp-show-friends');?></a>&nbsp;|&nbsp;
				<a href="javascript:imathSwitchToOnlineFriends('online',<?php echo $instance['per_page'];?>)"><?php _e('Online Friends','bp-show-friends');?></a>
			</div>
			<?php if(bp_show_friends_is_user()):?>
				<?php bp_show_friends_widget_list($instance['per_page']);?>
			<?php elseif(is_user_logged_in()):?>
				<?php bp_show_friends_widget_list($instance['per_page'], 1);?>
			<?php endif;?>
		<?php echo $after_widget; ?>
		<?php endif;?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['per_page'] = strip_tags( $new_instance['per_page'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'per_page' => 5 ) );
		$per_page = strip_tags( $instance['per_page'] );
		?>
		
		<p><label for="bp-show-friends-widget-per-page"><?php _e( 'Max Number of Avatars:', 'bp-show-friends' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'per_page' ); ?>" name="<?php echo $this->get_field_name( 'per_page' ); ?>" type="text" value="<?php echo attribute_escape( $per_page ); ?>" style="width: 30%" /></label></p>
		
	<?php
	}
}

/**
 * bp_show_friends_widget_list
 * send the html for the widget (by default send recently active friends)
 */

function  bp_show_friends_widget_list($limit=5, $nomember=false){
	global $bp;
	if(!$nomember) $user_id = $bp->displayed_user->id;
	else $user_id = $bp->loggedin_user->id;
	
	$args = array( 'user_id' => $user_id, 'type' =>'active', 'per_page' => $limit,'max'=> $limit, 'populate_extra' => 0);

	// plugins or themes can now order the friends differently !
	$args = apply_filters('bp_show_friends_args', $args );
	?>
	<div id="friends-container" style="padding-top:5px">
	    <?php if ( bp_has_members( $args ) ) : ?>
	      <?php while ( bp_members() ) : bp_the_member(); ?>
	        <div class="item-avatar">
	          <a href="<?php bp_member_permalink() ?>"><?php bp_member_avatar( array('class' =>'avatar bp-show-friends') ) ?></a>
	        </div>
	      <?php endwhile; ?>
	    <?php else:?>
	      <p><?php _e('No friends!','bp-show-friends')?></p>
	    <?php endif;?>
	    </div>
	    <br style="clear:both"/>
	<?php
}

/**
 * bp_show_friends_get_type_to_display
 * ajax to show online or recently active friends
 */

function bp_show_friends_get_type_to_display(){
  global $bp;
  if(bp_show_friends_is_user()) $user_id = $bp->displayed_user->id;
  elseif(is_user_logged_in()) $user_id = $bp->loggedin_user->id;
  
  //default args !
  $args = array( 'user_id' => $user_id, 'type' =>'active', 'per_page' => $limit,'max'=> $limit, 'populate_extra' => 0);

  if(isset($_POST['offoronline'])) $type = $_POST['offoronline'];
  else $type = 'offline';
  if(isset($_POST['number'])) $max = $_POST['number'];
  else $max = 5;

  if( $type != 'offline' )
	$args['type'] = 'online';

  // plugins or themes can now order the friends differently !
  $args = apply_filters('bp_show_friends_args', $args );

  if ( bp_has_members( $args ) ){
    ?>
    <?php while ( bp_members() ) : bp_the_member(); ?>
      <div class="item-avatar">
        <a href="<?php bp_member_permalink() ?>"><?php bp_member_avatar( array('class' =>'avatar bp-show-friends') ) ?></a>
      </div>
    <?php endwhile; ?>
    <?php
  }
  else{
    if($type=='online') echo '<p>'.__('No online friends!','bp-show-friends').'</p>';
      else echo '<p>'.__('No friends!','bp-show-friends').'</p>';
  }
  die();
}

function bp_show_friends_custom_js(){
  if ( bp_show_friends_is_user() || is_user_logged_in()){
    ?>
    <script type="text/javascript">
      function imathSwitchToOnlineFriends(type, max){
		jQuery("#friends-container").html('<p style="margin-top:10px;text-align:center"><img src="<?php echo BP_SF_PLUGIN_URL;?>/images/ajax-loader.gif" alt="loader"/></p>');
        var data = {
          action: 'bpsf_online_friends',
          offoronline: type,
		  number:max
        };
        jQuery.post(ajaxurl, data, function(response) {
          jQuery("#friends-container").html(response);
        });
      }
    </script>
    <?php
    }
}
add_action('wp_footer','bp_show_friends_custom_js');
add_action( 'wp_ajax_bpsf_online_friends', 'bp_show_friends_get_type_to_display');

/**
* taking care of deprecated since BP 1.5
*/
function bp_show_friends_is_user(){
	if( defined( 'BP_VERSION' ) && version_compare( BP_VERSION, '1.5-beta-1', '<' ) ){
		return bp_is_member();
	}
	else return bp_is_user();
}


/**
* Function for Translation
*/
/**
* bp_show_friends_load_textdomain
* translation!
* 
*/
function bp_show_friends_load_textdomain() {

	// try to get locale
	$locale = apply_filters( 'bp_show_friends_load_textdomain_get_locale', get_locale() );

	// if we found a locale, try to load .mo file
	if ( !empty( $locale ) ) {
		// default .mo file path
		$mofile_default = sprintf( '%s/languages/%s-%s.mo', BP_SF_PLUGIN_DIR, BP_SF_PLUGIN_NAME, $locale );
		// final filtered file path
		$mofile = apply_filters( 'bp_show_friends_load_textdomain_mofile', $mofile_default );
		// make sure file exists, and load it
		if ( file_exists( $mofile ) ) {
			load_textdomain( BP_SF_PLUGIN_NAME, $mofile );
		}
	}
}
add_action ( 'bp_init', 'bp_show_friends_load_textdomain', 2 );

/**
* bp_show_friends_activate
* store widget's version
* 
*/
function bp_show_friends_activate() {	
	//if first install
	if(!get_option('bp-show-friends-version')){
		update_option( 'bp-show-friends-version', BP_SF_VERSION );
	}
	elseif(get_option('bp-show-friends-version')!=BP_SF_VERSION){
		update_option( 'bp-show-friends-version', BP_SF_VERSION );
	}
}
register_activation_hook( __FILE__, 'bp_show_friends_activate' );
?>