<?php
/*
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

	//Create map, used by shortcode and widget
	function generate_map($atts = array(), $raw_results = array()){
		global $wpdb;
		
		$echo_this = (!empty($atts))? true:false;
		extract(shortcode_atts(array(
			  'pop_up' => $wpdb->escape($_POST["num_results"]),
			  'size' => $wpdb->escape($_POST["size"]),
			  'show_paging' => $wpdb->escape($_POST["show_paging"])
		 ), $atts));
		 
		//Setup data
		$data = get_option("bepro_listings");
		$num_results = $data["num_listings"]; 
		$size = empty($size)? 1:$size;
		
		//Get Listing Results
		$findings = process_listings_results($show_paging, $num_results);				
		$raw_results = $findings[0];
		
		//Setup Listing Markers
		$counter = 0;
		foreach($raw_results as $result){
			$permalink = get_permalink( $result->post_id );
			if (!empty($result->lat) && !empty($result->lon)){
				$map_cities .= '
					position = new google.maps.LatLng('.$result->lat.','.$result->lon.');
					var marker_'.$counter.' = new google.maps.Marker({
						position: position,
						map: map,
						clickable: true,
						title: "'.$result->item_name.'",
					});
					
					markers.push(marker_'.$counter.');
					positions.push(position);	
				';
				$currlat = $result->lat;
				$currlon = $result->lon;
				$thumbnail = get_the_post_thumbnail($result->post_id, 'thumbnail'); 
				$default_img = (!empty($thumbnail))? $thumbnail:'<img src="'.$data["default_image"].'"/>';
				if($pop_up){//marker pop up 
					$map_cities .= "
					var infowindow_".$counter." = '<div class=\"marker_content\"><span class=\"marker_img\">".$default_img."</span><span class=\"marker_detais\">".$result->post_title."<br /><a href=\"http://".urlencode($result->website)."\">Visit Website</a><br /><a href=\"".get_permalink($result->post_id)."\">View Listing</a></span></div>';
						  google.maps.event.addListener(marker_".$counter.", \"click\", function() {
							infowindow.setContent(infowindow_".$counter.");
							infowindow.open(map,marker_".$counter.");
						  });
					";
				}else{
					$map_cities .= '
					var infowindow_'.$counter.' = "<div class=\"marker_content\"><span class=\"marker_detais\">'.$result->post_title.'</span></div>";
						  google.maps.event.addListener(marker_'.$counter.', "mouseover", function() {
							infowindow.setContent(infowindow_'.$counter.');
							infowindow.open(map,marker_'.$counter.');
						  });
						  google.maps.event.addListener(marker_'.$counter.', "click", function() {
							window.location.href = "'.$permalink.'";
						  });
					';
				}
			}
			$counter++;
		}
		
		//javascript initialization of the map
		$map = "<script type='text/javascript'>
			jQuery(document).ready(function(){
				var currentlat;
				var currentlon;
				markers = new Array();
				positions = new Array();
				var currentlat = $currlat;
				var currentlon = $currlon;
				
				var latlng = new google.maps.LatLng(currentlat, currentlon);
				var myOptions = {
					zoom:10,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				map = new google.maps.Map(document.getElementById('map'), myOptions);
				var infowindow = new google.maps.InfoWindow( { content: '<div class=\"marker_content\"><span class=\"marker_img\">".$default_img."</span><span class=\"marker_detais\">".$result->post_title."<br /><a href=\"http://".urlencode($result->website)."\">Visit Website</a><br /><a href=\"".get_permalink($result->post_id)."\">View Listing</a></span></div>, size: new google.maps.Size(50,50) '});
				$map_cities
				//cluster markers
				if(markers.length > 1){
					var markerCluster = new MarkerClusterer(map, markers);
					//makes sure map view fits all markers
					 latlngbounds = new google.maps.LatLngBounds();
					for ( var i = 0; i < positions.length; i++ ) {
						latlngbounds.extend( positions[ i ] );
					}
					map.fitBounds( latlngbounds );
				}
			});
		</script>
		<div id='map' class='result_map_$size'></div>";
		if($echo_this){
			echo $map;
		}else{	
			return $map;
		}
	}
	
	//Show categories Called from shortcode
	function display_listing_categories($atts = array()){
		global $wpdb;
		$no_img = plugins_url("images/no_img.jpg", __FILE__ );
		extract(shortcode_atts(array(
			  'shorten' => $wpdb->escape($_POST["shorten"]),
			  'url_input' => $wpdb->escape($_POST["url"]),
			  'type' => $wpdb->escape($_POST["type"])
		 ), $atts));
		$categories = get_terms( 'bepro_listing_types', 'orderby=count&hide_empty=0' );
		$cat_list = "<h3>".__("Categories","bepro_listings")."</h3><div class='cat_lists'>";
		
		if($categories && (count($categories) > 0)){
			foreach($categories as $cat){
				$url = "";
				$url = empty($url_input)? $url."?filter_search=1&type=".$cat->term_id:$url_input;
				$thumb_id = get_bepro_listings_term_meta( $cat->term_id, "thumbnail_id");
				$img = empty($thumb_id)? $no_img:wp_get_attachment_url( $thumb_id );
				$cat_list .= "<div class='cat_list_item'>
				<div class='cat_img'><a href='".$url."'><img src='".$img."' /></a></div>
				<div class='cat_title'><a href='".$url."'>".$cat->name."</a></div>
				<div class='cat_desc'>".$cat->description."</div>
				</div>
				";
			}
		}else{
			$cat_list .= "<div class='cat_list_no_item'>No Categories Created.</div>";
		}
		echo $cat_list."</div>";
	}
	
	
	//Show listings Called from shortcode
	function display_listings($atts = array(), $raw_results = array(), $enlarge_map = 0){
		global $wpdb;
		extract(shortcode_atts(array(
			  'shorten' => $wpdb->escape($_POST["shorten"]),
			  'type' => $wpdb->escape($_POST["type"]),
			  'show_paging' => $wpdb->escape($_POST["show_paging"])
		 ), $atts));
		 
		$data = get_option("bepro_listings");
		$num_results = $data["num_listings"]; 
		$echo_this = (!empty($raw_results))? false:true;
		
		$findings = process_listings_results($show_paging, $num_results);				
		$raw_results = $findings[0];				
			
		//Create the GUI layout for the listings
		if(empty($raw_results) || is_null($raw_results)){
			$results = "<p>your criteria returned no results.</p>";
		}else{
			foreach($raw_results as $result){
				if(empty($layout)){
					$results .= basic_listing_layout($result, $shorten, $echo_this);
				}
			}
		}
		
		if($show_paging == 1){
			$pages = 0;
			$pages = $findings[1];
			$counter = 1;
			$paging = "<div style='clear:both'><br /></div><div class='paging'>Pages: ";
			while($pages != 0){
				$paging .= "<a href='?page=".$counter."'>".$counter."</a>";
				$pages--;
				$counter++;
			}
			$paging .= "</div>";
			if($counter > 1) $results.= $paging; // if no pages then dont show this
		}
		if($echo_this){
			echo $results;
		}else{	
			return $results;
		}
	}
	
	//process paging and listings
	function process_listings_results($show_paging = false, $num_results = false){
		if(!empty($_REQUEST["filter_search"]))$returncaluse = Bepro_listings::listitems(array());
		$filter_cat = (!empty($_REQUEST["type"]))? true:false;

		
		//Handle Paging selection calculations and process listings
		if($show_paging == 1){
			$page = (empty($_GET["page"]))? 1 : $_GET["page"];
			$page = ($page - 1) * $num_results;
			$limit_clause = " ORDER BY posts.post_title ASC LIMIT $page , $num_results";
			$resvs = bepro_get_listings($returncaluse);
			$pages = ceil(count($resvs)/$num_results);
			$findings[1] = $pages;
			$raw_results = bepro_get_listings($returncaluse, $filter_cat, $limit_clause);
		}else{
			$raw_results = bepro_get_listings($returncaluse, $filter_cat);
		}
		$findings[0] = $raw_results;
		return $findings;
	}
	
	function basic_listing_layout($result, $shorten = false, $echo_this = false){
		$data = get_option("bepro_listings");
		$listing_types = listing_types_by_post($result->post_id);
		$thumbnail = get_the_post_thumbnail($result->post_id, 'thumbnail'); 
		$default_img = (!empty($thumbnail))? $thumbnail:'<img src="'.$data["default_image"].'"/>';
		
		$results .= 
		'<div class="'.(($shorten)? "shortcode_results":"results").'">
			<div class="result_top">
				<table><tr>
				<td><span class="result_name">'.$result->post_title.'</span></td>
				<td class="result_bar"><span class="result_type">'.get_the_term_list($result->post_id, 'bepro_listing_types', '', ', ','').'</span></td>
				</tr></table>
			</div>
			<div class="result_buttom">
				<span class="result_img">'.$default_img.'</span>';
		
			//if requested, hide some of the post content
			if(empty($shorten)){
				$results .='		
				<span class="result_content">';
				if($data["show_geo"])$results .= '<span class="result_title">'.$result->city.','.$result->state.','.$result->country.'</span>';
				$results .= '	
					<span class="result_desc">'.htmlspecialchars(stripslashes(strip_tags($result->post_content))).'</span>
				</span>
				';		
			}
			$permalink = get_permalink( $result->post_id );
			if($data["show_cost"]){
				if(is_numeric($result->cost)){ 
					//formats the price to have comas and dollar sign like currency.
					setlocale(LC_MONETARY, "en_US");
					$cost = ($result->cost == 0)? "Free" : money_format("%.2n", $result->cost);
				}else{
					$cost = "Please Contact";
				} 
			}
		
		$results .=  '<span class="result_do">
						<span class="result_cost">'.$cost.'</span>
						';
		$results .=((!empty($result->website))? '<span class="result_button"><a href="http://'.$result->website.'" target="_blank">Website</a></span>':"");
		
		//If not private then don't show link to listing
		if($result->post_status == "publish")
		$results .='<span class="result_button"><a href="'.$permalink.'" target="_blank">Item</a>
						</span>
					</span>';
					
		$results .=	'<div style="clear:both"><br /></div>
					</div></div>';
					
		return $results;			
	}
	
	//User form for creating Bepro Listings
	function user_create_listing($atts = array()){
		global $wpdb;
		
		extract(shortcode_atts(array(
			  'register' => $wpdb->escape($_POST["register"])
		 ), $atts));
		
		//get settings
		$data = get_option("bepro_listings");
		$default_user_id = $data["default_user_id"];
		$num_images = $data["num_images"];
		$validate = $data["validate_form"];
		$show_cost = $data["show_cost"];
		$show_con = $data["show_con"];
		$show_geo = $data["show_geo"];
		
		if(empty($default_user_id) && empty($register)){
			echo "You must provide a 'default user id' in the admin settings or use the registration=1 option.";	
			return;
		}
		
		$wp_upload_dir = wp_upload_dir();
		bepro_listings_save();
		include( dirname( __FILE__ )."/templates/form.php");
	}
	
	
?>
