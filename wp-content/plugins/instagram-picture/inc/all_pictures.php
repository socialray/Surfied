<?php
function instagram_picture_alle_bilder() {

	########################################################################################################################
	/* 
	*	variable definition
   */
	global $instagram_picture_variable;
	global $wpdb;
	########################################################################################################################

	########################################################################################################################
	/*
	*	All Pictures
	*/
		// Picture count
		$num_bilder = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");

		// Check whether any pictures exist	
		if($num_bilder != "0")
		{	
			echo '<div class="instagram-picture-box"><h2>All Pictures</h2>';
			
			// Changes whether public or not
			if(isset($_POST['picid']))
			{
				$picid 				= $_POST['picid'];
				$picid_status		= $_POST['picid_status'];
		
				$wpdb->query("UPDATE $instagram_picture_variable[101] Set status = '$picid_status' WHERE id = '$picid'");
			}
	
			$i=1;		
			// Spend existing images
			foreach( $wpdb->get_results("SELECT * FROM $instagram_picture_variable[101] ORDER BY id DESC") as $key => $row) 
			{
				$url 			= $row->thumbnail;
				$title 		= $row->text;  
				$id 			= $row->id;  				
				$status 		= $row->status;
		
				$class = ($i % 2) ? "FFFFFF" : "E0E0E0";
		
				// output
				echo '
				<table style="float:left;border: 1px solid; margin:5px;background-color:#'.$class.';">
					<tr>
						<td><img src="'.$url.'" title="'.$title.'" width="80px" /></td>
						<td>'.$id.'<br />
						<form action="" id="instagram" method="post">
							<input type="hidden" name="picid" value="'.$id.'">';
						if($status == "0")
						{
							echo '
							<input type="hidden" name="picid_status" value="1">
							<input type="submit" class="instagram-picture-success-button" value="public">
							';	
						}
						if($status == "1")
						{
							echo '
							<input type="hidden" name="picid_status" value="0">
							<input type="submit" class="instagram-picture-danger-button" value="not public">
							';	
						}
						echo '</form>
						</td>
					</tr>
				</table>';
		
				$i++;
			}
	
			// clear
			echo '
				<div class="instagram_clear_admin"></div>
			</div>';
	
		}	
		else 
		{
			echo '
			<div class="instagram-picture-box">
				<h2>All Pictures</h2>
				<div class="instagram-picture-alert">
					<p>You need to upgrade your images first.</p><p>Just click on <b>"<a href="?page=instagram_picture_aktualisieren">Update</a>"</b></p>
				</div>
			</div>
			';
		}	
		
	########################################################################################################################		
		
}
?>