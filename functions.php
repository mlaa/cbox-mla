<?php

/**
 * Set this to true to put Infinity into developer mode
 */
define( 'INFINITY_DEV_MODE', true );

//
// Usually dev mode is enough, but if want finer control you can
// set some of these special constants manually.
//

/**
 * Set this to false to totally disable error handling by Infinity
 */
//define( 'INFINITY_ERROR_HANDLING', false );

/**
 * Set this to true to show detailed error and exception reports. This only
 * works if error handling is enabled (see above)
 */
define( 'INFINITY_ERROR_REPORTING', true );

/**
 * Set this to a short message to customize the friendly AJAX error
 */
//define( 'ICE_ERROR_AJAX_MESSAGE', 'Woops! We forgot to feed the server, sorry!' );

/**
 * Set this to an absolute path to a custom friendly fatal error page
 */
//define( 'ICE_ERROR_PAGE_PATH', '/path/to/my/error.php' );

/**
 * Set this to false to disable caching of dynamically generated
 * CSS and Javascript. Highly recommended for development.
 */
define( 'ICE_CACHE_EXPORTS', false );

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


function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(/wp-content/themes/cbox-mla/assets/images/mla-commons-logo.png);
            padding-bottom: 0px;
            background-size:271px 40px;
        }
        body.login { background:#fff;}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


?>
