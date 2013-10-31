=== BuddyPress Profile Progression ===
Contributors: grosbouff
Tags: BuddyPress,profile,stats,progression,bar,progress bar,statistics
Requires at least: WP 3, BuddyPress 1.2
Tested up to: WP 3.5.2, BuddyPress 1.7
License: GPLv2
Stable tag: trunk
Donate link:http://bit.ly/gbreant

Simple plugin that adds a progress bar on members pages, which displays the percentage of profile completed by a user.


== Description ==

Simple plugin that adds a progress bar on members pages, which displays the percentage of profile completed by a user.

By default, each field of the profile, except the base field (Name), worth 1 point.
If you have setup 10 fields and that your user has filled 4 fields, his progress bar will be at 40%.

If you want to customize how points are calculated (some fields can worth more than others); or add custom functions which must be taken
into consideration (eg. you can give points if the user has an avatar), you can do it using hooks.  Check the FAQ !

== Installation ==

= WordPress 3 and above = 

1. Check you have WordPress 3.0+
2. Download the plugin
3. Unzip and upload to plugins folder
4. Activate the plugin.

== Frequently Asked Questions ==

=How to embed the profile progression in my template ?=

Use function bppp_progression_block($user_id).  If no $user_id is set, the progression will be shown for the curent displayed user.

=I want to change how points are given to a specific profile field =

By default, each profile field worth 1 points.
You can act on how profile fields are count by adding a function on the bppp_register_progression_point_XXX hook, where XXX is the label of the progression point item.

Example for profile field#2 (changing the value to 5 points) :
>   function edit_progression_point_for_field_2($item){
>       //change the amounts of points for this field
>       $item['points']=5;
>       return $item;
>   }
>   
>   add_filter('bppp_register_progression_point_profile-field-2',edit_progression_point_for_field_2);


* I want to extend the plugin and count progression points for custom functions...
You can register new progression points using function bppp_register_progression_point().

Example : adding 3 points if the user has an avatar


>   function bppp_custom_function_avatar_register_point(){
>       bppp_register_progression_point(
>               'avatar',                                       //label for this custom point 
>               'bppp_custom_function_check_user_has_avatar',   //callback,
>               3                                               //points
>       );
>   }
>
>       function bppp_custom_function_check_user_has_avatar(){
>   
>          $user_id = bppp_get_user_id();
>          $has_avatar = ( bp_core_fetch_avatar( array( 'item_id' => $user_id, 'no_grav' => true,'html'=> false ) ) != bp_core_avatar_default() );
>   
>           return (bool)$has_avatar;
>       }
>   
>   
>   add_action('bppp_register_progression_points','bppp_custom_function_avatar_register_point');


= How can I customize the look of the plugin ? =

Use CSS rules and/or copy the files from /buddypress-profile-progression/theme to your current theme directory.
The plugin will load them first if they exists.


== Screenshots ==

1. Stat as displayed on a member profile

== Changelog ==
= 0.3.2 =
* POT file
* German translation (thanks to Thorsten W.)
* Russian translation (thanks to Romik J.)
= 0.3.1 =
* Localization path fix
* Spanish translation (thanks to Andrés Felipe L.G.)
= 0.2.8 =
* Better code to extend the plugin
= 0.2.7 =
* Added admin option to enable/disable profile progression auto embed 
= 0.2.6 = 
* New function bppp_progression_block($user_id) to display a user's profile progression
* Various Fixes
= 0.2.5 =
* Fixed bug in bppp_get_title when dispaying other user's profile progression bar.
= 0.2.4 =
* Localization files
= 0.2.3 =
* Plugin's headers fix
= 0.2.1 =
* loader.php for BuddyPress
= 0.2 =
* All code reviewed and rewritten for BP1.7.

= 0.1 =
* First version
