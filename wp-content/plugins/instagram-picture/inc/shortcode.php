<?php
function instagram_picture_shortcode_doku() {

	########################################################################################################################
	/* 
	*	variable definition
   */
	global $instagram_picture_variable;
	global $wpdb;
	########################################################################################################################

	// Picture count
	$num_bilder = $wpdb->get_var("SELECT COUNT(*) FROM $instagram_picture_variable[101]");
	
	if($num_bilder != "0")
	{	
		echo '
		<div class="instagram-picture-box">
			<h2>Shortcode</h2>
			<div class="row-instagram_admin">
				<div class="col-instagram-3_admin instagram-picture-box-in">
					<p>[ipic id="IMAGE-ID"]</p>
					<b>ID</b> - Image ID (ID here: <a href="?page=instagram_picture_alle_bilder">All Pictures</a>)
					<br />
					<b>float</b> - Box alignment (Standard: no orientation)
					<br />
					<b>size</b> - Image size (Standard: 150px)
					<br />
					<b>link</b> - Picture linkable (Instagram-Page: 1; Direct link:2) (Standard: no)
					<br />
					<b>info</b> - Picture Info (Yes: yes or 1 - No: leave empty) (Standard: no)
					<br />
					<b>current</b> - Topicality (Live: live or 1 (This process takes longer) - Database: db or 2 or leave empty) (Standard: db) 
					<br />
					<b>bacolor</b> - Border-Color (Standard: 4D4D4D)
					<br />
					<b>bacolor</b> - Background-Color (Standard: 4D4D4D)
					<br />
					<b>color</b> - font color (Standard: FFFFFF)
					<br />
					<p><b>full documentary:</b> <a target="_blank" href="http://tb-webtec.de/blog/?p=30">www.tb-webtec.de/blog/?p=30</a></p>
				</div>
			</div>
			<div class="instagram_clear_admin"></div>
		</div>
		';
	}
	else 
	{
		echo '
		<div class="instagram-picture-box">
			<h2>Shortcode</h2>
			<div class="instagram-picture-alert">
				<p>You need to upgrade your images first.</p><p>Just click on <b>"<a href="?page=instagram_picture_aktualisieren">Update</a>"</b></p>
			</div>
		</div>
		';
	}

}
?>