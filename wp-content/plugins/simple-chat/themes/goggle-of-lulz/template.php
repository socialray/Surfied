<?php ! defined( 'ABSPATH') && exit(); // prevent direct access ?>

<div class="schatbar" id="schatbar">
	<div id="schat_base">
		<!--chat template-->
		<div id="chat_template" class="chat_tab disabled">
			<div  class="chat_button" style="background-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>; border-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>">
				<span class="tab_name">
					some_name
				</span>
				<span class="tab_count disabled">0</span>
				<label class="close close_button">
					<span>&times;</span>
				</label>
			</div>
			<div class="chat_window">
				<div class="win_titlebar" style="background-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>; border-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>">
					<label class="close close_button">
						<span>&times;</span>
					</label>
					<label title="Minimize" class="minimize">
						<span>_</span>
					</label>
					<div class="win_title_text">
						<a class="win_title_text_link" href="#">name</a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="win_header">
					<a class="win_header_image_link" href="#">
					<img title="View Profile" class="img" src="" alt="" width="42" height="42" /></a>
					<div class="win_header_info"></div>
					<div class="win_toolbox">
						<div class="win_toolbar_items disabled"></div>
					</div>
				</div>
				<div class="win_body" style="height: 160px;">
					<div class="win_content">
					</div>
				</div>
				<div class="win_footer">
					<div class="chat_input_div">
						<textarea class="chat_input"></textarea>
					</div>
				</div>
				<input type="hidden" class="chatting_with_user" value="" />
			</div>
			<!-- end of chat window -->
		</div>
		<!-- end of chat template-->
		<div id="chat_tabs_slider" class="chat_tabs_slider">
			<div class="next disabled">
			</div>
			<div id="active_chat_tabs" class="active_chat_tabs">
				<!--current chat tabs here -->
			</div>
			<div class="prev disabled"></div>
		</div>
		<!-- end of tab slider-->
		<div class="chat_buddylist" id="chat_buddylist">
			<div id="settings_tab" class="chat_tab" >
				<div  class="chat_button" style="background-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>; border-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>">
					<span class="tab_name">
					Online (<span class="online_count"><?php echo schat_get_online_users_count();?></span>)
					</span>
				</div>
				<div class="chat_window">
					<div class="win_titlebar" style="background-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>; border-color:<?php echo get_option( 'schat_color', SIMPLE_CHAT_DEFAULT_COLOR ); ?>">
						<label title="Minimize" class="minimize">
							<span>_</span>
						</label>
						<span class="win_title_text">
							<?php _e('Online Users') ?>
						</span>
						<div class="clear"></div>
					</div>
					<div class="win_header">
						<div class="win_toolbox">
							<div class="win_toolbar_items disabled"></div>
						</div>
					</div>
					<div class="win_body">
						<div class="win_content">
							<div class="friend_list_container" id="friend_list_container">
								<?php schat_get_online_users_list();?>
							</div>
						</div>
					</div>
					<div class="win_footer"></div>
				</div>
				<!-- end of chat window -->
				<input type="hidden" id="fetch_time" value="<?php echo CURRENT_MYSQL_TIME;?>" />
				<input type="hidden" id="mesage_store" value="" />
			</div>
			<!-- end of chat tab-->
		</div>
		<!--end buddylist/settings win -->
	</div>
	<!-- end of chat_base -->
</div>
<!-- end of chat bar -->

