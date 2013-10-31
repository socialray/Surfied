	
	<div class="wrap">

		<div style="float:right;width:400px">
			<div style="float:right; margin-top:10px">
				 <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(SIMPLE_CHAT_LIKE_URL) ?>&amp;layout=box_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21"
					scrolling="no" frameborder="0" style="overflow:hidden; width:90px; height:61px; margin:0 0 0 10px; float:right" allowTransparency="true"></iframe>
					<strong style="line-height:25px;">
						<?php echo __('Do you like <a href="'.SIMPLE_CHAT_LIKE_URL.'" target="_blank">Simple Chat</a> Plugin? '); ?>
					</strong>
			</div>
		</div>

		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Simple Chat</h2>
		
		<form action="options.php" method="post">
			
			<?php settings_fields( 'simple-chat' ); ?>
			
			<table class="form-table">
				<!--tr valign="top">
					<th scope="row">Smiles</th>
					<td>
						<label for="sra_show_posts_at_home">
							<input name="sra_show_posts_at_home" type="checkbox" id="sra_show_posts_at_home" value="1" <?php if(get_option('sra_show_posts_at_home')) echo 'checked="checked"' ?> />
							
						</label>
					</td>
				</tr-->
				<tr valign="top">
					<th scope="row">Color</th>
					<td>
						<label for="schat_color">
							<input name="schat_color" type="text" id="schat_color" value="<?php echo get_option('schat_color', '#333333') ?>" />
						</label>
						<div id="colorpicker"></div>
						
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Themes</th>
					<td>
						<!--label>
							<input name="schat_theme" type="radio" value="default" <?php if( get_option('schat_theme', 'default')=='default' ) echo 'checked="checked"' ?> />
							Black Penguin (default theme)
							<br />
						</label-->
							
						<label>
							<input name="schat_theme" type="radio" value="goggle-of-lulz" <?php if( get_option('schat_theme', 'goggle-of-lulz')=='goggle-of-lulz' ) echo 'checked="checked"' ?> />
							Goggle of Lulz
							<br />
						</label>
						
						<label>
							<input name="schat_theme" type="radio" value="lacking-faces-2009" <?php if( get_option('schat_theme')=='lacking-faces-2009' ) echo 'checked="checked"' ?> />
							Lacking faces 2009 (with a bar at bottom)
							<br />
						</label>
						
						<!--label>
							<input name="schat_theme" type="radio" value="lacking-faces-2010" <?php if( get_option('schat_theme')=='lacking-faces-2010' ) echo 'checked="checked"' ?> />
							Lacking faces 2010 (without bar)
							<br />
						</label>
						
						<label>
							<input name="schat_theme" type="radio" value="lacking-faces-2013" <?php if( get_option('schat_theme')=='lacking-faces-2013' ) echo 'checked="checked"' ?> />
							Lacking faces 2013 (dark and fixed bar)
							
						</label-->
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Notification</th>
					<td>
						<label for="schat_notification">
							<input name="schat_notification" type="checkbox" id="schat_notification" value="1"
								<?php if( get_option('schat_notification') ) echo 'checked="checked"'; ?> /> Disable sound notification
						</label>						
					</td>
				</tr>
			</table>
			
			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save changes') ?>"></p>
		</form>
	</div>

<script type="text/javascript">
 
  jQuery(document).ready(function() {
    jQuery('#colorpicker').hide();
    jQuery('#colorpicker').farbtastic("#schat_color");
    jQuery("#schat_color").click(function(){jQuery('#colorpicker').slideToggle()});
  });
 
</script>
