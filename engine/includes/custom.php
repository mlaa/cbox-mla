<?php

// Custom avatars.
require_once( 'avatars.php' );

function modify_some_widgets(){

   register_sidebar(array(
      'name' => 'Homepage Center Left',
      'id' => 'homepage-center-left',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   register_sidebar(array(
      'name' => 'Homepage Center Middle',
      'id' => 'homepage-center-middle',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   register_sidebar(array(
      'name' => 'Homepage Center Right',
      'id' => 'homepage-center-right',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   // Unregsiter CBox sidebar
   unregister_sidebar( 'homepage-center-widget' );

   // Publications
   register_sidebar( array(
      'id' => 'publications-sidebar',
      'name' => 'Publications Sidebar',
      'description' => 'The publications widget area',
      'before_widget' => '<article id="%1$s" class="widget %2$s">',
      'after_widget' => '</article>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   register_sidebar( array(
      'id' => 'help-dropdown',
      'name' => 'Help Dropdown',
      'description' => 'The dropdown menu displayed when the help menu item is clicked.',
   ));

}
add_action( 'widgets_init', 'modify_some_widgets', 9 );

?>
