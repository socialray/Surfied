<?php
/*
Plugin Name: BP Facebook Style Friend Lists 
Plugin URI: http://cityflavourmagazine.com
Description: Show Photos And Names of User's Friends Or Current Viewing Member's Friends  
Version: 1.0
Requires at least: 3.0  and BuddyPress 1.3
Tested up to: 3.6.1  and BuddyPress 1.8 
License: GNU/GPL 2
Author URI: http://cityflavourmagazine.com/
Author:Prince Abiola Ogundipe
*/

/**
 *  Make sure BuddyPress is loaded
 */
if ( !function_exists( 'bp_core_install' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'buddypress/bp-loader.php' ) )
		require_once ( WP_PLUGIN_DIR . '/buddypress/bp-loader.php' );
	else
		return;
}

/**
 * bp_facebook_style_friend_list_register_widgets
 * register widget.
 */

function bp_facebook_style_friend_list_register_widgets() {
	add_action('widgets_init', create_function('', 'return register_widget("Bp_Facebook_Style_Friend_List_Widget");') );
}
add_action( 'plugins_loaded','bp_facebook_style_friend_list_register_widgets' );

class Bp_Facebook_Style_Friend_List_Widget extends WP_Widget {
	
	function bp_facebook_style_friend_list_widget() {
		$widget_ops = array('classname' => 'widget_facebook_style_friend_list','description' => __( "Show Photos And Names of User's Friends Or Current Viewing Member's Friends", "bp-facebook-style-friend-list") );
		parent::WP_Widget( false, __('FB style friend list','bp-facebook-style-friend-list'), $widget_ops);
	}
        
    function widget($args, $instance) {
        global $bp;
        //dont show if user is not logged in or not viewing a profile
        if( !is_user_logged_in())
                $user_id=bp_loggedin_user_id ();
        
                if(bp_is_user())
                $user_id=bp_displayed_user_id ();
                if(!$user_id)
                return;
                extract( $args );
                echo $before_widget;
		echo $before_title;?>
              <?php bp_word_or_name( __( "My Friends", 'bp-facebook-style-friend-list' ), __( "Friends", 'bp-facebook-style-friend-list' ) ) ?> (<?php echo BP_Friends_Friendship::total_friend_count( $bp->displayed_user->id ) ?>)<span>
<a style="font-size:11px;color:#3B5998; float:right; margin-right:12px "href="<?php echo $bp->displayed_user->domain . $bp->friends->slug ?>"><?php _e('See All', 'bp-facebook-style-friend-list') ?></a></span>
		<?php echo $after_title;?>
        
        
        <?php if ( bp_has_members( 'type=active&max='. $instance['max_num'] . '&user_id='.bp_displayed_user_id() ) & is_user_logged_in() ) : ?>

	 <ul id="members-list" class="item-list">
	 <?php while ( bp_members() ) : bp_the_member(); ?>
         <li>
         <div class="item-avatar">
         <a href="<?php bp_member_permalink() ?>"><?php bp_member_avatar('type=full&width=50&height=50') ?></a></div>
         <div class="item">
         <div class="item-title">
	 <a style="color:#3B5998;"href="<?php bp_member_permalink() ?>"><?php bp_member_name() ?></a>          
         <div class="clear"></div></div>
	<?php endwhile; ?>
         </div>
		
	<?php else: ?>


			<div class="widget-error">
				
                                <p><?php _e( "Sorry, no friends here.", 'bp-facebook-style-friend-list' ); ?></p>
			</div>

		<?php endif; ?>
                
		<?php echo $after_widget; ?>
<?php
	}

function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['max_num'] = strip_tags( $new_instance['max_num'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'max_num' => 5 ) );
		$max_num = strip_tags( $instance['max_num'] );
		?>
		
		<p><label for="bp-facebook-style-friend-list-max-num"><?php _e( 'Max Number of Members:','bp-facebook-style-friend-list' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_num' ); ?>" name="<?php echo $this->get_field_name( 'max_num' ); ?>" type="text" value="<?php echo attribute_escape( $max_num ); ?>" style="width: 30%" /></label></p>
		
	<?php
	}
}
?>
