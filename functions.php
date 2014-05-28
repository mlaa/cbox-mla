<?php

// Include files instead of cluttering up this file. 
require_once( 'engine/includes/advanced-search.php' ); 
require_once( 'engine/includes/allowed-tags.php' ); 
require_once( 'engine/includes/avatars.php' );
require_once( 'engine/includes/custom.php' );
require_once( 'engine/includes/custom-filters.php' );

/**
 * Set this to true to put Infinity into developer mode. Developer mode will refresh the dynamic.css on every page load.
 */
define( 'INFINITY_DEV_MODE', true );

/* This script contains Buddypress customizations for MLA group types. */ 

/* MLA edits to BP literals */
define ( 'BP_FRIENDS_SLUG', 'contacts' );

// Change "en_US" to your locale
define( 'BPLANG', 'en_US' );
if ( file_exists( WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo' ) ) {
	load_textdomain( 'buddypress', WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo' );
}

/* This function filters out membership activities from the group activity stream, 
 * so that "so-and-so joined the group X" doesn't clutter the activity stream. 
 */ 

/* this is a jQuery hack to check the checkbox on 
 * Create a Group → 4. Forum → Group Forum → “Yes. I want this Group to have a forum” 
 * by default. 
 */ 
function mla_check_create_forum_for_new_group() {
	if( wp_script_is( 'jquery', 'done' ) ) { ?>
		<script type="text/javascript">
		jq('#bbp-create-group-forum').prop('checked', true);
		</script>
<?php }
}
add_action('wp_footer', 'mla_check_create_forum_for_new_group');

/* disable visual editor entirely, for everyone */ 
/* add_filter( 'user_can_richedit' , '__return_false', 50 ); */ 

/*
 * Remove misbehaving forums tab on profile pages.
 */
function remove_forums_nav() {
	bp_core_remove_nav_item('forums');
}
add_action( 'wp', 'remove_forums_nav', 3 );

/**
 * Removes Forums from Howdy dropdown
 */
function mlac_remove_forums_from_adminbar( $wp_admin_bar ) {
	$wp_admin_bar->remove_menu( 'my-account-forums' );
}
add_action( 'admin_bar_menu', 'mlac_remove_forums_from_adminbar', 9999 );

/* 
 * Hide settings page (we don't want users changing their 
 * e-mail or password).
 */
function change_settings_subnav() {

	$args = array(
		'parent_slug' => 'settings',
		'screen_function' => 'bp_core_screen_notification_settings',
		'subnav_slug' => 'notifications'
	);

	bp_core_new_nav_default($args);

}
add_action('bp_setup_nav', 'change_settings_subnav', 5);

function remove_general_subnav() {
	global $bp;
	bp_core_remove_subnav_item($bp->settings->slug, 'general');
}
add_action( 'wp', 'remove_general_subnav', 2 );

/* 
 * Remove redundant email status button in group headings; 
 * this is handled by the group tab "Email Options" 
 */
remove_action ( 'bp_group_header_meta', 'ass_group_subscribe_button' );

/* 
 * Remove forum subscribe link. Users are already subscribed to the forums 
 * when they subscribe to the group. Having more fine-grained control over 
 * subscriptions is unnecessary and confusing.
 */ 
function mla_remove_forum_subscribe_link($link){ 
	return ""; //making this empty so that it will get rid of the forum subscribe link
} 
add_filter( 'bbp_get_forum_subscribe_link', 'mla_remove_forum_subscribe_link');
