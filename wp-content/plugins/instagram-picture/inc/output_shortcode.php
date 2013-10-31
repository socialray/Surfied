<?php
/*
* The file we have not yet revised
*/

function ipic_func( $atts ) {
	extract( shortcode_atts( array(
		'id' => '0', // ID zum Bild
		'size' => '0', // groesse
		'float' => '0', // Ausrichtung
		'link' => '0', // Ob Link oder nicht
		'info' => '0', // Ob mit Info
		'current' => '0', // Ob aktuell oder DB ////// live oder 1; db oder 2
		'bocolor' => '0', // border-color (nur bei Info an)
		'bacolor' => '0', // background-color (nur bei Info an)
		'color' => '0', // Color (nur bei Info an)
	), $atts ) );
	
	// String kleinschreiben
	$float = strtolower($float);
	$link = strtolower($link);
	$info = strtolower($info);
	$current = strtolower($current);
	
	// DB Connect
	global $wpdb;
	// Tabelle
   $table_bilder = $wpdb->prefix . "instagram_bilder";

	// Uepruefenen ob float angeben wurde!
	if($float != "0")
	{
		$float_ausgabe = ' float:'.$float.';';	
	}
	else 
	{
		$float_ausgabe = '';
	}

	// Uepruefenen ob ID nicht 0 ist
	// Wenn ID nicht 0 ist, dann dann versteckt sich dahinter ein Bild
	if($id != "0")
	{		
		// Uepruefenen ob size 0 ist
		// Wenn ja wurde keine groesse angegeben und das thumbnail Bild wird ausgegeben
		if($size != "0")
		{
			// Nun Uepruefenenwelche groesse
			
			// Kleiner als 150px
			// Thumbnail
			if($size <= "150")
			{
				// Datenbank Abfrage nach der ID
				$result_ausgabe = $wpdb->get_var("SELECT thumbnail FROM $table_bilder WHERE id='$id'");
		
				$bild = '<img src="'.$result_ausgabe.'" style="width:'.$size.'px;'.$float_ausgabe.'" />';
			}
			// Zwischen 150 und 306px
			// low_resolution
			if($size <= "306" AND $size >= "150")
			{
				// Datenbank Abfrage nach der ID
				$result_ausgabe = $wpdb->get_var("SELECT low_resolution FROM $table_bilder WHERE id='$id'");
		
				$bild = '<img src="'.$result_ausgabe.'" style="width:'.$size.'px;'.$float_ausgabe.'" />';
			}
			// Groesser als 306px
			// standard_resolution
			if($size >= "306")
			{
				// Datenbank Abfrage nach der ID
				$result_ausgabe = $wpdb->get_var("SELECT standard_resolution FROM $table_bilder WHERE id='$id'");
		
				$bild = '<img src="'.$result_ausgabe.'" style="width:'.$size.'px;'.$float_ausgabe.'" />';
			}
		}
		// Es wurde keine Groesse angegeben also Thumbnail
		else 
		{
		
			// Datenbank Abfrage nach der ID
			$result_ausgabe = $wpdb->get_var("SELECT thumbnail FROM $table_bilder WHERE id='$id'");
		
			$bild = '<img src="'.$result_ausgabe.'" style="'.$float_ausgabe.'" />';
		
		}
		
		if($link == "1" OR $link == "2")
		{
			if($link == "1")
			{
				$result_link = $wpdb->get_var("SELECT link FROM $table_bilder WHERE id='$id'");
			
				$ausgabe = '<div class="instagram-picture-hover"><a href="'.$result_link.'" target="_blank">'.$bild.'</a></div>';			
			}
			if($link == "2")
			{
				$result_picture = $wpdb->get_var("SELECT standard_resolution FROM $table_bilder WHERE id='$id'");
				$result_title = $wpdb->get_var("SELECT text FROM $table_bilder WHERE id='$id'");
			
				$ausgabe = '<div class="instagram-picture-hover"><a href="'.$result_picture.'" data-lightbox="lightbox_instagram_picture_shortcode" data-lightbox="roadtrip" title="'.$result_title.'">'.$bild.'</a></div>';
			}
		}
		else 
		{
			$ausgabe = $bild;
		}
	}
	
	// Ob Info an ist!
	if($info == "yes" OR $info == "1")
	{
		// Uepruefenen ob Daten live sein sollen!
		if($current == "live" OR $current == "1")
		{
			$table_info = $wpdb->prefix . "instagram_info";
			$result_userid = $wpdb->get_var("SELECT text FROM $table_info WHERE id='1'");
			$result_access = $wpdb->get_var("SELECT text FROM $table_info WHERE id='2'");
	
			$bild_id = $id.'_'.$result_userid;
		
			$url='https://api.instagram.com/v1/media/'.$bild_id.'?access_token='.$result_access;
			$curl = curl_init();
			// Options
			curl_setopt_array($curl, array(
  			CURLOPT_RETURNTRANSFER => 1,
  			CURLOPT_URL => $url,
  			CURLOPT_TIMEOUT => 5,
  			CURLOPT_SSL_VERIFYPEER => false,
 			));

			$resp = curl_exec($curl);
			curl_close($curl);



			if($resp)
			{
				$data=json_decode($resp, true);
			
				$error = $data["meta"]["code"];	
			
				if($error == "200")
				{
			
					$bild_like			= $data["data"]["likes"]["count"];
					$bild_comments	= $data["data"]["comments"]["count"];
				
					$bild_like 		= number_format($bild_like);
					$bild_comments	= number_format($bild_comments);
				}
			}
		}
		// Uepruefenen ob Daten aus DB kommen sollen!
		if($current == "DB" OR $current == "2" OR $current == "0")
		{
			$bild_like = $wpdb->get_var("SELECT pic_like FROM $table_bilder WHERE id='$id'");
			$bild_comments = $wpdb->get_var("SELECT pic_comment FROM $table_bilder WHERE id='$id'");
			
			$bild_like 		= number_format($bild_like);
			$bild_comments	= number_format($bild_comments);
		}
		
		// Title
		$result_title = $wpdb->get_var("SELECT text FROM $table_bilder WHERE id='$id'");
		
		// Fuer div Box die groesse herausfinden!
		if($size == "0")
		{
			// Groessee nicht gesetzt also Standardgroessee 150px;
			$size = "150";	
		}		
		
		// Border-Color Uepruefenen ob gesetzt
		if($bocolor == "0")
		{
			$bocolor = "4D4D4D";
		}		
		
		// Background-color Uepruefenen ob gesetzt
		if($bacolor == "0")
		{
			$bacolor = "4D4D4D";
		}		
		
		// Color Uepruefenen ob gesetzt
		if($color == "0")
		{
			$color = "FFFFFF";	
		}
		
		// Bild Pfad für Comment und like
		$file = plugins_url()."/instagram-picture/img/";	
		
		$ausgabe = '
		<!-- Instagram Picture -->
		<!-- http://wordpress.org/plugins/instagram-picture/ -->
		<div style="margin:5px;border: 5px solid #'.$bocolor.';background-color:#'.$bacolor.';color:#'.$color.';width:'.$size.'px;'.$float_ausgabe.'">
			<div>
				<b>'.$result_title.'</b>
			</div>
			'.$ausgabe.'
			<br />
			<div class="row-instagram">
				<div class="col-instagram-6">
					<img src="'.$file.'like.png" title="Likes" alt="Likes" style="box-shadow:none;" style="box-shadow:none;"/>
				</div>
				<div class="col-instagram-6">
					<b>'.$bild_like.'</b>
				</div>
			</div>
			<div class="instagram_clear"></div>
			<div class="row-instagram">
				<div class="col-instagram-6">
					<img src="'.$file.'comment.png" title="Comments" alt="Comments" style="box-shadow:none;" />
				</div>
				<div class="col-instagram-6">
					<b>'.$bild_comments.'</b>
				</div>
			</div>
			<div class="instagram_clear"></div>
		</div>
		<!-- Instagram Picture END -->
';
	
		
	}
	
	return $ausgabe;
}
add_shortcode( 'ipic', 'ipic_func' );

?>