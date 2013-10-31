<?php

// Info einzelnt
class instagram_importer_widget_info extends WP_Widget 
{
	
	public function __construct() {
		parent::__construct(
			'instagram_picture_info',
			'Instagram Picture with Infos',
			array(
				'description' => 'Choose a picture from Instagram with Infos'
			)
	    );
	}
	
function form($config)
{
?>
    <label for="<?php echo $this->get_field_id("title");  ?>">
    <p>Title:<br><input type="text"  value="<?php echo $config['title']; ?>" name="<?php  echo $this->get_field_name("title"); ?>" id="<?php  echo $this->get_field_id("title") ?>"></p>
    </label>

    <label for="<?php echo $this->get_field_id("instagram_id");  ?>">
    <p>Picture-ID:<br><input  type="text" value="<?php echo $config['instagram_id'];  ?>" name="<?php echo  $this->get_field_name("instagram_id"); ?>" id="<?php  echo $this->get_field_id("instagram_id") ?>"></p>
    </label>
    
    <label for="<?php echo $this->get_field_id("width");  ?>">
    <p>Width:<br><input type="text"  value="<?php echo $config['width']; ?>" name="<?php  echo $this->get_field_name("width"); ?>" id="<?php  echo $this->get_field_id("width") ?>"> px</p>
    </label>
    
    <label for="<?php echo $this->get_field_id("color");  ?>">
    <p>Color:<br> #<input type="text"  value="<?php echo $config['color']; ?>" name="<?php  echo $this->get_field_name("color"); ?>" id="<?php  echo $this->get_field_id("color") ?>"></p>
    </label>
    
    <label for="<?php echo $this->get_field_id("border-color");  ?>">
    <p>Border-Color:<br> #<input type="text"  value="<?php echo $config['border-color']; ?>" name="<?php  echo $this->get_field_name("border-color"); ?>" id="<?php  echo $this->get_field_id("border-color") ?>"></p>
    </label>
    
    <label for="<?php echo $this->get_field_id("background-color");  ?>">
    <p>Background-Color:<br> #<input type="text"  value="<?php echo $config['background-color']; ?>" name="<?php  echo $this->get_field_name("background-color"); ?>" id="<?php  echo $this->get_field_id("background-color") ?>"></p>
    </label>
    
    <?php
   	   if($config['link'] == "0" OR empty($config['link'])){ $result_link_output="Do Nothing"; $config['link']="0";}
			if($config['link'] == "1"){ $result_link_output="Original Page";}
			if($config['link'] == "2"){ $result_link_output="Original Page with hover-effect";}
			if($config['link'] == "3"){ $result_link_output="Direct link";}
			if($config['link'] == "4"){ $result_link_output="Direct link with hover-effect";}
    ?>
    <label for="<?php echo $this->get_field_id("link");?>">
    <p>Picture linkable:<br>
    	<select name="<?php echo  $this->get_field_name("link"); ?>" size="1">
							<option value="<?php echo $config['link']; ?>"><?php echo $result_link_output; ?></option>
    				  		<?php if($config['link'] != "0"){echo '<option value="0">Do Nothing</option>';}
      					if($config['link'] != "1"){echo '<option value="1">Original Page</option>';}
      					if($config['link'] != "2"){echo '<option value="2">Original Page with hover-effect</option>';}
      					if($config['link'] != "3"){echo '<option value="3">Direct link</option>';}
      					if($config['link'] != "4"){echo '<option value="4">Direct link with hover-effect</option>';} 
     						?>
    					</select>
    </p>
    </label>
    
    <label for="<?php echo $this->get_field_id("update");?>">
    <p>Update:<br>
    	<input type="radio" name="<?php echo  $this->get_field_name("update"); ?>" value="0" <?php if($config['update'] == "0") {echo " checked='checked'";} ?>> DB
		<input type="radio" name="<?php echo  $this->get_field_name("update"); ?>" value="1" <?php if($config['update'] == "1") {echo " checked='checked'";} ?>> Live
    </p>
    </label>
    

<?php        
}


function update($newinstance,$oldinstance)
{
    $instance = $oldinstance;

	 $instance = array();
	 $instagram = array();

    $instagram =  $newinstance['instagram_id'];
    $instagram =  $newinstance['link'];
	 $instagram =  $newinstance['update'];
	 $instagram =  $newinstance['width'];
	 $instagram =  $newinstance['border-color'];
	 $instagram =  $newinstance['background-color'];
	 $instagram =  $newinstance['color'];
    
    $instance['instagram_id'] =  $newinstance['instagram_id'];
    $instance['title'] =  $newinstance['title'];
    $instance['link'] =  $newinstance['link'];
    $instance['update'] =  $newinstance['update'];
    $instance['width'] =  $newinstance['width'];
    $instance['border-color'] =  $newinstance['border-color'];
	 $instance['background-color'] =  $newinstance['background-color'];
	 $instance['color'] =  $newinstance['color'];

    return $instance;
    return $instagram;
}
	

function widget($instance, $instagram)
{
	
	  global $wpdb;
     $table_name = $wpdb->prefix . "instagram_bilder";	

     $title = $instagram["title"];
     $id = $instagram["instagram_id"];
     $result_link = $instagram["link"];
     $result_update =  $instagram['update'];
     $result_width =  $instagram['width'];
     $result_bocolor =  $instagram['border-color'];
     $result_bacolor =  $instagram['background-color'];
     $result_color =  $instagram['color'];
     
     $before_widget = $instance["before_widget"];
     $after_widget = $instance["after_widget"];
     $before_title = $instance["before_title"];
     $after_title = $instance["after_title"];
     $widget_id = $instance["widget_id"];
     
     echo $before_widget;
     
     if(!empty($title))
     {
     	echo $before_title.''.$title.''.$after_title;	
     }

     foreach( $wpdb->get_results("SELECT * FROM $table_name WHERE id='$id'") as $key => $row) 
     {
     	 $url = $row->low_resolution;
     	 $standard_resolution = $row->standard_resolution;
		 $title = $row->text;
		 $link = $row->link;
		 
		 $title = utf8_decode($title); 	
		 
			if($result_link == "1")
			{
				$link_anfang = '<a href="'.$link.'" target="_blank">';
				$link_ende = '</a>';
			}
			if($result_link == "2")
			{
				$link_anfang = '<div class="instagram-picture-hover"><a href="'.$link.'" target="_blank">';
				$link_ende = '</a></div>';
			}
		
			if($result_link == "3")
			{
				$widget_id = str_replace("instagram_picture_individually-", "", $widget_id);
				$link_anfang = '<a href="'.$standard_resolution.'" title="'.$title.'">';
				$link_ende = '</a>';
			}
			if($result_link == "4")
			{
				$widget_id = str_replace("instagram_picture_individually-", "", $widget_id);
				$link_anfang = '<div class="instagram-picture-hover"><a href="'.$standard_resolution.'" title="'.$title.'">';
				$link_ende = '</a></div>';
			}
			
			$bild = $link_anfang.'<img src="'.$url.'" />'.$link_ende;
			
			// Update
			// DB
			if(empty($result_update) OR $result_update == "0")
			{
				$bild_like = $wpdb->get_var("SELECT pic_like FROM $table_name WHERE id='$id'");
				$bild_comments = $wpdb->get_var("SELECT pic_comment FROM $table_name WHERE id='$id'");
			
				$bild_like 		= number_format($bild_like);
				$bild_comments	= number_format($bild_comments);
			}
			// Live
			if($result_update == "1")
			{
				$table_info = $wpdb->prefix . "instagram_info";
				$result_userid = $wpdb->get_var("SELECT text FROM $table_info WHERE id='1'");
				$result_access = $wpdb->get_var("SELECT text FROM $table_info WHERE id='2'");
	
				$bild_id = $id.'_'.$result_userid;
		
				$url_curl='https://api.instagram.com/v1/media/'.$bild_id.'?access_token='.$result_access;
				$curl = curl_init();
				// Options
				curl_setopt_array($curl, array(
  				CURLOPT_RETURNTRANSFER => 1,
  				CURLOPT_URL => $url_curl,
  				CURLOPT_TIMEOUT => 4,
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
			
			// Bild Pfad fÃ¼r Comment und like
			$file = plugins_url()."/instagram-picture/img/";	
			
			// width
			if(empty($result_width))
			{
				$size="150";	
			}
			else {
					$size = $result_width;	
			}
			
			// Border-Color
			if(empty($result_bocolor))
			{
				$bocolor = "4D4D4D";
			}
			else {
				$bocolor = $result_bocolor;
			}
			
			// Background-Color
			if(empty($result_bacolor))
			{
				$bacolor = "4D4D4D";
			}
			else {
				$bacolor = $result_bacolor;
			}
			
			// Color
			if(empty($result_color))
			{
				$color = "FFFFFF";
			}
			else {
				$color = $result_color;
			}
			
			$ausgabe = '
		<!-- Instagram Picture -->
	   <!-- http://wordpress.org/plugins/instagram-picture/ -->
		<div style="margin:0 auto;border: 5px solid #'.$bocolor.';background-color:#'.$bacolor.';color:#'.$color.';width:'.$size.'px;">
			<div>
				<b>'.$title.'</b>
			</div>
			'.$bild.'
			<br />
			<div class="row-instagram">
				<div class="col-instagram-6">
					<img src="'.$file.'like.png" title="Likes" alt="Likes" style="box-shadow:none;" />
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
		<div class="instagram_clear"></div>
		<!-- Instagram Picture END -->';
		
		echo $ausgabe;
			
			
     }
     
     echo $after_widget;
     
     echo "\n\n";
     
}	
	
	}


add_action( 'widgets_init', 'load_instagram_importer_widget_info' );

function load_instagram_importer_widget_info() {
	register_widget( 'instagram_importer_widget_info' );
}

?>