<?php

// einzelnt
class instagram_importer_widget_einzelnt extends WP_Widget 
{
	
	public function __construct() {
		parent::__construct(
			'instagram_picture_individually',
			'Instagram Picture individually',
			array(
				'description' => 'Choose a picture from Instagram'
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
    
    <label for="<?php echo $this->get_field_id("bild_title");?>">
    <p>Picture title:<br>
    	<input type="radio" name="<?php echo  $this->get_field_name("bild_title"); ?>" value="0" <?php if($config['bild_title'] == "0") {echo " checked='checked'";} ?>> No
		<input type="radio" name="<?php echo  $this->get_field_name("bild_title"); ?>" value="1" <?php if($config['bild_title'] == "1") {echo " checked='checked'";} ?>> Yes
    </p>
    </label>
    
    <?php
	 if($config['border-radius'] == ""){$config['border-radius']='0';}    
    ?>
    
    <label for="<?php echo $this->get_field_id("border-radius");?>">
    <p>Border-Radius:<br>
    <select name="<?php echo  $this->get_field_name("border-radius"); ?>" size="1">
    	<option value="<?php echo $config['border-radius'] ?>"><?php echo $config['border-radius'] ?> %</option>';
    	<?php 
    	if($config['border-radius'] != "0") {echo '<option value="0">0 %</option>';}
    	if($config['border-radius'] != "5") {echo '<option value="5">5 %</option>';}
    	if($config['border-radius'] != "10") {echo '<option value="10">10 %</option>';}
    	if($config['border-radius'] != "15") {echo '<option value="15">15 %</option>';}
    	if($config['border-radius'] != "20") {echo '<option value="20">20 %</option>';}
    	if($config['border-radius'] != "25") {echo '<option value="25">25 %</option>';}
    	if($config['border-radius'] != "30") {echo '<option value="30">30 %</option>';}
    	if($config['border-radius'] != "35") {echo '<option value="35">35 %</option>';}
    	if($config['border-radius'] != "40") {echo '<option value="40">40 %</option>';}
    	if($config['border-radius'] != "45") {echo '<option value="45">45 %</option>';}
    	if($config['border-radius'] != "50") {echo '<option value="50">50 %</option>';}
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

    $instagram =  $newinstance['instagram_id'];
    $instagram =  $newinstance['link'];
	 $instagram =  $newinstance['bild_title'];
	 $instagram =  $newinstance['border-radius'];
    
    $instance['instagram_id'] =  $newinstance['instagram_id'];
    $instance['title'] =  $newinstance['title'];
    $instance['link'] =  $newinstance['link'];
    $instance['bild_title'] =  $newinstance['bild_title'];
    $instance['border-radius'] =  $newinstance['border-radius'];

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
     $result_bild_title = $instagram["bild_title"];
     $result_border = $instagram["border-radius"];
     
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
		 
		 echo "<!-- Instagram Picture -->\n";
		 echo "<!-- http://wordpress.org/plugins/instagram-picture/ -->\n";
		 
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
			
			if($result_bild_title == "1")
			{
				$title_ausgabe = 'title="'.$title.'" ';	
			}
			else { $title_ausgabe=""; }
			
			if($result_border != "0")
			{
				$border_ausgabe = ' class="instagram-radius-'.$result_border.'" ';	
			}
			else { $border_ausgabe=""; }
			
			echo $link_anfang.'<img src="'.$url.'" '.$title_ausgabe.$border_ausgabe.'/>'.$link_ende;
			
			echo "<!-- Instagram Picture END -->\n";
     }
     
     echo $after_widget;
     
     echo "\n\n";
     
}	
	
	
}


add_action( 'widgets_init', 'load_instagram_importer_widget_einzelnt' );

function load_instagram_importer_widget_einzelnt() {
	register_widget( 'instagram_importer_widget_einzelnt' );
}

?>