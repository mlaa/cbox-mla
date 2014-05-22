<?php

// Include files instead of cluttering up this file. 
require_once( 'engine/includes/avatars.php' );
require_once( 'engine/includes/custom.php' );
require_once( 'engine/includes/custom-filters.php' );


/**
 * Set this to true to put Infinity into developer mode. Developer mode will refresh the dynamic.css on every page load.
 */
define( 'INFINITY_DEV_MODE', true );

/* This script contains Buddypress customizations for MLA group types. */ 

/* This part adds custom MLA filters to the BuddyPress 
 * Groups directory, allowing users to filter the groups by type
 * (committees, discussion groups, etc) and by visibility
 * (private, public, hidden, etc). 
 */

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

/* This rewrites the create_screen function so the 
 * option to enable forums is automatically checked 
 * when creating a new group. 
 */ 

/* Disabling, since I can't get this to work. 
class MLA_BBP_Forums_Group_Extension extends BBP_Forums_Group_Extension { 
 	function create_screen() {

		// bail if not looking at this screen
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false;

		$checked = TRUE; 
//		$checked = bp_get_new_group_enable_forum() || groups_get_groupmeta( bp_get_new_group_id(), 'forum_id' ); ?>

		<h4><?php _e( 'Group Forum', 'bbpress' ); ?></h4>

		<p><?php _e( 'Create a FIXME FIXME to allow members of this group to communicate in a structured, bulletin-board style fashion.', 'bbpress' ); ?></p>

		<div class="checkbox">
			<label><input type="checkbox" name="bbp-create-group-forum" id="bbp-create-group-forum" value="1"<?php checked( $checked ); ?> /> <?php _e( 'Yes. I want this group to have a forum.', 'bbpress' ); ?></label>
		</div>

		<?php

		// Verify intent
		wp_nonce_field( 'groups_create_save_' . $this->slug );
	}
} 

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

/* This is a quick and dirty jQuery hack to remove the default value
 * for a group's blog subdomain name, (i.e. the suggested subdomain name). 
 * This addresses the issue created here: 
 * https://github.com/boonebgorges/bp-groupblog/issues/27
 * with very long (>63 char) subdomain names. 
 * A pull request was created here: 
 * https://github.com/boonebgorges/bp-groupblog/pull/28
 * Once this issue is addressed and a fix released in CBOX, 
 * this function can be deleted. 
 */
function mla_dont_suggest_long_subdomain() { 
	if( wp_script_is( 'jquery', 'done' ) ) { ?>
		<script type="text/javascript">
		jq('#blogname').val('');
		</script>
<?php }
} 
add_action('wp_footer', 'mla_dont_suggest_long_subdomain');

/* disable visual editor entirely, for everyone */ 
/* add_filter( 'user_can_richedit' , '__return_false', 50 ); */ 

/* allow a few more tags in posts so that users can paste from Microsoft Word and not see any cruft */ 
function mla_allowed_tags() {
        return array(

                // Links
                'a' => array(
                        'href'     => array(),
                        'title'    => array(),
                        'rel'      => array(),
                        'target'   => array()
                ),

                // Quotes
                'blockquote'   => array(
                        'cite'     => array()
                ),

                // Code
                'code'         => array(),
                'pre'          => array(),

                // Formatting
                'em'           => array(),
                'strong'       => array(),
                'del'          => array(
                        'datetime' => true,
                ),
		// Tags used by Word, begrudgingly included so that users can paste from Word
		'b'            => array(), 	
		'i'            => array(),
		'h1'           => array(),
		'h2'           => array(),
		'h3'           => array(),
		'h4'           => array(),
		'h5'           => array(),
		'h6'           => array(),
		'sub'          => array(),
		'sup'        => array(),
		'p'            => array(
			'align'    => true, 
		),
		'span'         => array(
	 		'style'    => true,	
		), 

                // Lists
                'ul'           => array(),
                'ol'           => array(
                        'start'    => true,
                ),
                'li'           => array(),

                // Images
                'img'          => array(
                        'src'      => true,
                        'border'   => true,
                        'alt'      => true,
                        'height'   => true,
                        'width'    => true,
                )
        );
}

add_filter('bbp_kses_allowed_tags','mla_allowed_tags');

// Adds BBPress "Forums" select option to Advanced Search. - JR
function mla_bp_search_form_type_select_add_forums($options) { 
	$options['bbpforums']  = __( 'Forums',  'buddypress' ); 
	return $options; 
} 
add_filter('bp_search_form_type_select_options', 'mla_bp_search_form_type_select_add_forums'); 

// Fix forum search handling - JR
function mla_bp_core_action_search_site( $slug = '') { 

	if ( !bp_is_current_component( bp_get_search_slug() ) )
		return;

	if ( empty( $_POST['search-terms'] ) ) {
		bp_core_redirect( bp_get_root_domain() );
		return;
	}

	$search_terms = stripslashes( $_POST['search-terms'] );
	$search_which = !empty( $_POST['search-which'] ) ? $_POST['search-which'] : '';
	$query_string = '/?s=';

	if ( empty( $slug ) ) {
		switch ( $search_which ) {
			case 'posts':
				$slug = '';
				$var  = '/?s=';

				// If posts aren't displayed on the front page, find the post page's slug.
				if ( 'page' == get_option( 'show_on_front' ) ) {
					$page = get_post( get_option( 'page_for_posts' ) );

					if ( !is_wp_error( $page ) && !empty( $page->post_name ) ) {
						$slug = $page->post_name;
						$var  = '?s=';
					}
				}
				break;

			case 'blogs':
				$slug = bp_is_active( 'blogs' )  ? bp_get_blogs_root_slug()  : '';
				break;

			case 'forums':
				$slug = bp_is_active( 'forums' ) ? bp_get_forums_root_slug() : '';
				$query_string = '/?fs=';
				break;

			case 'bbpforums': 
				$slug = 'forums';
				$query_string = '/search/';
				break;

			case 'groups':
				$slug = bp_is_active( 'groups' ) ? bp_get_groups_root_slug() : '';
				break;

			case 'members':
			default:
				$slug = bp_get_members_root_slug();
				break;
		}

		if ( empty( $slug ) && 'posts' != $search_which ) {
			bp_core_redirect( bp_get_root_domain() );
			return;
		}
	}
	bp_core_redirect( apply_filters( 'bp_core_search_site', home_url( $slug . $query_string . urlencode( $search_terms ) ), $search_terms ) );
} 
//add_filter('bp_core_search_site', 'mla_bp_search_forums', 10, 2); 
remove_action('bp_init', 'bp_core_action_search_site', 7); 
add_action('bp_init', 'mla_bp_core_action_search_site', 7); 

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
