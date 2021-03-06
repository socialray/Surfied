<?php

 
    function display_item_list() {
		add_action( 'bp_template_content', 'display_item_content' );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
	
	function display_item_content(){
		global $wpdb, $bp;
		$types = listing_types();
		$current_user = wp_get_current_user();
		$user_id = $bp->displayed_user->id;
		
		// get records
		$items = $wpdb->get_results("SELECT geo.*, wp_posts.post_title, wp_posts.post_status FROM ".$wpdb->prefix.BEPRO_LISTINGS_TABLE_NAME." as geo 
		LEFT JOIN ".$wpdb->prefix."posts as wp_posts on wp_posts.ID = geo.post_id WHERE wp_posts.post_status != 'trash' AND wp_posts.post_author = ".$user_id);
		
		$listing_url = $bp->loggedin_user->domain.$bp->current_component."/".BEPRO_LISTINGS_CREATE_SLUG."/";
		require( dirname( __FILE__ ) . '/templates/list.php' );
	}
	
    function create_listings() {
		global $wpdb, $bp;
		$current_user = wp_get_current_user();
		
		//categories
		
		$user_ID = $bp->displayed_user->id;
		if(isset($_POST["save_bepro_listing"]) && !empty($_POST["save_bepro_listing"])){
			$success = false;
			$success = bepro_listings_save();
			$current_user = wp_get_current_user();
			$bp_profile_link = bp_core_get_user_domain( $bp->displayed_user->id);
			wp_redirect($bp_profile_link  . BEPRO_LISTINGS_SLUG ."?message=".$success);
			exit;

		}elseif(isset($bp->action_variables[0]) && ($bp->current_action == BEPRO_LISTINGS_CREATE_SLUG)){
			add_action( 'bp_template_content', 'update_listing_content' );
		}else{
			add_action( 'bp_template_content', 'create_listing_content' );
		}
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
    }
	
	function create_listing_content(){
		global $bp;
		//get settings
		$data = get_option("bepro_listings");
		$default_user_id = $data["default_user_id"];
		$num_images = $data["num_images"];
		$validate = $data["validate_form"];
		$show_cost = $data["show_cost"];
		$show_con = $data["show_con"];
		$show_geo = $data["show_geo"];
		
		$listing_url = $bp->loggedin_user->domain.$bp->current_component;
		require( dirname( __FILE__ ) . '/templates/form.php' );
	}
	
	function update_listing_content(){
		global $wpdb, $bp, $post;
		//get information 
		$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.BEPRO_LISTINGS_TABLE_NAME." WHERE id = ".$bp->action_variables[0]);
		$post_data = get_post($item->post_id);
		//get categories
		$raw_categories = listing_types_by_post($item->post_id);
		$categories = array();
		if($raw_categories){
			foreach($raw_categories as $category) {
				$categories[$category->term_id] = 1;
			}
		}
		$args = array(
			'numberposts' => -1,
			'post_mime_type' => 'image',
			'post_parent' => $item->post_id,
			'post_type' => 'attachment'
		);  
		//get images
		$attachments = get_posts($args);
		$thunmbnails = array();
		if($attachments){  
			foreach ($attachments as $attachment) {
				$image = wp_get_attachment_image_src($attachment->ID,'thumbnail', false);
				$image[4] =  $attachment->ID;
				$thunmbnails[] = $image;
			}
		}

		//get settings
		$data = get_option("bepro_listings");
		$default_user_id = $data["default_user_id"];
		$num_images = $data["num_images"];
		$validate = $data["validate_form"];
		$show_cost = $data["show_cost"];
		$show_con = $data["show_con"];
		$show_geo = $data["show_geo"];
		
		$siteNo = $bp->groups->current_group->id;
		$listing_url = $bp->loggedin_user->domain.BEPRO_LISTINGS_SLUG."/";
		require( dirname( __FILE__ ) . '/templates/form.php' );
	}
 
	/**
	 * Get the current view type when the item type is 'group'
	 *
	 * @package BuddyPress Docs
	 * @since 1.0-beta
	 */
	function get_current_view(  ) {
		global $bp;

		if ( empty( $bp->action_variables[0] ) ) {
			// An empty $bp->action_variables[0] means that you're looking at a list
			$view = 'list';
		}  else if ( $bp->action_variables[0] == "create" ) {
			// Create new doc
			$view = 'create';
		}  else if ( !empty( $bp->action_variables[1] ) && $bp->action_variables[1] == "edit" ) {
			// Create new doc
			$view = 'edit';
		} 
	

		return $view;
	}
		
// Set up Cutsom BP navigation
function bepro_listings_nav() {
    global $bp;
		$settings_link = $bp->loggedin_user->domain . BEPRO_LISTINGS_SLUG. '/';

		bp_core_new_nav_item( array(
				'name' => __( BEPRO_LISTINGS_SLUG, 'buddypress' ),
				'slug' => BEPRO_LISTINGS_SLUG,
				'position' => 20,
				'default_subnav_slug' => 'List',
				'screen_function' => 'display_item_list' 
		) );
		
		bp_core_new_subnav_item( array( 'name' => __( BEPRO_LISTINGS_LIST_SLUG, 'buddypress' ), 'slug' => BEPRO_LISTINGS_LIST_SLUG, 'parent_url' => $settings_link, 'parent_slug' => BEPRO_LISTINGS_SLUG, 'screen_function' => 'display_item_list', 'position' => 10) );
		
		bp_core_new_subnav_item( array( 'name' => __( BEPRO_LISTINGS_CREATE_SLUG, 'buddypress' ), 'slug' => BEPRO_LISTINGS_CREATE_SLUG, 'parent_url' => $settings_link, 'parent_slug' => BEPRO_LISTINGS_SLUG, 'screen_function' => 'create_listings', 'position' => 90, 'user_has_access' => bp_is_my_profile() ) );


      // Change the order of menu items
      $bp->bp_nav[BEPRO_LISTINGS_SLUG]['position'] = 100;
	  bepro_listings_nav_count();
	  
}

function bepro_listings_nav_count() {
		global $bp, $wpdb;
		
		$user_id = $bp->displayed_user->id;
		// This will probably only work on BP 1.3+
		if ( !empty( $bp->bp_nav[BEPRO_LISTINGS_SLUG]) ) {
			$current_tab_name = $bp->bp_nav[BEPRO_LISTINGS_SLUG]['name'];

			$item_count = $wpdb->get_row("SELECT count(*) as item_count FROM ".$wpdb->prefix.BEPRO_LISTINGS_TABLE_NAME." as geo 
		LEFT JOIN ".$wpdb->prefix."posts as wp_posts on wp_posts.ID = geo.post_id WHERE wp_posts.post_status != 'trash' AND wp_posts.post_author = ".$user_id);

			if($item_count) $item_count = $item_count->item_count;

			$bp->bp_nav[BEPRO_LISTINGS_SLUG]['name'] = sprintf( __( '%s <span>%d</span>', BEPRO_LISTINGS_SLUG ), $current_tab_name, $item_count );
		}
	}

	


bepro_listings_nav();
?>
