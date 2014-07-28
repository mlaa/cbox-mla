<?php
/**
 * This file contains customizations specific to the MLA
 * implementation of CBOX. 
 */ 

// Include files instead of cluttering up this file. 
require_once 'engine/includes/advanced-search.php'; 
require_once 'engine/includes/allowed-tags.php'; 
require_once 'engine/includes/avatars.php';
require_once 'engine/includes/custom.php';
require_once 'engine/includes/custom-filters.php';

/**
 * Set this to true to put Infinity into developer mode. Developer mode will refresh the dynamic.css on every page load.
 */
define('INFINITY_DEV_MODE', true);

/* This script contains Buddypress customizations for MLA group types. */ 

/* MLA edits to BP literals. 
/* These don't seem to have any effect, since slugs appear to be handled by WP pages, 
 * but I'll keep these in just in case they affect something else elsewhere.
 */ 
define('BP_FRIENDS_SLUG', 'contacts');
define('BP_BLOGS_SLUG', 'sites');

// Change "en_US" to your locale
define('BPLANG', 'en_US');
if (file_exists(WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo')) {
	load_textdomain('buddypress', WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo');
}

/* This function filters out membership activities from the group activity stream, 
 * so that "so-and-so joined the group X" doesn't clutter the activity stream. 
 */ 

/* this is a jQuery hack to check the checkbox on 
 * Create a Group → 4. Forum → Group Forum → 
 * “Yes. I want this Group to have a forum” 
 * by default. 
 */ 
function mla_check_create_forum_for_new_group() {
	if(wp_script_is('jquery', 'done')) { ?>
		<script type="text/javascript">
		jq('#bbp-create-group-forum').prop('checked', true);
		</script>
<?php }
}
add_action('wp_footer', 'mla_check_create_forum_for_new_group');

/* disable visual editor entirely, for everyone */ 
/* add_filter('user_can_richedit' , '__return_false', 50); */ 

/* 
 * Remove redundant email status button in group headings; 
 * this is handled by the group tab "Email Options" 
 */
remove_action('bp_group_header_meta', 'ass_group_subscribe_button');

/* 
 * Remove forum subscribe link. Users are already subscribed to the forums 
 * when they subscribe to the group. Having more fine-grained control over 
 * subscriptions is unnecessary and confusing.
 */ 
function mla_remove_forum_subscribe_link($link){ 
	return ""; //making this empty so that it will get rid of the forum subscribe link
} 
add_filter('bbp_get_forum_subscribe_link', 'mla_remove_forum_subscribe_link');

/* 
 * Remove subscription link from groups directory. 
 * Because we're about to rewrite it!
 * Get ready for the magic.  
 */ 
remove_action('bp_directory_groups_actions', 'ass_group_subscribe_button');

function mla_ass_group_subscribe_button() {
	global $bp, $groups_template;

	if(! empty( $groups_template)) {
		$group =& $groups_template->group;
	}
	else {
		$group = groups_get_current_group();
	}

	if (!is_user_logged_in() || !empty($group->is_banned) || !$group->is_member)
		return;

	// if we're looking at someone elses list of groups hide the subscription
	if (bp_displayed_user_id() && (bp_loggedin_user_id() != bp_displayed_user_id()))
		return;

	$group_status = ass_get_group_subscription_status(bp_loggedin_user_id(), $group->id);

	if ($group_status == 'no')
		$group_status = NULL;

	$status_desc = __('Your email status is ', 'bp-ass');
	$link_text = __('change', 'bp-ass');
	$gemail_icon_class = ' gemail_icon';
	$sep = '';

	if (!$group_status) {
		//$status_desc = '';
		$link_text = __('Get email updates', 'bp-ass');
		$sep = '';
	}

	$status = ass_subscribe_translate($group_status);

	$notifications_url = home_url().'/groups/'.groups_get_slug($group->id).'/notifications/'; 
	?>

	<div class="group-subscription-div">
		<a class="group-subscription-options-link" id="gsublink-<?php echo $group->id; ?>" href="<?php echo $notifications_url; ?>" title="<?php _e('Change your email subscription options for this group','bp-ass');?>"><span class="group-subscription-status<?php echo $gemail_icon_class ?>" id="gsubstat-<?php echo $group->id; ?>"><?php echo $status; ?></span> <?php echo $sep; ?></a>
	</div>

	<?php
}

add_action('bp_directory_groups_actions', 'mla_ass_group_subscribe_button');

/* Remove forum title, since in our use cases forum titles have the same names as
 * their parent groups, and users see a redundant title on group forums pages. 
 */
function mla_remove_forum_title($title) { 
} 
add_filter('bbp_get_forum_title', 'mla_remove_forum_title'); 

/* 
 * Remove profile group tab from edit profile page when there's only one profile
 * group. I just says "Profile" and is kind of confusing. 
 */ 
function mla_remove_profile_group_tab($tabs, $groups) { 
	if (count($groups) > 1) { 
		return $tabs; 
	} else { 
		return; 
	} 
} 
add_filter('xprofile_filter_profile_group_tabs', 'mla_remove_profile_group_tab'); 

/** 
 * Remove "What's new in ___, Jonathan?" 
 * Taken from BP-Group-Announcements. 
 */ 
// Disable the activity update form on the group home page. Props r-a-y
add_action( 'bp_before_group_activity_post_form', create_function( '', 'ob_start();' ), 9999 );
add_action( 'bp_after_group_activity_post_form', create_function( '', 'ob_end_clean();' ), 0 );

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

// force reload css on new versions
function my_wp_default_styles($styles)
{
        //use epoch time for version
        $styles->default_version = date('U');
}
add_action("wp_default_styles", "my_wp_default_styles");
