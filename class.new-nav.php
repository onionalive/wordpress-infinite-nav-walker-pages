<?php

// Extending Walker_Nav_Menu
class xyz_Custom_Menu extends Walker_Nav_Menu {
	
	// start_el defines the start of the element being rendered
	// In this case, the element being the top level nav item & all children
  function start_el( &$output, $item, $depth, $args, $id ) {

		// depth == 0 meaning the current item is a top level navigation item
    if ($depth == 0) {
			
			// The id is not the post id, but the navigation item id. This gets the id of the post item
      $id = get_post_meta( $item->ID, '_menu_item_object_id', true );
			
			// Checks to see if the post has child pages
      $post_child_pages = get_pages( array( 'child_of' => $id ));
			
			// Sets classes to a variable for easier manipulation (array)
      $classes = $item->classes;
			
			// Grabs the last class in array of classes
      $last_class = end($classes);

      // If page does not have any child pages, do not add the `has-children` class
			// wordpress will add this class by default if there are sub-menu items set, but since
			// we are defing children as pages - not nav items - this logic needs to be updated
      // else, if page does have child pages but no `sub-menus` add `has-children` class
			// This way we can accurately attach styling & js to menu items with children
      if (count($post_child_pages) <= 0) {
        array_pop($classes);
      } elseif ((count($post_child_pages) >= 1) && ($last_class != "menu-item-has-children")) {
        array_push($classes, "menu-item-has-children");
      }

			// Starting output definition - add classes to li element
      $output .= "<li class='" .  implode(" ", $classes) . "'>";
			
			// Adding link with item url 
      $output .= '<a href="' . $item->url . '">';
			
			// Adding title
      $output .= $item->title;
			
			// Closing link tag
      $output .= '</a>';
			
			// Adding any child items of page to output
      $output .= self::get_menu_children($id);

    } elseif ($depth >= 1) {
      // Do not render pages beyond top-level
      $output .= '';
    }
	}

	// Since we are only rendering top level items, we want this to output nothing
	// otherwise start_lvl and end_lvl will render empty ul elements to page
	// This wont break the menu, but will add needless elements to DOM
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "";
  }

	// Again, setting start_lvl and end_lvl to empty
  function end_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "";
  }

	// Get all children, grandchildren, etc. of post.
  function get_menu_children( $id, $parent, $grandparent = NULL ) {
    // String of markup to be returned and added to menu
    $return_output = "";

    // set arguments to pass to get_pages
    $args = array(
      'parent' => $id,
      'sort_order' => 'desc',
    );
		// Get children of current post
    $children = get_pages( $args );

    // Loop through children
    if ($children != NULL && count($children) > 0 ) {
			// if children, nest within a ul element
      $return_output .= "<ul class='sub-menu'>";

      // Loop through & display children
      foreach ($children as $child) {
				// Get the title of child
        $title = get_the_title($child);
				
				// Get the url of child
        $url = get_permalink($child);
				
				// Get id of child
        $id = $child->ID;

        // Get children of child
        $has_children = get_pages( array( 'child_of' => $id ) );
				// Set 'has-children' class to empty string
        $has_child_class = "";

				// if child does have children, update class to add to child element
        if ( count($has_children) > 0 ) {
          $has_child_class = "menu-item-has-children";
        }

				// render child list item element with $has_child_class
        $return_output .= "<li class='menu-item menu-item-type-post_type menu-item-object-page $has_child_class'>";
				// render child link with url and title
        $return_output .= "<a href='$url'>$title</a>";

        // Recursively traverse down in search of deeper levels of children
				// If child has children, will return another ul element with children, granchildren, etc.
				// otherwise, will return an empty string to append to output
        $return_output .= self::get_menu_children( $id, $new_parent, $new_grandparent );
      }

      // after all children looped, close the <ul> element
      $return_output .= '</ul>';
    }

		// Return full output of all children - granchildren, etc. to append to $output in start_el
    return $return_output;
  }
}


?>
