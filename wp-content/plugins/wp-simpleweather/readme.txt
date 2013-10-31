=== WP SimpleWeather ===
Contributors: Matt McBrien
Donate link: https://www.paypal.com/us/cgi-bin/webscr?cmd=_flow&SESSION=OpQWbMDAUV-MIl8VAqeiPWmDBHrYQm_zvLKRGnNuPLM27L9_QV4kr4Qebl0&dispatch=5885d80a13c0db1f8e263663d3faee8d4b3d02051cb40a53495971fa2777c8ff
Tags: weather, widget, jquery, Yahoo!, simpleweather, plugin
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: trunk

WP SimpleWeather allows you to quickly and easily display the current weather at any location you enter using the Yahoo! Weather API.

== Description ==
WP SimpleWeather allows you to quickly and easily display the current weather at any location you enter using the Yahoo! Weather API. WP SimpleWeather is exactly what you'd expect from the name. A SIMPLE way to display weather via a wordpress plugin. It is easily installed, widgetized, and comes with an expansive yet easy to use settings page. 

The WP SimpleWeather Plugin was adapted from the simpleWeather jQuery plugin by [James Fleeting](http://paperkilledrock.com/2010/06/how-to-display-weather-with-jquery/).

== Installation ==
1. Upload the whole plugin folder to your /wp-content/plugins/ folder.
2. Go to the 'Plugins' page in the menu and activate the plugin.
3. Use the WP SimpleWeather Settings page located under the 'Settings' menu to change your plugin options. You must enter a location.
4. Activate the WP SimpleWeather Widget under the 'Appearance --> Widgets' menu, or by adding the `[wp-simpleweather]` shortcode where you wish to display the weather widget. 

= Shortcode Options =
> * Options include [wp-simpleweather width="(integer)" float="(left/right/none)" paddingtop="(integer)" paddingright="(integer) paddingbottom="(integer)" paddingleft="(integer)"
> * **Example** [wp-simpleweather width="200" float="left" paddingtop="10" paddingright="10" paddingbottom="10" paddingleft="0"]

== Screenshots ==
1. The default WP SimpleWeather widget.
2. The WP SimpleWeather widget with all options shown.
3. The WP SimpleWeather Settings page.

== Changelog ==
Changes for the WP SimpleWeather Plugin:

= 0.2.4 =
* Testing to ensure the plugin works correctly with Wordpress 3.5.1.

= 0.2.3 =
* Corrected an error where selecting to display only the city, without displaying the region or country, would break the widget. Thanks to Rick H. for pointing out the problem.

= 0.2.2 =
* Added the ability to modify celsius wind speed units from km/h to m/s.
* Changed function which removed DB settings on deactivation to the proper function to remove those settings on uninstall. Unfortunately, prior to this version, updates remove your settings. Sorry!

= 0.2.1 =
* Changes to initialization of options, so that default options are saved into the DB, not merely checked as if they had been saved.
* Now deletes DB entries upon deactivation for a clean uninstall.

= 0.2 =
* Minor corrections in wp-simpleweather-plain.css
* Addition of a choice of image themes. Choices added are Sketchy Weather by [Azure_Sol](http://azuresol.deviantart.com/) and Weather Images Shiny by [Jyrik](http://jyrik.deviantart.com/).
* Localized all image files.
* Added shortcode implementation option.

= 0.1.1 =
* Minor correction to description, addition of changelog in the readme.txt file.

= 0.1 =
* Initial release.