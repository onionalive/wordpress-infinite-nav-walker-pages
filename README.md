# wordpress-infinite-nav-walker-pages
A simple custom nav walker that will grab only the top level elements of a nav and create a menu with all child pages, grandchild pages, etc.

## Explanation

What does this custom nav walker do? In wordpress, you can create a custom menu of pages in Admin -> Appereance -> Menus. You can also give pages parents. This attempts to combine these features in a functional navigation menu.

This will grab ONLY the top level menu items set in Appereance -> menus (and ignore all sub menu items). It will then search each item for `child pages`, each of those `child pages` for their own `child pages`, etc. until there are no more children to show. 

This allows you to create a page hierarchy that will be reflected in navigation without needing to replicate that heirarcy entirely in the menus admin.

## Files

### page.php
This is an example of the wp_nav_menu item that you put into your page file to display the menu

### class.new-nav.php
This is the logic behind the creation of the menu. You should import this class/file into your functions.php file.
