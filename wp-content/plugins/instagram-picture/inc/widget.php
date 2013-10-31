<?php

function instagram_picture_widget() {
	
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
		
		echo '
		<style>
			.box
			{
				width: 40%;
				float: left;
			}
			.box td
			{
				text-align:center;	
			}
			.box_margin
			{
				margin-right: 100px;
			}
		</style>
		';	
	
		// Check if images are present at all!
		$num_bilder = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");
	
		if($num_bilder != "0")
		{
			echo '
				<div class="instagram-picture-box">
					<h2>Widget Doku</h2>
					<hr />
					<div class="box box_margin">
						<h3>Settings</h3>
						<div class="row-instagram_admin">
							<div class="col-instagram-12_admin instagram-picture-box-in">
							<h4>Instagram Picture individually</h4>
							This widget allows you to highlight a picture<br/>
							<b>Title</b> - The heading<br />
							<b>Picture-ID</b> - Picture ID (IDs can be found here <a href="?page=instagram_picture_alle_bilder">All Pictures</a>)<br />
							<b>Picture linkable</b> - Linking to Instagram Page or Direct link <br />
							<b>Picture title</b> - The title as mouse-over<br />
							<b>Border-Radius</b> - percent indicating whether the image should be round.
							</div>
						</div>
						<div class="instagram_clear_admin"></div>
								<hr />
						<div class="row-instagram_admin">
							<div class="col-instagram-12_admin instagram-picture-box-in">
							<h4>Instagram Picture</h4>
							Several images with a style<br />
							<b>Title</b> - The heading<br />
							<b>Style-ID</b> - Style ID (A list is below)<br />
							<b>Picture linkable</b> - Linking to Instagram Page or Direct link <br />
							<b>Picture title</b> - The title as mouse-over<br />
							<b>Border-Radius</b> - percent indicating whether the image should be round.
							</div>
						</div>
						<div class="instagram_clear_admin"></div>
								<hr />
						<div class="row-instagram_admin">
							<div class="col-instagram-12_admin instagram-picture-box-in">
							<h4>Instagram Picture with Infos</h3>
							This widget allows you to highlight a picture with Infos<br/>
							<b>Title</b> - The heading<br />
							<b>Picture-ID</b> - Picture ID (IDs can be found here <a href="?page=instagram_picture_alle_bilder">All Pictures</a>)<br />
							<b>Picture linkable</b> - Linking to Direct link <br />
							<b>Width</b> - width of the div box<br>
							<b>bacolor</b> - Border-Color (Standard: 4D4D4D)<br />
							<b>bacolor</b> - Background-Color (Standard: 4D4D4D)<br />
							<b>color</b> - font color (Standard: FFFFFF)<br />
							<b>Update</b> - DB or live (Standard: DB)<br />
							</div>
						</div>
						<div class="instagram_clear_admin"></div>
								<hr />
						<div class="row-instagram_admin">
							<div class="col-instagram-12_admin instagram-picture-box-in">
							<h4>Instagram Picture User Infos</h4>
							<b>Update</b> - DB or live (Standard: DB)<br />
							<b>With Picture:</b> - Yes or No (Standard: No)<br />
							<b>Picture linkable</b> - Linking to Instagram Page or Direct link <br />
							(Test-Style width 250px; Picture: Yes, Link: Yes)
							<div style="width:250px;margin:0 auto;">
							';
							foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[102] WHERE ID ='1'") as $key => $row) 
   						{
   							$username 				= $row->username;
   							$full_name 			= $row->full_name;
   							$media 					= $row->media;
   							$followers				= $row->followed;
   							$following 				= $row->follows;
   							$profil_picture 	= $row->profil_picture;
							}
			// Number_format
			$media 		= number_format($media);
			$followers 	= number_format($followers);
			$following 	= number_format($following);

		
			echo '
					<div style="background-color: #1c5380;padding:2%;color:#ffffff;">
						<div class="row-instagram" style="padding: 0;margin:0;">
							<div class="col-instagram-12" style="font-size:13px;padding-bottom:2.083333333333333%;font-weight:bold;">
								<a href="http://instagram.com/'.$username.'" style="color:#ffffff;text-decoration:none;">'.$full_name.'</a>
							</div>
						</div>
						<div class="row-instagram">
							
							<div class="col-instagram-4">
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#133856;padding-top:5px;padding-bottom:5px;">
										Post									
									</div>
								</div>
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#216195;padding-top:5px;padding-bottom:5px;">
										'.$media.'									
									</div>
								</div>
								
							</div>
							
							<div class="col-instagram-4">
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#133856;padding-top:5px;padding-bottom:5px;">
										Follow									
									</div>
								</div>
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#216195;padding-top:5px;padding-bottom:5px;">
										'.$followers.'									
									</div>
								</div>
							</div>
							
							<div class="col-instagram-4">
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#133856;padding-top:5px;padding-bottom:5px;">
										Follower									
									</div>
								</div>
								<div class="row-instagram">
									<div class="col-instagram-12" style="font-size:13px;box-shadow:1px 1px #1c4b80;background-color:#216195;padding-top:5px;padding-bottom:5px;">
										'.$following.'									
									</div>
								</div>
							</div>
							
						</div>
						<div class="instagram_clear"></div>
					</div>
			';	
			
			$ausgabe = '
			<div style="background-color: #2a7cbf;padding:2.083333333333333%;">
				<div class="row-instagram">
					<div class="col-instagram-3">
						[01]
					</div>
					<div class="col-instagram-3">
						[02]
					</div>
					<div class="col-instagram-3">
						[03]
					</div>
					<div class="col-instagram-3">
						[04]
					</div>
				</div>
				<div class="row-instagram">
					<div class="col-instagram-3">
						[05]
					</div>
					<div class="col-instagram-3">
						[06]
					</div>
					<div class="col-instagram-3">
						[07]
					</div>
					<div class="col-instagram-3">
						[08]
					</div>
				</div>
				<div class="row-instagram">
					<div class="col-instagram-3">
						[09]
					</div>
					<div class="col-instagram-3">
						[10]
					</div>
					<div class="col-instagram-3">
						[11]
					</div>
					<div class="col-instagram-3">
						[12]
					</div>
				</div>
				<div class="instagram_clear"></div>
			</div>
			';
			
			$i="1";
			$limit="12";
			
			// Count Pictures
			$count_picture = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");
			
			// Compare whether there are enough pictures
					
			if($limit > $count_picture)
			{
				echo 'Not enough pictures for Picture Yes';
				echo "\n";
			}
			else 
			{
	
				// Tabelle der Bilder
				$table_name_bilder = $wpdb->prefix . "instagram_bilder";	
			
				foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] ORDER BY id DESC LIMIT $limit") as $key => $row) 
   			{
   				$url = $row->low_resolution;
					$link = $row->link;    				
    	
					if($i < "10")
					{
						$i = "0$i";	
					}
		
					$link_anfang = '<a href="'.$link.'" target="_blank"'.$title_ausgabe.'>';
					$link_ende = '</a>';
    	
   				$ausgabe = str_replace("[$i]", $link_anfang.'<img src="'.$url.'" '.$border_ausgabe.'/>'.$link_ende, $ausgabe);
   				$i++;
  				}
  			
  				echo $ausgabe;
  			
  			}
		
							echo '
							</div>
						</div>
						<div class="instagram_clear_admin"></div>
							</div>
						<hr />
					</div>
					<div class="box">
						<div style="margin: 0 auto;">
							<h3>All styles</h3>
							<p>The styles-ids for the widget</p>
						</div>
						<table style="width:300px;margin:0 auto;">
				';
	
				$style_ende=$instagram_picture_variable[7];
		
				for ($style = 200; $style < $style_ende; $style++)
				{
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
						echo '
						<tr>
							<td>Not enough pictures<hr/></td>
							<td style="width:80px;"></td>
						</tr>
						';
					}
					else 
					{
					
						foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] ORDER BY id DESC LIMIT $limit") as $key => $row) 
   					{
   						if($i < "10")
							{
								$i = "0$i";	
							}
		
   						$url = $row->thumbnail;
   	
   						$output = str_replace("[$i]", '<img src="'.$url.'" />', $output);
   						$i++;
  	 					}
						echo '
						<tr>
							<td>'.$output.'<hr/></td>
							<td style="width:80px;">Style-ID:<br><b>'.$style.'</b></td>
						</tr>
						';
			
					}
				}
	
			echo '</table></div>';
		}
		else 
		{
			echo '
			<div class="instagram-picture-box">
				<h2>Settings Widget</h2>
				<div class="instagram-picture-alert">
					<p>You need to upgrade your images first.</p><p>Just click on <b>"<a href="?page=instagram_picture_aktualisieren">Update</a>"</b></p>
				</div>
			</div>
			';
		}
	}
?>