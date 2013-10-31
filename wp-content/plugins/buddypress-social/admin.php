<?php

// add the bp social admin menu
add_action('admin_menu', 'bp_social_plugin_menu');
add_action( 'network_admin_menu', 'bp_social_plugin_menu' );

function bp_social_plugin_menu() {
	add_submenu_page( 'options-general.php', 'Bp Social', 'BuddyPress Social', 'manage_options', 'bp-social', 'bpsocial_plugin_options');
	add_action( 'admin_init', 'bpsocial_register_settings' );
}

// register the bp social admin settings
function bpsocial_register_settings() {
// social services options
	register_setting( 'bpsocial_plugin_options', 'social_button_facebook' );
	register_setting( 'bpsocial_plugin_options', 'social_button_twitter' );
	register_setting( 'bpsocial_plugin_options', 'social_button_google' );
	register_setting( 'bpsocial_plugin_options', 'social_button_email' );
// social button color options
	register_setting( 'bpsocial_plugin_options', 'social_button_color' );
	register_setting( 'bpsocial_plugin_options', 'social_button_color_hover' );
}

// build the admin options page
function bpsocial_plugin_options() {
	// admin check
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>

<form method="post" action="<?php echo admin_url('options.php');?>">

<?php wp_nonce_field('update-options'); ?>

    <div class="wrap">
        <div class="icon32" id="icon-buddypress">
            <br>
        </div>

        <h2 class="nav-tab-wrapper">
        	<p class="nav-tab nav-tab-active">Social Settings</p>
        	<a class="nav-tab" href="http://bp-plugins.com" target="_blank">Premium</a>
        </h2>

        <h3>Social services</h3>

        <p>Change the colors of the social buttons. If you do not select a
        color or clear and save, your theme's a tag styling will be used.</p>
        <!--START:social options table-->

        <table cellspacing="0" class="widefat fixed plugins">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column" id="cb"
                    scope="col">&nbsp;</th>

                    <th class="manage-column column-name" id="name" scope="col"
                    style="width: 190px;">Component</th>

                    <th class="manage-column column-description" id=
                    "description" scope="col">Description</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <tr>
                    <th scope="row">
                    	<input type="checkbox" name="social_button_facebook" value="1" <?php if (get_option('social_button_facebook')==1) echo 'checked="checked"'; ?>/>
                	</th>

                    <td class="plugin-title" style="width: 190px;">
                        <span class="social foundicon-facebook" style=
                        "font-size: 16px; float: left; margin: 5px 5px 0 0;"></span>
                        <strong style="margin-top: 3px;">Facebook</strong>

                        <div class="row-actions-visible"></div>
                    </td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p>Facebook is a social utility that connects people with friends and others who work, study and live around them.</p>
                        </div>

                        <div class="active second plugin-version-author-uri">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    	<input type="checkbox" name="social_button_twitter" value="1" <?php if (get_option('social_button_twitter')==1) echo 'checked="checked"'; ?>/>
                	</th>

                    <td class="plugin-title" style="width: 190px;">
                        <span></span> <span class="social foundicon-twitter"
                        style=
                        "font-size: 16px; float: left; margin: 5px 5px 0 0;"></span>
                        <strong style="margin-top: 3px;">Twitter</strong>

                        <div class="row-actions-visible"></div>
                    </td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p>Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news..</p>
                        </div>

                        <div class="active second plugin-version-author-uri">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    	<input type="checkbox" name="social_button_google" value="1" <?php if (get_option('social_button_google')==1) echo 'checked="checked"'; ?>/>
                	</th>

                    <td class="plugin-title" style="width: 190px;">
                        <span></span> <span class=
                        "social foundicon-google-plus" style=
                        "font-size: 16px; float: left; margin: 5px 5px 0 0;"></span>
                        <strong style="margin-top: 3px;">Google+</strong>

                        <div class="row-actions-visible"></div>
                    </td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p>Google+ aims to make sharing on the web more like sharing in real life. Check out Circles, Events and Hangouts, just a few of the things we've been working on.</p>
                        </div>

                        <div class="active second plugin-version-author-uri">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    	<input type="checkbox" name="social_button_email" value="1" <?php if (get_option('social_button_email')==1) echo 'checked="checked"'; ?>/>
                	</th>

                    <td class="plugin-title" style="width: 190px;">
                        <span></span> <span class="general foundicon-mail"
                        style=
                        "font-size: 16px; float: left; margin: 5px 5px 0 0;"></span>
                        <strong style="margin-top: 3px;">Email</strong>

                        <div class="row-actions-visible"></div>
                    </td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <p>Electronic mail, most commonly referred to as email or e-mail, is a method of exchanging digital messages from an author to one or more recipients.</p>
                        </div>

                        <div class="active second plugin-version-author-uri">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table><!--END:social options table-->
        <br>

        <h3>Social button colors</h3>

        <p>Change the color of the social buttons. If you do not select a color
        or clear and save, your theme's a tag styling will be used.</p>

        <!--START:color picker table-->
        <table cellspacing="0" class="widefat fixed plugins">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column" id="cb"
                    scope="col">&nbsp;</th>

                    <th class="manage-column column-name" id="name" scope="col"
                    style="width: 190px;">Component</th>

                    <th class="manage-column column-description" id=
                    "description" scope="col">Color picker</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <tr>
                    <th scope="row"></th>

                    <td class="plugin-title" style="width: 190px;">
                    <strong style="margin-top: 3px;">Social button
                    color</strong></td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <input class="my-color-field" name="social_button_color" type="text" value="<?php echo get_option('social_button_color') ?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row"></th>

                    <td class="plugin-title" style="width: 190px;">
                    <strong style="margin-top: 3px;">Social button hover
                    color</strong></td>

                    <td class="column-description desc">
                        <div class="plugin-description">
                            <input class="my-color-field" name="social_button_color_hover" type="text" value="<?php echo get_option('social_button_color_hover') ?>">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table><!--END:color picker table-->
    </div>
<!--save the settings-->
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="social_button_facebook,social_button_twitter,social_button_google,social_button_email,social_button_color,social_button_color_hover" />
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
</form>

<?php
}
// END:build the admin options page
?>