<?php

function instagram_picture_php_code() {
	
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
	
	########################################################################################################################
	/*
	* PHP-Code settings
	*/
	
			echo '
			<style>
				.box
				{
					float: left;
					width: 40%;
					height: auto;
					border: 1px solid;
					padding: 10px;
					margin: 10px;
					background-color: white;
				}
				.check
				{
					background-color: red;	
				}
				.box_ausgabe
				{
					margin: 20px;
					height: auto;
					padding: 5px;
				}
				.clear
				{
					clear:both;	
				}
			</style>
			';
	
		// Check if images are present at all!
		$num_bilder = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");
	
		if($num_bilder != "0")
		{

			echo '
				<div class="instagram-picture-box">
					<h2>Setting PHP code</h2>
						<p>These settings are only for the PHP code: <code>&lt;?php instagram_header(); ?&gt;</code></p>
						<hr />
				';
	
			if(isset($_POST['link']))
			{
				$link 		= $_POST['link'];
				$title 		= $_POST['title'];	
				$radius 		= $_POST['radius'];
		
				$wpdb->query("UPDATE $instagram_picture_variable[100] Set text = '$link' WHERE id = '4'");
		
				$wpdb->query("UPDATE $instagram_picture_variable[100] Set text = '$title' WHERE id = '5'");
		
				$wpdb->query("UPDATE $instagram_picture_variable[100] Set text = '$radius' WHERE id = '6'");
				
		
			}	
	
			$result_link 			= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='4'");
			$result_title 		= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='5'");
			$result_radius 		= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='6'");
			
			// Check which ID with name (for output)
			if($result_link == "0"){ $result_link_output="Do Nothing";}
			if($result_link == "1"){ $result_link_output="Original Page";}
			if($result_link == "2"){ $result_link_output="Original Page with hover-effect";}
			if($result_link == "3"){ $result_link_output="Direct link";}
			if($result_link == "4"){ $result_link_output="Direct link with hover-effect";}
	
			// form settins
			echo '
			<div class="row-instagram_admin">
				<div class="col-instagram-3_admin instagram-picture-box-in">
				<form action="" id="instagram" method="post">
					<p><b>On Thumbnail Click:</b></p>
						<select name="link" size="1">
							<option value="'.$result_link.'">'.$result_link_output.'</option>';
    				  		if($result_link != "0"){echo '<option value="0">Do Nothing</option>';}
      					if($result_link != "1"){echo '<option value="1">Original Page</option>';}
      					if($result_link != "2"){echo '<option value="3">Original Page with hover-effect</option>';}
      					if($result_link != "3"){echo '<option value="2">Direct link</option>';}
      					if($result_link != "4"){echo '<option value="4">Direct link with hover-effect</option>';}
      	echo '
    					</select>

						<br /><br />
					<p><b>Image title?</b> (Mouseover)</p>
						<input type="radio" name="title" value="0"';if($result_title == "0") {echo " checked='checked'";}echo '> No
						<input type="radio" name="title" value="1"';if($result_title == "1") {echo " checked='checked'";}echo '> Yes
						<br /><br />
					<p><b>Border-Radius</b> (All images except the already round pictures!)</p>
						<select name="radius" size="1">
							<option value="'.$result_radius.'">'.$result_radius.' %</option>';
    				  		if($result_radius != "0"){echo '<option value="0">0 %</option>';}
      					if($result_radius != "5"){echo '<option value="5">5 %</option>';}
     						if($result_radius != "10"){echo '<option value="10">10 %</option>';}
      					if($result_radius != "15"){echo '<option value="15">15 %</option>';}
      					if($result_radius != "20"){echo '<option value="20">20 %</option>';}
      					if($result_radius != "25"){echo '<option value="25">25 %</option>';}
      					if($result_radius != "30"){echo '<option value="30">30 %</option>';}
      					if($result_radius != "35"){echo '<option value="35">35 %</option>';}
      					if($result_radius != "40"){echo '<option value="40">40 %</option>';}
      					if($result_radius != "45"){echo '<option value="45">45 %</option>';}
      					if($result_radius != "50"){echo '<option value="50">50 %</option>';}
      	echo '
    					</select>
    				<br /><br />
				<button type="submit" class="instagram-picture-success-button">Save</button>
			</form>
			</div>
		</div>
		<div class="instagram_clear_admin"></div>
			<hr />
			';
	
			// post available
			if (isset ($_POST["style"]))
			{
				$style = $_POST["style"];	
				$style = mysql_real_escape_string($style);

				$wpdb->query("UPDATE $instagram_picture_variable[100] Set text = '$style' WHERE id = '3'");
			}
	
			$style_id 			= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='3'");
			$result_border 	= $wpdb->get_var("SELECT text FROM $instagram_picture_variable[100] WHERE id='6'");

			// form styles
			echo '
			<h2>Select a Style</h2>
				<form action="" id="instagram" method="post">
			';
	
			echo '<div class="box">';	
	
			########################################################################################################################
			/*
			* optimized header angular
			*/
				echo "<h3>Optimized header angular</h3>";
				echo "<p>In the settings, but you can also make the Angular pictures around.</p>";
	
	
				$style_ende=$instagram_picture_variable['5'];

				for ($style = 1; $style < $style_ende; $style++)
				{
					if($style < "10")
					{
						$style = "0$style";	
					}
		
					$result_grid = grid($style);
	
					// HTML output
					$output=$result_grid["0"];
					// Number of images in style
					$anzahl=$result_grid["1"];
		
					$i="1";
					$limit = $anzahl;
					
					// Count Pictures
					$count_picture = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");
					
					// Compare whether there are enough pictures
					
					if($limit > $count_picture)
					{
						echo '<div class="box_ausgabe';if($style == "$style_id") {echo " check";}echo '">';
						echo '<table><tr><td>Not enough pictures</td><td></td></tr></table>';
						echo '</div><hr />';
						echo "\n";
					}
					else 
					{
						foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] ORDER BY id DESC LIMIT $limit") as $key => $row) 
   					{
   						if($i < "10")
							{
								$i = "0$i";	
							}
		
   						$url = $row->low_resolution;
   		
   						if($result_border != "0")
							{
								$border_ausgabe = 'class="instagram-radius-'.$result_border.'" ';	
							}
							else 
							{ 
								$border_ausgabe=""; 
							}
   	
   						$output = str_replace("[$i]", '<img src="'.$url.'" '.$border_ausgabe.'/>', $output);
   					
   						$i++;
  	 					}
  	 					
  	 					echo '<div class="box_ausgabe';if($style == "$style_id") {echo " check";}echo '">';
						echo '<table><tr><td>'.$output.'</td><td><input type="radio" name="style" value="'.$style.'"';if($style == "$style_id") {echo " checked='checked'";}echo '></td></tr></table>';
						echo '</div><hr />';
						echo "\n";
					
  	 				}
	
					
				}
				echo "<hr />";
				echo '</div>';	
				echo '<div class="box">';	
			########################################################################################################################	
	
			########################################################################################################################
			/*
			* optimized header angular
			*/
				echo "<h3>Optimized header round</h3>";
				echo "<p>In the settings, but you can also make the Angular pictures around.</p>";
	
	
				$style_ende=$instagram_picture_variable['6'];
				for ($style = 100; $style < $style_ende; $style++)
				{
					if($style < "10")
					{
						$style = "0$style";	
					}
		
					$result_grid = grid($style);
	
					// HTML output
					$output=$result_grid["0"];
					// Number of images in style
					$anzahl=$result_grid["1"];
		
					$i="1";
					$limit = $anzahl;
					
					// Compare whether there are enough pictures
					
					if($limit > $count_picture)
					{
						echo '<div class="box_ausgabe';if($style == "$style_id") {echo " check";}echo '">';
						echo '<table><tr><td>Not enough pictures</td><td></td></tr></table>';
						echo '</div><hr />';
						echo "\n";
					}
					else 
					{
					
						foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] ORDER BY id DESC LIMIT $limit") as $key => $row) 
   					{
   						if($i < "10")
							{
								$i = "0$i";	
							}
		
   						$url = $row->low_resolution;
   		
   						if($result_border != "0")
							{
								$border_ausgabe = 'class="instagram-radius-'.$result_border.'" ';	
							}
							else 
							{ 
								$border_ausgabe=""; 
							}
   		
   						$output = str_replace("[$i]", '<img src="'.$url.'" '.$border_ausgabe.'/>', $output);
   					
   						$i++;
  	 					}
	
						echo '<div class="box_ausgabe';if($style == "$style_id") {echo " check";}echo '">';
						echo '<table><tr><td>'.$output.'</td><td><input type="radio" name="style" value="'.$style.'"';if($style == "$style_id") {echo " checked='checked'";}echo '></td></tr></table>';
						echo '</div><hr />';
						echo "\n";
					}
				}
				
				echo '<hr />';
				echo '</div>';
				echo '<div class="clear"></div>';
			########################################################################################################################

 		 		echo '						
					<button class="instagram-picture-success-button" type="submit">Save</button>
				</form>
				<hr />
				</div>
				';

		// end when pictures are available
		}	
		else 
		{
			echo '
			<div class="instagram-picture-box">
				<h2>Setting PHP code</h2>
				<div class="instagram-picture-alert">
					<p>You need to upgrade your images first.</p><p>Just click on <b>"<a href="?page=instagram_picture_aktualisieren">Update</a>"</b></p>
				</div>
			</div>
			';
		}
	
	########################################################################################################################
	
}
?>