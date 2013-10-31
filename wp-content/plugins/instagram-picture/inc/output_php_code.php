<?php
function instagram_header() {
	
	########################################################################################################################
	/* 
	*	variable definition
   */
	global $instagram_picture_variable;
	global $wpdb;
	########################################################################################################################
	
		###############
		/*
		* include grid
		*/
		require_once("grid.php");
		##############


		// Infos
		$result_style 		= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='3'");
		$result_link 			= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='4'");
		$result_title 		= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='5'");
		$result_border 		= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='6'");
	
		$result_grid = grid($result_style);
	
		// HTML output
		$output=$result_grid["0"];
		// Number of images in style
		$anzahl=$result_grid["1"];
	
		$i="1";

		$limit = $anzahl;
	
		// Output of the images (but only outputting so many pictures that are actually required)
		foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] WHERE status ='0' ORDER BY id DESC LIMIT $limit") as $key => $row) 
   	{
   		$low_resolution			= $row->low_resolution;
   		$standard_resolution 	= $row->standard_resolution;
			$title 						= $row->text;
			$link 						= $row->link;   
			
			$title = utf8_decode($title); 				
    	
			if($i < "10")
			{
				$i = "0$i";	
			}
		
			if($result_title == "1")
			{
				$title_ausgabe = ' title="'.$title.'"';	
			}
			else { $title_ausgabe=""; }
			
			// Link on; Fancybox off
			if($result_link == "1")
			{
				$link_anfang = '<a href="'.$link.'" target="_blank"'.$title_ausgabe.'>';
				$link_ende = '</a>';
			}
			if($result_link == "2")
			{
				$link_anfang = '<div class="instagram-picture-hover"><a href="'.$link.'" target="_blank"'.$title_ausgabe.'>';
				$link_ende = '</a></div>';
			}
			if($result_link == "3")
			{
					$link_anfang = '<a href="'.$standard_resolution.'" title="'.$title.'">';
					$link_ende = '</a>';
			}
			if($result_link == "4")
			{
					$link_anfang = '<div class="instagram-picture-hover"><a href="'.$standard_resolution.'" title="'.$title.'">';
					$link_ende = '</a></div>';
			}
			
			if($result_border != "0")
			{
				$border_ausgabe = ' class="instagram-radius-'.$result_border.'" ';	
			}
			else { $border_ausgabe=""; }
    	
   		$output = str_replace("[$i]", $link_anfang.'<img src="'.$low_resolution.'" '.$border_ausgabe.'/>'.$link_ende, $output);
   		$i++;
   	}
   	
   	
	echo "<!-- Instagram Picture -->\n";
	echo "	<!-- http://wordpress.org/plugins/instagram-picture/ -->\n";
	echo "		$output \n";
	echo "<!-- Instagram Picture END -->\n";

}

?>