<?php 
/* 
Plugin Name: WP SimpleWeather 
Plugin URI: http://www.mattmcbrien.com/wpsimpleweather/
Description: WP SimpleWeather allows you to quickly and easily display the current weather at any location you enter using the Yahoo! Weather API.
Author: Matt McBrien
Author URI: http://www.mattmcbrien.com/
Version: 0.2.4

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

*/

// Check the version of WordPress, and ask user to upgrade if necessary
global $wp_version;	

$exit_msg='WP SimpleWeather was created and tested using WordPress 3.0 and newer. It may not work correctly on older versions of WordPress. <a href="http://codex.wordpress.org/Upgrading_WordPress">Update to the latest version of WordPress now</a>.';
if (version_compare($wp_version,"2.6","<"))
{
	exit ($exit_msg);
}

// Set plugin URL
$wp_simpleweather_plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

// Insert calls for javascript in the head
function wp_simpleweather_scripts_action()
{
	global $wp_simpleweather_plugin_url;
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('simpleweather', $wp_simpleweather_plugin_url.'jquery.simpleWeather.js', array('jquery'));
	}
}

add_action('wp_print_scripts', 'wp_simpleweather_scripts_action');
add_action('wp_head', 'wp_simpleweather_head_action');

// Insert call for information in the head
function wp_simpleweather_head_action()
{
	global $wp_simpleweather_plugin_url;
	
	$data = get_option('simpleweather_options');
	
	echo '<link rel="stylesheet" href="'.$wp_simpleweather_plugin_url.'wp-simpleweather-plain.css" type="text/css" />';
	echo "<script type='text/javascript'>
		jQuery(function() 
			{
				jQuery.simpleWeather(
				{
				location: '{$data['location']}', unit: '{$data['unit']}',
				success: function(weather)
				{
				";
				if ($data['display_city'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<h3>'+weather.city+";
				}
				if ($data['display_region'] == "1")
				{
					echo "', '+weather.region+";
				}
				if ($data['display_country'] == "1")
				{
					echo "', '+weather.country+'</h3>');";
				}
				if ($data['display_city'] == "1" && $data['display_region'] == "1" && $data['display_country'] != "1")
				{
					echo "'</h3>');";
				}
				if ($data['display_city'] == "1" && $data['display_region'] != "1" && $data['display_country'] != "1")
				{
					echo "'</h3>');";
				}
				if ($data['current_weather_image'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<img src=\'$wp_simpleweather_plugin_url";
					echo "images/".$data['image_theme']."/'+weather.image+'\' id=\'current_weather_image\'&#47;>');";
				}		
				if ($data['current_temperature'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<span id=\'current_temperature\'>'+weather.temp+'&deg; '+weather.units.temp+'</span>');";
				}
				if ($data['current_weather'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<span id=\'current_weather\'>'+weather.currently+'</span>');";
				}
				if ($data['wind_direction'] == "1")
				{
					if ($data['wind_speed_meters'] == "1")
					{
						echo "jQuery(\"#simpleweather-widget\").append('<p id=\'wind_direction\'><strong>Wind</strong>: '+weather.wind.direction+' '+(weather.wind.speed/3.6).toFixed(2)+' m/s</p>');"; 
					}
					else
					{
						echo "jQuery(\"#simpleweather-widget\").append('<p id=\'wind_direction\'><strong>Wind</strong>: '+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'</p>');"; 
					}
				}
				if ($data['wind_chill'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'wind_direction\'><strong>Wind Chill</strong>: '+weather.wind.chill+'&deg; '+weather.units.temp+'</p>');";
				}
				if ($data['weather_pressure'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_pressure\'><strong>Barometer</strong>: '+weather.pressure+' '+weather.units.pressure+";
				}
				if ($data['weather_rising'] == "1")
				{
					echo "' and '+weather.rising+'</p>');";
				}
				if ($data['weather_rising'] != "1" && $data['weather_pressure'] == "1")
				{
					echo "'</p>');";
				}
				if ($data['weather_humidity'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_humidity\'><strong>Humidity</strong>: '+weather.humidity+'&#37;</p>');";
				}
				if ($data['weather_visibility'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_visibility\'><strong>Visibility</strong>: '+weather.visibility+' '+weather.units.distance+'</p>');";
				}
				if ($data['weather_sunrise'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_sunrise\'><strong>Sunrise</strong>: '+weather.sunrise+'</p>');";
				}
				if ($data['weather_sunset'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_sunset\'><strong>Sunset</strong>: '+weather.sunset+'</p>');";
				}
				if ($data['high_temperature'] == "1")
				{
					echo "
					jQuery(\"#simpleweather-widget\").append('<span id=\'high_temperature\'><strong>High</strong>: '+weather.high+'&deg; '+weather.units.temp+'</span>');";
				}
				if ($data['low_temperature'] == "1")
				{
					echo "
					jQuery(\"#simpleweather-widget\").append(' <span id=\'low_temperature\'><strong>Low</strong>: '+weather.low+'&deg; '+weather.units.temp+'</span>');";
				}
				if ($data['weather_forecast'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'weather_forecast\'><strong>Today\'s Forecast</strong>: '+weather.forecast+'</p>');";
				}
				if ($data['tomorrow_date'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<h3 id=\'tomorrow_forecast_header\'>Tomorrow\'s Forecast</h3><p id=\'tomorrow_date\'>'+weather.tomorrow.day+' '+weather.tomorrow.date+'</p>');";
				}
				if ($data['tomorrow_date'] != "1" && $data['tomorrow_weather_image'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<h3 id=\'tomorrow_forecast_header\'>Tomorrow\'s Forecast</h3>');";
				}
				if ($data['tomorrow_weather_image'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<img src=\'$wp_simpleweather_plugin_url";
					echo "images/".$data['image_theme']."/'+weather.tomorrow.image+'\' id=\'tomorrow_weather_image\'&#47;>');";
				}
				if ($data['tomorrow_weather_image'] != "1" && $data['tomorrow_date'] != "1" && $data['tomorrow_weather_forecast'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'tomorrow_forecast_title\'><strong>Tomorrow\'s Forecast:</strong> '+weather.tomorrow.forecast+'</p>');";
				}
				if ($data['tomorrow_weather_forecast'] == "1" && $data['tomorrow_weather_image'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<div id=\'tomorrow_weather_forecast_box\'><span id=\'tomorrow_weather_forecast\'>'+weather.tomorrow.forecast+'</span></div>');";
				}
				if ($data['tomorrow_weather_forecast'] == "1" && $data['tomorrow_date'] == "1" && $data['tomorrow_weather_image'] != "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'tomorrow_forecast_title\'><strong>Tomorrow\'s Forecast:</strong> '+weather.tomorrow.forecast+'</p>');";
				}
				if ($data['tomorrow_high_temperature'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<span id=\'tomorrow_high_temperature\'><strong>High</strong>: '+weather.tomorrow.high+'&deg; '+weather.units.temp+'</span>');";
				}
				if ($data['tomorrow_low_temperature'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append(' <span id=\'tomorrow_low_temperature\'><strong>Low</strong>: '+weather.tomorrow.low+'&deg; '+weather.units.temp+'</span>');";
				}
				if ($data['last_updated'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id=\'last_updated\'>Updated: '+weather.updated+'</p>');";
				}
				if ($data['weather_link'] == "1")
				{
					echo "jQuery(\"#simpleweather-widget\").append('<p id\'=weather_link\'><a href=\"'+weather.link+'\" target=\"_blank\">View forecast at Yahoo! Weather</a></p>');";
				}
				echo "
				},
		error: function(error) 
		{
			jQuery(\"#simpleweather-widget\").html('<p>'+error+'</p>');
		}
	});
});
				
		</script>";
}

// Register the widget with WordPress
function WP_SimpleWeather_Widget_Init()
{
	register_sidebar_widget('WP SimpleWeather', 'WPSimpleWeather_Widget');
	register_widget_control('WP SimpleWeather', 'WPSimpleWeather_WidgetControl');
	$options = get_option('wp_simpleweather');
}

add_action('init', 'WP_SimpleWeather_Widget_Init');

// The function to create the widget (simply creates a div container [#simpleweather-widget] which will be filled by the javascript)
function WPSimpleWeather_Widget($args = array())
{
	// extract the parameters
	extract ($args);
	
	//get the options
	$options=get_option('wp_simpleweather');
	$title=$options['simpleweather_title'];
	
	// print the theme compatibility code
	echo $before_widget;
	echo $before_title . $title. $after_title;
	
	// include the widget
	include('wp-simpleweather-widget.php');
	
	echo $after_widget;
	
}

// The function to create the widget via shortcode (simply creates a div container [#simpleweather-widget] which will be filled by the javascript)
function WPSimpleWeather_ShortCode($atts, $content = null)
{
	//get the options
	$options=get_option('wp_simpleweather');
	
	// extract the parameters
	extract(shortcode_atts(
		array(
			"width"         => "185",
			"float"         => "none",
			"padding"       => "0",
			"paddingtop"    => "0",
			"paddingright"  => "0",
			"paddingbottom" => "0",
			"paddingleft"   => "0",
			),
		$atts));

	if ($paddingleft == "0" && $float == "right")
		$paddingleft = "10";
	if ($paddingright == "0" && $float == "left")
		$paddingright = "10";

	$value .= '<div id="simpleweather-widget" style="width: '.$width.'px; float: '.$float.'; padding: '.$paddingtop.'px '.$paddingright.'px '.$paddingbottom.'px '.$paddingleft.'px;" ></div>';

	return $value;
}

add_shortcode('wp-simpleweather', 'WPSimpleWeather_ShortCode');

// Widget Control Panel
function WPSimpleWeather_WidgetControl()
{
	// get saved options 
	$options = get_option('wp_simpleweather');
	$title = $options['simpleweather_title'];
	include('wp-simpleweather-widget-control.php');
	
	// handle user input
	if ( $_POST["simpleweather_submit"] )
	{
		$options['simpleweather_title'] = strip_tags( stripslashes($_POST["simpleweather_title"] ) );
		update_option('wp_simpleweather', $options);
	}

}

// Creating Options Page
add_action('admin_menu', 'simpleweather_plugin_menu');

// Setup of the options in the DB
add_action('admin_init', 'register_simpleweather_settings' );

function register_simpleweather_settings() 
{
	register_setting('simpleweather_option_group', 'simpleweather_options', 'simpleweather_options_validate' );
		
	$init_options = array(
		'location' => 'london, united kingdom',
		'unit' => 'f',
		'current_weather' => '1',
		'current_weather_image' => '1',
		'current_temperature' => '1',
		'image_theme' => 'yahoo-weather'
	);

	add_option( 'simpleweather_options', $init_options );
}

// Remove options from DB on deactivate
register_uninstall_hook(__FILE__, 'simpleweather_uninstall' );

function simpleweather_uninstall()
{
	unregister_setting('simpleweather_option_group', 'simpleweather_options');
	delete_option('simpleweather_options');
	delete_option('wp_simpleweather');
}

function simpleweather_options_validate($input) 
{
    // Our first value is either 0 or 1
    $input['display_city'] = ( $input['display_city'] == 1 ? 1 : 0 );
   
    // Say our second option must be safe text with no HTML tags
    $input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
   
    return $input;
}

function simpleweather_plugin_menu() 
{
	add_options_page('WP SimpleWeather Plugin Options', 'WP SimpleWeather', 'manage_options', 'wp-simpleweather', 'simpleweather_plugin_options');
}

function simpleweather_plugin_options() 
{
	if (!current_user_can('manage_options'))  
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	include('wp-simpleweather-options.php');

}

?>