<?php

// Displays our custom menu on the page
wp_nav_menu( array(
	'container'  => false,
	'items_wrap' => '%3$s',
	// Uses our new walker to create the menu
	'walker' => new xyz_Custom_Menu,
	// chooses which menu to use
	'theme_location' => 'menu-location'
));

?>
