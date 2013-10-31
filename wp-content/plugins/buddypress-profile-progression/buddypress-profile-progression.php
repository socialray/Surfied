<?php
/*
Plugin Name: BuddyPress Profile Progression
Plugin URI: http://wordpress.org/extend/plugins/buddypress-profile-progression
Description: Simple plugin that adds a progress bar in the member profile, which displays the percentage of datas filled.
Author: G.Breant
Version: 0.3.2
Author URI: http://pencil2d.org
License: GPL2
Text Domain: bppp
*/

class BP_Profile_Progression {
	/** Version ***************************************************************/

	/**
	 * @public string plugin version
	 */
	public $version = '0.3.2';

	/**
	 * @public string plugin DB version
	 */
	public $db_version = '020';
	
	/** Paths *****************************************************************/

	public $file = '';
	
	/**
	 * @public string Basename of the plugin directory
	 */
	public $basename = '';

	/**
	 * @public string Absolute path to the plugin directory
	 */
	public $plugin_dir = '';
        
	/**
	 * @public string Absolute path to the plugin theme directory
	 */
        public $templates_dir = '';
        
	/**
	 * @public string Prefix for the plugin
	 */
        public $prefix = '';
 
	/**
	 * @var Instance
	 */
	private static $instance;
        
        /** User ID for which we want the progression*/
        public $user_id;
        
        public $progression_points = array();
        public $current_point_item = '';


	/**
	 * Main Instance
	 *
	 * Insures that only one instance of the plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
         * 
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new BP_Profile_Progression;
			self::$instance->setup_globals();
			self::$instance->includes();
			self::$instance->setup_actions();
		}
		return self::$instance;
	}
        
	/**
	 * A dummy constructor to prevent bbPress from being loaded more than once.
	 *
	 * @since bbPress (r2464)
	 * @see bbPress::instance()
	 * @see bbpress();
	 */
	private function __construct() { /* Do nothing here */ }
        
	function setup_globals() {

		/** Paths *************************************************************/
            
		// Setup some base path and URL information
		$this->file       = __FILE__;
		$this->basename   = apply_filters( 'bppp_plugin_basenname', plugin_basename( $this->file ) );

		$this->plugin_dir = plugin_dir_path( $this->file );
		$this->plugin_url = plugin_dir_url ( $this->file );
		$this->prefix = 'bppp';
		$this->templates_dir = $this->plugin_dir . 'theme/';
 
	}
        
	function includes(){
		require( $this->plugin_dir . 'bppp-functions.php'   );
		require( $this->plugin_dir . 'bppp-profile-fields-points.php'   );
		require( $this->plugin_dir . 'bppp-template.php'   );
		require( $this->plugin_dir . 'bppp-admin.php'   );    
	}
	
	function setup_actions(){
            add_action( 'init',                         array($this, 'load_plugin_textdomain'));
            add_action( 'init',                         'bppp_admin_init');
            add_action( 'wp_enqueue_scripts',           array( $this, 'scripts_styles' ) );//scripts + styles
            add_action('bp_before_member_header_meta',  array( $this, 'member_display' ) );            
	}

        public function load_plugin_textdomain(){
            load_plugin_textdomain($this->prefix, FALSE, dirname( $this->basename ) . '/languages/');
        }
        
        function scripts_styles() {
            wp_register_style( $this->prefix.'-style', $this->plugin_url . 'style.css' );
            wp_enqueue_style( $this->prefix.'-style' );
        }

        
	/**
	 * Set up the next section and iterate current section index.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return WP_Post Next section.
	 */
	function next_point() {

		$this->query->current_point++;

		$this->query->point = $this->query->points[$this->query->current_point];
		return $this->query->point;
	}

	/**
	 * Sets up the current section.
	 *
	 * Retrieves the next section, sets up the section, sets the 'in the loop'
	 * property to true.
	 *
	 * @since 1.5.0
	 * @access public
	 * @uses $section
	 * @uses do_action_ref_array() Calls 'loop_start' if loop has just started
	 */
	function the_point() {
		$this->query->in_the_loop = true;

		if ( $this->query->current_point == -1 ) // loop has just started
			do_action_ref_array('loop_start', array(&$this));

		$point = $this->next_point();

		//setup_sectiondata($section);
	}

	/**
	 * Whether there are more sections available in the loop.
	 *
	 * Calls action 'loop_end', when the loop is complete.
	 *
	 * @since 1.5.0
	 * @access public
	 * @uses do_action_ref_array() Calls 'loop_end' if loop is ended
	 *
	 * @return bool True if sections are available, false if end of loop.
	 */
	function have_points() {

		if ( $this->query->current_point + 1 < $this->query->point_count ) {
			return true;
		} elseif ( $this->query->current_point + 1 == $this->query->point_count && $this->query->point_count > 0 ) {
			do_action_ref_array('loop_end', array(&$this));
			// Do some cleaning up after the loop
			$this->rewind_points();
		}

		$this->query->in_the_loop = false;
		return false;
	}

	/**
	 * Rewind the sections and reset section index.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	function rewind_points() {
		$this->query->current_point = -1;
		if ( $this->query->point_count > 0 ) {
			$this->query->point = $this->query->points[0];
		}
	}
        
        function member_display() {

            $auto_embed = bp_get_option( 'bppp-auto-embed', 'display-profile' );
            
            if (empty($auto_embed)) return false;

            if ((!bp_is_user_profile_edit())&&($auto_embed=='edit-profile')){
                return false;
            }
            bppp_progression_block();
   
        }
}
        
  
/**
 * The main function responsible for returning the one instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $bppp = bppp(); ?>
 *
 * @return The one Instance
 */

function bppp() {
	return BP_Profile_Progression::instance();
}

add_action( 'bp_include', 'bppp' );



?>