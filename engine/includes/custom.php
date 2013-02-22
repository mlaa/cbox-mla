<?php

//Custom avatars.
require_once( 'engine/includes/avatars.php' );

// disable admin bar style that adds 28px to top of screen
add_theme_support( 'admin-bar', array( 'callback' => '__return_false') );

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

}
add_action( 'widgets_init', 'modify_some_widgets', 9 );

function restrict_admin() {
   if ( !current_user_can('edit_posts') && !defined('DOING_AJAX') ) {
      wp_die( __('Access to the dashboard has been disabled.') );
   }
}
add_action( 'admin_init', 'restrict_admin', 1 );

?>
