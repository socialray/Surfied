wp-bootstrap
============

Module intended to include Twitter Bootstrap with LESS into WordPress Themes.

**Version:** 3.0.0

**Twitter Bootstrap Version:** 3.0.0

**Author:** Mattia Migliorini (deshack)

**Donate:** http://www.deshack.net/donate/

Usage
---

Include this library in your theme's directory, import `less/bootstrap.less` in your own less file and start overriding.

To use Boostrap Walker in your menu, open your **functions.php** file and add this content:

```
// Register Custom Navigation Walker
require get_template_directory_uri() . '[path_to_wp-bootstrap]/bootstrap-walker.php';
```

Than update your ```wp_nav_menu()``` function to use the new walker:

```
<?php
	wp_nav_menu( array(
		'menu'						=> 'primary',
		'theme_location'	=> 'primary',
		'depth'						=> 2,
		'container'				=> false,
		'menu_class'			=> 'nav navbar-nav',
		'fallback_cb'			=> 'wp_page_menu',
		'walker'					=> new Bootstrap_Walker()
	) );
?>
```
