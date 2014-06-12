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

/* 
 * Remove subscription link from groups directory. Cleans up the interface
 * a lot, and users can still change their email subscription settings in "email settings." 
 */ 
remove_action ( 'bp_directory_groups_actions', 'ass_group_subscribe_button' );
