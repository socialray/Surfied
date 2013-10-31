=== Facebook Friends Inviter ===
Contributors: leehodson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=lee@wpservicemasters.com&currency_code=USD&amount=&item_name=Donation%20to%20JournalXtra&return=http://journalxtra.com/thank-you/&notify_url=&cbt=Thank%20you%20for%20your%20donation,%20it%20is%20greatly%20appreciated&page_style=
Tags: Links, Facebook, Inviter, FB, Invite, Facebook Inviter, Widget, Shortcode, Facebook Friend Inviter, Facebook Invite Friends, JournalXtra, LeeHodson, VizRED
Author URI: http://journalxtra.com/
Plugin URI: http://journalxtra.com/websiteadvice/wordpress/plugins-wordpress/wordpress-invite-facebook-friends-button-plugin-5405/
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.0.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Facebook friends inviter buttons using widgets and shortcodes. Visitors click the button then select Facebook friends to bring to your site. Increases traffic and popularity.

== Description ==

Add an Invite Facebook Friends button to any WordPress website with the Facebook Friends Inviter Plugin.

This WordPress plugin adds customizable text links and customizable graphic buttons that are visible to anyone who visits your WordPress site. Buttons can be added using widgets and shortcodes.

**Features**

* Facebook Requests Graphic Button
* Facebook Requests Link Button
* Seamless friend requests selector
* FB Friends request widgets - as many widgets as you want to use
* FB Friend inviter shortcodes - as many shortcodes as you want to use
* Customizable link text
* Customizable button
* Put the button or text link anywhere within your site
* Set button/text width
* Set button/text alignment

This plugin is free. It is standalone so no need to install other plugins to make it work.

You could use the Facebook request code in raw format within your site but why do that when you can use a plugin that makes the process easier, quicker and tidier!

*The easiest way to get your visitors to bring their Facebook friends to your party*

**The Widget**

The widget can be placed in any widget area. Put it in a sidebar, in your header, in your footer... Put it wherever you have a widget area.

Display your button using custom text or a custom button. Can't think of a button image to use? Use the default settings.

**The widget has 10 settings**

* Widget Title
* Show Title
* Dialogue Box Title
* Message
* App ID
* Button Text
* Button Image
* Force Image Use
* Button Width
* Alignment

*Widget Title* is the title of the widget.

*Show Title* determines whether the widget title shows at all.

*Dialogue Box Title* sets the title of the Facebook friends selector popup box.

*Message* is the message shown in the Facebook friends selector popup box.

*App ID* is your Facebook App ID. Instructions for getting an app ID are shown below.

*Button Text* is the text used for your FB invite link. Leave as blank to show the default button image.

*Button Image* is for a custom button image URL. The default button is used if no URL is stated.

*Force button use?* Forces a button image to be used even if anchor text for a text link is stated in the Button text field.

*Button Width* lets you specify the pixel/percentage width of the button image and button text.

*Alignment* lets you choose whether the button should be left, center or right aligned.

[DEMO](http://journalxtra.com/websiteadvice/wordpress/plugins-wordpress/wordpress-invite-facebook-friends-button-plugin-5405/)

**The Shortcode**

The [fib] shortcode lets you place a button anywhere in your site where a shortcode can be used. It has two attributes:

* title
* message
* text
* image
* appid
* width
* align

*title=""* sets the title of the Facebook friends selector popup box.

*message=""* sets the message shown in the Facebook friends selector popup box.

*text=""* is the text used as your 'invite friends' link. Leave empty to display the default inviter button.

*image=""* lets you specify an alternate button image. text="" overrides any image setting.

*appid=""* is your Facebook App ID (instructions above).

*width=""* lets you specify the button width. You must add the unit of measure e.g px or %.

*align=""* let you choose button alignment. Options are *left*, *center* and *right*. The default value is 'center'.

Only two attributes need to be set for the button to work: *message* and *appid*. For example,

[fib message="Learn exciting new stuff at example.com!" appid="123456789"]

The shortcode used with all attributes could look like:

[fib title="Invite friends to join" message="Learn exciting new stuff at example.com!" text="Click to invite friends" image="http://example.com" appid="123456789" width="100%" align="center"]

Both the *title* and *message* attributes default to 'Invite Friends'.

The *text* attribute overrides the *image* attribute.

== Other Notes ==

= How to get your Facebook AppID =

You need a Facebook App ID to be able to use the inviter plugin. Just follow these steps to make your app.

* Go to <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Developers</a>
* Create a developers account if you do not already have one
* Click *Apps* in the top menu
* Click *Create New App* at the top right of the page, below the main menu
* Give your app a *Display Name* and *Namespace*. These can be the same. By doing this you are effectively naming your app within Facebook and giving it a URL
* Add a contact email
* Type in the domain name of the site that this plugin is installed in. Leave out the http://www bit. Enter the domain name only e.g example.com. Do not use a trailing slash
* Disable sandbox mode (we're not testing, are we?)
* Under *Website with Facebook Login,* enter the domain of the site that hosts this plugin. This time enter the protocol and the subdomain name if required e.g http://example.com/ or http://www.example.com/. Best way to get the proper domain is to go to the site's homepage and copy the URL from the browser address bar. Use a trailing slash
* Save changes
* Copy the App ID from the top of the *Basic* settings page
* You can set an avatar image from the *Basic* settings page by clicking *edit* when hovering over the default app avatar image. You add further details about your app from the *App Details* page
* Use the *App ID* in the FacebookInviter widget's settings or us it in the shortcode's appid="" attribute.

== Installation ==

1. Use the WordPress *Add New Plugins* menu otherwise...
1. Download the zip file from WordPress.org.
1. Upload the *FacebookInviter* file to */wp-content/plugins/*.
1, Extract the zip file.
1. Activate the plugin through the WordPress *plugins* page.
1. See *Appearance > Widgets* to place the *Facebook Inviter Widget* in a widget area.
1. Use the [fib] shortcode to place a Facebook friends inviter button anywhere a widget can't be used.

== Frequently Asked Questions ==

*How can I use single quotes in the Facebook PopUp Box?*
You can't. Swap them for double quotes instead.

== Contact ==

[General support](http://journalxtra.com/)
[Commercial support](http://vizred.com/)
[Donate](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=lee@wpservicemasters.com&currency_code=USD&amount=&item_name=Donation%20to%20JournalXtra&return=http://journalxtra.com/thank-you/&notify_url=&cbt=Thank%20you%20for%20your%20donation,%20it%20is%20greatly%20appreciated&page_style=WPSM)

== Supported Languages ==

* English

== Changelog ==

1.0.5

* Bug fix: 32 bit servers were unable to use numbers greater than 2147483647. Added different method of checking numerical input.

1.0.4

* Replaced esc_html() with htmlspecialchars()
* Prefixed variables used by the shortcode's code with 'sc' to help eliminate conflict between the widget and the shortcode
* Prefixed the function() used within the shortcode's code with 'SC' to help eliminate conflict between the widget and the shortcode (precautionary action)
* Converted screenshots to jpegs from PNGs in hope they display better in the plugin's details in the WP repo.

1.0.3

* Fixed missing comma in shortcode code.
* Changed esc_attr to esc_html() on save with html_entity_decode() on display in the widget code.
* Thank you wordpress.org users christonphero and funmi omoba for pointing out my oversight.

1.0.2

* Minor change: original upload had been placed into a subdirectory within SVN /Trunk. Corrected this.
* Corrected screenshots.

1.0.1

* First public release.

== Upgrade Notice ==

*

== Screenshots ==

1. widget settings
1. button appearance
1. facebook invite popup
1. facebook app creation