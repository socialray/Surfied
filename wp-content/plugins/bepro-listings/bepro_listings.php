<?php
/*
Plugin Name: BePro Listings
Plugin Script: bepro_listings.php
Plugin URI: http://www.beprosoftware.com/products
Description: Bepro Listings allows you to create posts with additional information like, costs, contact, and geographic. This plugin includes the tools you need, to implement listings on any page or post.
Version: 1.2.35
License: GPL V3
Author: BePro Software Team
Author URI: http://www.beprosoftware.com


Copyright 2012 [Beyond Programs LTD.](http://www.beyondprograms.com/)

Commercial users are requested to, but not required to contribute, promotion, 
know-how, or money to plug-in development or to www.beprosoftware.com. 

This file is part of BePro Listings.

BePro Listings is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

BePro Listings is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with BePro Listings.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( !defined( 'ABSPATH' ) ) exit;

class Bepro_listings{

	/**
	 * Welcome to BePro Listings, part of the BePro Software collection.
	*/
	 
	//Start
	function __construct() {
		include(dirname( __FILE__ ) . '/bepro_listings_functions.php');
		include(dirname( __FILE__ ) . '/admin/bepro_listings_admin.php');
		include(dirname( __FILE__ ) . '/admin/bepro_listings_widgets.php');
		include(dirname( __FILE__ ) . '/bepro_listings_frontend.php');
		load_constants();
		
		add_action('init', 'create_post_type' );
		add_action('admin_init', 'bepro_admin_init' );
		add_action('admin_head', 'bepro_admin_head' );
		add_action('wp_head', 'bepro_listings_wphead', 0);
		add_action('wp_footer', 'bepro_listings_javascript');
		add_action('admin_enqueue_scripts', 'bepro_listings_adminhead');
		add_action('admin_menu', 'bepro_listings_menus');
		add_action("widgets_init", array('bepro_widgets', 'register'));
		add_action('post_updated', 'bepro_admin_save_details' );
		add_action('delete_post', 'bepro_delete_post' );
		add_action('wp_ajax_save-widget', 'bepro_save_widget' );
		add_action("manage_posts_custom_column",  "bepro_listings_custom_columns");
		add_action( "plugins_loaded",  "bepro_listings_install");
		add_action( 'bp_init', array( $this, "start_bp_addon") );
		add_action( 'wp_ajax_bepro_ajax_delete_post', 'bepro_ajax_delete_post' );
		add_action( 'wp_ajax_nopriv_bepro_ajax_delete_post', 'bepro_ajax_delete_post' );
		add_action( 'wpmu_new_blog', 'bepro_new_blog', 10, 6);   
		add_action( 'bepro_listing_types_add_form_fields', 'bepro_listings_add_category_thumbnail_field' );
		add_action( 'bepro_listing_types_edit_form_fields', 'bepro_listings_edit_category_thumbnail_field', 10,2 );
		add_action( 'created_term', 'bepro_listings_category_thumbnail_field_save', 10,3 );
		add_action( 'edit_term', 'bepro_listings_category_thumbnail_field_save', 10,3 );
		
		
		add_filter('manage_edit-bepro_listing_types_columns', 'bepro_edit_listing_types_column', 10, 3 );
		add_filter('manage_bepro_listing_types_custom_column', 'bepro_listing_types_column', 10, 3 );
		add_filter("manage_edit-bepro_listings_columns", "bepro_listings_edit_columns");
		add_filter('the_content', array( $this, 'post_page_single'));	
		
		add_shortcode("search_form", array( $this, "searchform"));
		add_shortcode("filter_form", array( $this, "search_filter_options"));
		add_shortcode("generate_map", "generate_map");
		add_shortcode("display_listings", "display_listings");
		add_shortcode("display_listing_categories", "display_listing_categories");
		add_shortcode("create_listing_form", "user_create_listing");
	}

	//Simple Search Listings Form
	function searchform($atts){
		global $wpdb;
		extract(shortcode_atts(array(
			  'listing_page' => $wpdb->escape($_POST["listing_page"])
		 ), $atts));
		
		$data = get_option("bepro_listings");
		
		$return_text = '
			<div class="search_listings">
				<form method="post" name="searchform" id="listingsearchform" action="'.get_bloginfo("url")."/".$listing_page.'">
					<input type="hidden" name="filter_search" value="1">
					<input type="hidden" name="l_type" value="'.$_POST["l_type"].'">
					<input type="hidden" name="distance" value="'.$_POST["distance"].'">
					<input type="hidden" name="min_date" value="'.$_POST["min_date"].'">
					<input type="hidden" name="max_date" value="'.$_POST["max_date"].'">
					<input type="hidden" name="min_cost" value="'.$_POST["min_cost"].'">
					<input type="hidden" name="max_cost" value="'.$_POST["max_cost"].'">
					<span class="searchleft">';	
		if($data["show_geo"] == (1||"on"))$return_text .= '<span class="searchlabel">'.__("Where", "bepro-listings").'</span><br />
						<input type="text" name="addr_search" value="'.$_POST["addr_search"].'"><br />
						';
		$return_text .=	'<span class="searchlabel">'.__("Name", "bepro-listings").'</span><br />
						<input type="text" name="name_search" id="name_search" value="'.$_POST["name_search"].'"><br />
					</span>
					<input type="submit" value="'.__("Search Listings", "bepro-listings").'">
					
				</form>
				<a class="clear_search" href="'.$_SERVER["PHP_SELF"].'"><button>Clear Search</button></a>
			</div>
		';
		
		echo $return_text;
	}

	//Process Filter Search Criteria
	function listitems($atts) {
		global $wpdb;
		extract(shortcode_atts(array(
			  'l_type' => $wpdb->escape($_REQUEST["type"]),
			  'min_cost' => $wpdb->escape($_POST["min_cost"]),
			  'max_cost' => $wpdb->escape($_POST["max_cost"]),
			  'min_date' => $wpdb->escape($_POST["min_date"]),
			  'max_date' => $wpdb->escape($_POST["max_date"]),
			  'l_name' => $wpdb->escape($_POST["name_search"]),
			  'l_city' => $wpdb->escape($_POST["addr_search"]),
			  'wp_site' => $wpdb->escape($_POST["wp_site"]),
		 ), $atts));
		 
		 $data = get_option("bepro_listings");
		 
		 //manage search fields throughout dynamic pages and functionality
		 if(!empty($l_name) || !empty($l_city)){
			$_SESSION["name_search"] = $l_name;
			$_SESSION["addr_search"] = $l_city;
		 }else if((empty($l_name) && empty($l_city)) && !empty($_GET["page"])){
			$l_name = $_SESSION["name_search"];
			$l_city = $_SESSION["addr_search"];
		 }else if(empty($l_name) && empty($l_city) && empty($_GET["page"])){
			unset($_SESSION["name_search"]);
			unset($_SESSION["addr_search"]);
		 }
		
		//Query Bepro Listing Types
		$returncaluse = "";
		 if(!empty($l_type) && (is_numeric($l_type) || is_array($l_type))){
			if(is_array($l_type))$l_type = implode(",", $l_type);
			$returncaluse  .= "AND t.term_id IN ($l_type)";
		 }		

		//Query google for lat/lon of users requested address
		$distance = (empty($_POST["distance"]))? $data["distance"]:$_POST["distance"];
		if(!empty($l_city) && isset($l_city)){ 
			//newest edits aug, 12, 2012
			$addresstofind = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&output=csv&sensor=false',rawurlencode($l_city));
			$ch = curl_init();
			$timeout = 5; 
			curl_setopt ($ch, CURLOPT_URL, $addresstofind);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$_result = curl_exec($ch);
			curl_close($ch);
			
			$_result = json_decode($_result);
			$currentlat = (string)$_result->results[0]->geometry->location->lat; 
			$currentlon = (string)$_result->results[0]->geometry->location->lng;
			 // Variables for proximity query
			 $x = $currentlat;
			 $x2 = 'geo.lat';
			 $y = $currentlon;
			 $y2 = 'geo.lon';
			 
			 if($_result){
				$returncaluse =  "AND (3958 * 3.1415926 * SQRT(({$y2} - {$y}) * ({$y2} - {$y}) + COS({$y2} / 57.29578) * COS({$y} / 57.29578) * ({$x2} - {$x}) * ({$x2} - {$x})) / 180) <= {$distance} AND geo.lat IS NOT NULL AND geo.lon IS NOT NULL";
			 }
	   }
	   
	   //Query BePro Listing Name 'LIKE' user query
	   if(!empty($l_name)){
			$listing_table_name = (!empty($wp_site) && is_numeric($wp_site) && ($wp_site > 0))?
				$wpdb->prefix.$wp_site.'_bepro_listings':$wpdb->prefix.BEPRO_LISTINGS_TABLE_NAME;
	   
			$check_avail = $wpdb->get_row("SELECT bl.* FROM ".$listing_table_name." as bl
			LEFT JOIN ".$wpdb->prefix."posts as posts ON posts.ID = bl.post_id
			WHERE post_title LIKE '%$l_name%' LIMIT 1");
			 
			if($check_avail){
				//If distance, find listings 'LIKE' user supplied request within radius
				if(!empty($_POST["distance"])){
					$x = $check_avail->lat;
					$x2 = 'geo.lat';
					$y = $check_avail->lon;
					$y2 = 'geo.lon';
					$distance_clause = "AND (3958 * 3.1415926 * SQRT(({$y2} - {$y}) * ({$y2} - {$y}) + COS({$y2} / 57.29578) * COS({$y} / 57.29578) * ({$x2} - {$x}) * ({$x2} - {$x})) / $distance) <= {$distance}";
				}
				$returncaluse .= " AND posts.post_title LIKE '%$l_name%' $distance_clause AND geo.lat IS NOT NULL AND geo.lon IS NOT NULL";
			}
	   }
	 
		//min/max cost setup 
	   if(isset($min_cost) && is_numeric($min_cost) && ($min_cost > 0)){
		$returncaluse.= " AND geo.cost > $min_cost";
	   }
	   if(isset($max_cost) && is_numeric($max_cost) && ($max_cost > 0)){
		$returncaluse.= " AND geo.cost < $max_cost";
	   }

	   //setup dates
	   if(!empty($min_date) && (is_numeric(str_replace("/","",$min_date)))){
		$returncaluse.= " AND geo.created >= '".date("y-m-d",strtotime($min_date))."'";
	   }
	   if(!empty($max_date) && (is_numeric(str_replace("/","",$max_date)))){
		$returncaluse.= " AND geo.created <= '".date("y-m-d",strtotime($max_date))."'";
	   }
		return $returncaluse;
	}

	function search_filter_options($atts = array()){
		global $wpdb;
		extract(shortcode_atts(array(
			  'listing_page' => $wpdb->escape($_POST["listing_page"])
		 ), $atts));
		$echo_this = (!empty($atts))? true:false;
		
		//get settings
		$data = get_option("bepro_listings");
		
		//Process user requested Bepro listing types 
		if(!empty($_POST["type"])){
			$l_type = $_POST["type"];
			foreach($l_type as $raw_t){
				$types[$raw_t] = 1; 
			}
		}	
		
		$search_form = "<div class='filter_search_form'>
			<form method='post' action='/".$listing_page."'>
				<input type='hidden' name='name_search' value='".$_POST["name_search"]."'>
				<input type='hidden' name='addr_search' value='".$_POST["addr_search"]."'>
				<input type='hidden' name='filter_search' value='1'>
				<table>
					<tr>
						<td>
						<span class='searchlabel'>".__("Listing Types", "bepro-listings")."</span><br />
						";
			$options = listing_types();
			foreach($options as $opt){
				$checked = (isset($types[$opt->term_id]))? "checked='checked'":"";
				$search_form .= '<input type="checkbox" name="type[]" value="'.$opt->term_id.'" '.$checked.'/><span class="searchcheckbox">'.$opt->name.'</span><br />';
			}

			$search_form .= '</td>
			</tr>';
			///////////////////////////////////////////////////////////////////////
			if($data["show_geo"] == (1||"on"))	
			$search_form .= '
				<tr><td>
					'.__("Distance", "bepro-listings").': <select name="distance">
						<option value="">None</option>
						<option value="50" '.(($_POST["distance"] == 50)? 'selected="selected"':"").'>50 miles</option>
						<option value="150" '.((($_POST["distance"] == 150) || empty($_POST["distance"]))? 'selected="selected"':"").'>150 miles</option>
						<option value="250" '.(($_POST["distance"] == 250)? 'selected="selected"':"").'>250 miles</option>
						<option value="500" '.(($_POST["distance"] == 500)? 'selected="selected"':"").'>500 miles</option>
						<option value="1000" '.(($_POST["distance"] == 1000)? 'selected="selected"':"").'>1000 miles</option>
					</select>
				</td></tr>';
				
				//min/max cost
				if($data["show_cost"] == (1||"on"))
				$search_form .= '
				<tr><td>
					<span class="label_sep">'.__("Price Range", "bepro-listings").'</span><span class="form_label">'.__("From", "bepro-listings").'</span><input class="input_text" type="text" name="min_cost" value="'.$_POST["min_cost"].'"><span class="form_label">'.__("To", "bepro-listings").'</span><input class="input_text" type="text" name="max_cost" value="'.$_POST["max_cost"].'">
				</td></tr>';
				
				$search_form .= '
				<tr><td>
					<span class="label_sep">'.__("Date Range", "bepro-listings").'</span><span class="form_label">'.__("From", "bepro-listings").'</span><input class="input_text" type="text" name="min_date" id="min_date" value="'.$_POST["min_date"].'"><span class="form_label">'.__("To", "bepro-listings").'</span><input class="input_text" type="text" name="max_date" id="max_date" value="'.$_POST["max_date"].'">
				</td></tr>
				<tr>
					<td>
						<input type="submit" class="form-submit" value="'.__("Refine Search", "bepro-listings").'" id="edit-submit" name="find">
						<a href="'.$_SERVER["PHP_SELF"].'"><button>Clear Search</button></a>
					</td>
				</tr>
			</table>
		</form></div>
		';
		if($echo_this){
			echo $search_form;
		}else{	
			return $search_form;
		}
	}
	
	//show listing on pages created for it
	function post_page_single($content){
		remove_filter( 'the_content', array( $this, 'post_page_single'));
		if(is_single() && in_the_loop() && (get_post_type() == 'bepro_listings')){
			global $current_user, $wpdb;
			//get listing information related to this post
			$page_id = get_the_ID();
			$item = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.BEPRO_LISTINGS_TABLE_NAME." WHERE post_id = ".$page_id);
			//get settings
			$data = get_option("bepro_listings");
			if($item && ($wpdb->num_rows == 1)){
				//Show wordpress gallery for this page
				echo "<div class='bepro_listing_gallery'>".do_shortcode("[gallery size='".$data["gallery_size"]."' columns=5]"."</div>");
				$post_categories = wp_get_post_categories( $page_id );
				$types = listing_types();	
				$check_types = array();
				foreach($types as $type){
					$cat = get_category( $type->category_id );
					$check_types[] = $cat->name;
				}
				
				if(is_numeric($item->cost)){
					//formats the price to have comas and dollar sign like currency.
					$cost = ($item->cost == 0)? __("Free", "bepro-listings") : money_format("%.2n", $item->cost);
				}else{
					$cost = __("Please Contact", "bepro-listings");
				} 
				if(!empty($data["show_details"]) && ($data["show_details"] == "on")){
					echo "<span class='bepro_listing_info'>
						<div class='item_cost'>".__("Cost", "bepro-listings")." - ".$cost."</div>";
						//If we have geographic data then we can show this listings address information
						if($item->lat){
							$map_url = "http://maps.google.com/maps?&z=10&q=".$item->lat."+".$item->lon."+(".urlencode($item->address_line1.", ".$item->city.", ".$item->state.", ".$item->country).")&mrt=yp ";
							echo "<div class='bepro_address_info'><span class='item_label'>".__("Address", "bepro-listings")."</span> - <a href='$map_url' target='_blank'>".__("View Map", "bepro-listings")."</a></div>";
						}
						//If there is contact information then show it
						if($item->first_name || $item->email){
							echo "<div class='item_contactinfo'>
									<span class='item_label'>".__("First Name", "bepro-listings")."</span> - ".$item->first_name."<br />
									<span class='item_label'>".__("Last Name", "bepro-listings")."</span> - ".$item->last_name."<br />
									<span class='item_label'>".__("Email", "bepro-listings")."</span> - ".$item->email."<br />
									<span class='item_label'>".__("Phone", "bepro-listings")."</span> - ".$item->phone."
								</div>";
						}
					echo "</span>";
				}
				if(!empty($data["show_content"]) && ($data["show_content"] == "on")){
					echo "<div class='bepro_listing_desc'>".get_the_content()."</div>";
				}	
			}else{
				echo the_content();
			}	
		}else{
			return $content;
		}
	}
	
	//buddypress hook
	function start_bp_addon(){
		$data = get_option("bepro_listings");
		if($data["buddypress"] == (1||"on"))
		include( dirname( __FILE__ ) . '/bepro-listings-bp.php' );
	}
}

$startup = new Bepro_listings();