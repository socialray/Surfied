<?php

// User Info
class instagram_importer_widget_user_info extends WP_Widget 
{
	
	public function __construct() {
		parent::__construct(
			'instagram_picture_user_info',
			'Instagram Picture User Infos',
			array(
				'description' => 'Pictures with User Infos'
			)
	    );
	}
	
function form($config)
{
?>
    <label for="<?php echo $this->get_field_id("title");  ?>">
    <p>Title:<br><input type="text"  value="<?php echo $config['title']; ?>" name="<?php  echo $this->get_field_name("title"); ?>" id="<?php  echo $this->get_field_id("title") ?>"></p>
    </label>
    
    <label for="<?php echo $this->get_field_id("update");?>">
    <p>Update:<br>
    	<input type="radio" name="<?php echo  $this->get_field_name("update"); ?>" value="0" <?php if($config['update'] == "0") {echo " checked='checked'";} ?>> DB
		<input type="radio" name="<?php echo  $this->get_field_name("update"); ?>" value="1" <?php if($config['update'] == "1") {echo " checked='checked'";} ?>> Live
    </p>
    </label>
    
     <label for="<?php echo $this->get_field_id("picture");?>">
    <p>With Picture:<br>
    	<input type="radio" name="<?php echo  $this->get_field_name("picture"); ?>" value="0" <?php if($config['picture'] == "0") {echo " checked='checked'";} ?>> No
		<input type="radio" name="<?php echo  $this->get_field_name("picture"); ?>" value="1" <?php if($config['picture'] == "1") {echo " checked='checked'";} ?>> Yes
    </p>
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
    
<?php        
}


function update($newinstance,$oldinstance)
{
    $instance = $oldinstance;

	 $instance = array();
	 $instagram = array();

	 $instagram =  $newinstance['update'];
	 $instagram =  $newinstance['picture'];
	 $instagram =  $newinstance['link'];
    
    $instance['update'] =  $newinstance['update'];
    $instance['picture'] =  $newinstance['picture'];
    $instance['link'] =  $newinstance['link'];
    $instance['title'] =  $newinstance['title'];

    return $instance;
    return $instagram;
}
	

function widget($instance, $instagram)
{
	
	  global $wpdb;
     $table_name = $wpdb->prefix . "instagram_bilder";	

	  $title = $instagram["title"];
     $result_update =  $instagram['update'];
     $result_picture =  $instagram['picture'];
     $result_link =  $instagram['link'];
     
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
     
     
     	// User Infos DB
		if(empty($result_update) OR $result_update == "0")
		{
		
			// Tabelle
  			$table_user_info = $wpdb->prefix . "instagram_user_info";
  		
  			foreach( $wpdb->get_results("SELECT * FROM $table_user_info WHERE ID ='1'") as $key => $row) 
   		{
   			$username 				= $row->username;
   			$full_name 			= $row->full_name;
   			$media 					= $row->media;
   			$followers				= $row->followed;
   			$following 				= $row->follows;
   			$profil_picture 	= $row->profil_picture;
			}
		
		}
		// User Infos live
		if($result_update == "1")
		{			
			// Tabelle
  			$table_info = $wpdb->prefix . "instagram_info";
	
			// member-id und access-token
			$instagram_id = $wpdb->get_var("SELECT text FROM $table_info WHERE id='1'");
   		$instagram_access = $wpdb->get_var("SELECT text FROM $table_info WHERE id='2'");
		
			$url='https://api.instagram.com/v1/users/'.$instagram_id.'/?access_token='.$instagram_access;
		
			// Get cURL resource
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

			// Wenn daten vorhanden sind
			if($resp)
			{
				// Mit json nun decoden, damit Wir es im Array auslesen können
				$data=json_decode($resp, true);
			
				// Error speicherung in der Variable
				$error = $data["meta"]["code"];	
			
				// Wenn Error 200 ist, dann ist alles ok!
				if($error == "200")
				{

					$username				= $data["data"]["username"];
					$profil_picture 	= $data["data"]["profile_picture"];
					$full_name 			= $data["data"]["full_name"];
					$media					= $data["data"]["counts"]["media"];
					$followers				= $data["data"]["counts"]["followed_by"];
					$following				= $data["data"]["counts"]["follows"];
				
				}
			}		
		
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
										Followers									
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
										Following									
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
			
		// Check whether with images
		if($result_picture == "1")
		{
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
	
			// Tabelle der Bilder
			$table_name_bilder = $wpdb->prefix . "instagram_bilder";	
			
			foreach( $wpdb->get_results("SELECT * FROM $table_name_bilder WHERE status ='0' ORDER BY id DESC LIMIT $limit") as $key => $row) 
   		{
   			$url = $row->low_resolution;
   			$standard_resolution 	= $row->standard_resolution;
				$link = $row->link;  
				$title = $row->text;  		
				
				$title = utf8_decode($title); 			
    	
				if($i < "10")
				{
					$i = "0$i";	
				}
		
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
    	
   			$ausgabe = str_replace("[$i]", $link_anfang.'<img src="'.$url.'" '.$border_ausgabe.'/>'.$link_ende, $ausgabe);
   			$i++;
  			}
  			
  			echo "<!-- Instagram Picture -->\n";
			echo "	<!-- http://wordpress.org/plugins/instagram-picture/ -->\n";
			
  			echo $ausgabe;
  			
  			echo "<!-- Instagram Picture END -->\n";
		}

     

	  echo $after_widget;
     
     echo "\n\n";
     
}	

// ende der class
}


add_action( 'widgets_init', 'load_instagram_importer_widget_user_info' );

function load_instagram_importer_widget_user_info() {
	register_widget( 'instagram_importer_widget_user_info' );
}

?>