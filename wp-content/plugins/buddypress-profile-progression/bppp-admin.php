<?php

/**
 * Main Curriculum Vitae Admin Class
 *
 * @package Curriculum Vitae
 * @subpackage Administration
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'BP_Profile_Progression_Admin' ) ) :
/**
 * Loads Curriculum Vitae plugin admin area
 *
 * @package Curriculum Vitae
 * @subpackage Administration
 * @since Curriculum Vitae (r2464)
 */
class BP_Profile_Progression_Admin {

	/** Functions *************************************************************/

	/**
	 * The main Curriculum Vitae admin loader
	 *
	 * @since Curriculum Vitae (r2515)
	 *
	 * @uses BBP_Admin::setup_globals() Setup the globals needed
	 * @uses BBP_Admin::includes() Include the required files
	 * @uses BBP_Admin::setup_actions() Setup the hooks and actions
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
	}

	/**
	 * Admin globals
	 *
	 * @since Curriculum Vitae (r2646)
	 * @access private
	 */
	private function setup_globals() {
	}

	/**
	 * Include required files
	 *
	 * @since Curriculum Vitae (r2646)
	 * @access private
	 */
	private function includes() {
	}
 

	/**
	 * Setup the admin hooks, actions and filters
	 *
	 * @since Curriculum Vitae (r2646)
	 * @access private
	 *
	 * @uses add_action() To add various actions
	 * @uses add_filter() To add various filters
	 */
	private function setup_actions() {
            add_action( 'bp_register_admin_settings', array( $this, 'register_admin_settings' ));

            /** Filters ***********************************************************/

            // Modify Curriculum Vitae's admin links
            add_filter( 'plugin_action_links', array( $this, 'modify_plugin_action_links' ), 10, 2 );

	}
        
        function register_admin_settings(){
            
            // Add the settings section
            add_settings_section( 'bppp',      __( 'BuddyPress Profile Progression', 'bppp' ), array( $this, 'settings_section_callback' ), 'buddypress');
            
            // Add setting field
            add_settings_field( 'bppp-auto-embed', __( 'Include Progression Bar', 'bppp' ), array( $this, 'settings_field_auto_embed_callback' ), 'buddypress', 'bppp' );
            
            //Points review
            add_settings_field( 'bppp-points-shares', __( 'Points Shares', 'bppp' ), array( $this, 'settings_field_points_shares_callback' ), 'buddypress', 'bppp' );
            
            register_setting( 'buddypress', 'bppp-auto-embed', array( $this, 'settings_field_auto_embed_sanitize' ) );

        }
        
        function settings_section_callback(){
        }

        function settings_field_auto_embed_callback(){
            
            $include_location = bp_get_option( 'bppp-auto-embed', 'display-profile' );

            ?>
            <p>
                <input name="bppp-auto-embed" type="radio" value="display-profile" <?php checked( $include_location,'display-profile' ); ?> />
                <label for="bppp-auto-embed"><?php _e( 'When displaying a profile', 'bppp' ); ?></label>
                <br/>
                <input name="bppp-auto-embed" type="radio" value="edit-profile" <?php checked( $include_location,'edit-profile' ); ?> />
                <label for="bppp-auto-embed"><?php _e( 'When editing a profile', 'bppp' ); ?></label>
                <br/>
                <input name="bppp-auto-embed" type="radio" value="0" <?php checked( empty($include_location) ); ?> />
                <label for="bppp-auto-embed"><?php _e( 'No auto embed', 'bppp' ); ?></label>
            </p>
            <p class="description">
                <?php printf(__( 'Use function %s to display the profile progression block in your templates.', 'bppp' ),'<code>bppp_progression_block()</code>'); ?>
            </p>
            <?php
        }

        
        function settings_field_points_shares_callback(){
            
            if (!bppp_has_point_items()) return false;

            $total_points = bppp_get_total_points();

            while ( bppp()->have_points() ) : bppp()->the_point();

                $current_point = bppp()->query->point;

                $percent = 0;

                if ($current_point['points'])
                    $percent = round((($current_point['points']/$total_points)*100),1);

                ?>
                <span>
                    <strong><?php echo $current_point['label'];?>: </strong>
                    <?php printf(__('%1d points (%2d%% of total)','bppp'),$current_point['points'],$percent);?>
                    — <?php printf(__('Callback used : %1s','bppp'),'<code>'.$current_point['callback'].'</code>');?>
                </span><br/>
                <?php



            endwhile;
        }
        
        
        function settings_field_auto_embed_sanitize($value=false){

            return $value;
            
        }


	/**
	 * Add Settings link to plugins area
	 *
	 * @since Curriculum Vitae (r2737)
	 *
	 * @param array $links Links array in which we would prepend our link
	 * @param string $file Current plugin basename
	 * @return array Processed links
	 */
	public static function modify_plugin_action_links( $links, $file ) {

		// Return normal links if not Curriculum Vitae
		if ( plugin_basename( bppp()->file ) != $file )
			return $links;

		// Add a few links to the existing links array
		return array_merge( $links, array(
			'settings' => '<a href="' . bppp_get_admin_options_link() . '">' . esc_html__( 'Settings', 'bppp' ) . '</a>'
		) );
	}


}
endif; // class_exists check

function bppp_get_admin_options_link(){
    $url = admin_url( 'admin.php' );
    $url = add_query_arg( array( 'page' => 'bp-settings'   ), $url );
    return $url;
}

function bppp_admin_init() {
    bppp()->admin = new BP_Profile_Progression_Admin();
}

?>
