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
require_once 'engine/includes/bp-ges-custom.php';

/**
 * Set this to true to put Infinity into developer mode. Developer mode will refresh the dynamic.css on every page load.
 */
define( 'INFINITY_DEV_MODE', true );

/* This script contains Buddypress customizations for MLA group types. */

/* MLA edits to BP literals.
/* These don't seem to have any effect, since slugs appear to be handled by WP pages,
 * but I'll keep these in just in case they affect something else elsewhere.
 */
define( 'BP_FRIENDS_SLUG', 'contacts' );
define( 'BP_BLOGS_SLUG', 'sites' );

// Change "en_US" to your locale
define( 'BPLANG', 'en_US' );
if ( file_exists( WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo' ) ) {
	load_textdomain( 'buddypress', WP_LANG_DIR . '/buddypress-' . BPLANG . '.mo' );
}

/* this is a jQuery hack to check the checkbox on
 * Create a Group → 4. Forum → Group Forum →
 * “Yes. I want this Group to have a forum”
 * by default.
 */
function mla_check_create_forum_for_new_group() {
	if ( wp_script_is( 'jquery','done' ) ) { ?>
		<script type="text/javascript">
		jq('#bbp-create-group-forum').prop('checked', true);
		</script>
  <?php }
}
add_action( 'wp_footer', 'mla_check_create_forum_for_new_group' );

/* disable visual editor entirely, for everyone */
/* add_filter('user_can_richedit' , '__return_false', 50); */

/*
 * Remove redundant email status button in group headings;
 * this is handled by the group tab "Email Options"
 */
remove_action( 'bp_group_header_meta', 'ass_group_subscribe_button' );

/*
 * Don't update group last activy date if members join or leave.
 */
remove_action( 'groups_join_group', 'groups_update_last_activity' );
remove_action( 'groups_leave_group', 'groups_update_last_activity' );

/*
 * Remove forum subscribe link. Users are already subscribed to the forums
 * when they subscribe to the group. Having more fine-grained control over
 * subscriptions is unnecessary and confusing.
 */
function mla_remove_forum_subscribe_link( $link ){
	return ''; //making this empty so that it will get rid of the forum subscribe link
}
add_filter( 'bbp_get_forum_subscribe_link', 'mla_remove_forum_subscribe_link' );

/* Remove forum title, since in our use cases forum titles have the same names as
 * their parent groups, and users see a redundant title on group forums pages.
 */
function mla_remove_forum_title( $title ) {
}
add_filter( 'bbp_get_forum_title', 'mla_remove_forum_title' );

/*
 * Remove profile group tab from edit profile page when there's only one profile
 * group. I just says "Profile" and is kind of confusing.
 */
function mla_remove_profile_group_tab( $tabs, $groups ) {
	if ( count( $groups ) > 1 ) {
		return $tabs;
	} else {
		return;
	}
}
add_filter( 'xprofile_filter_profile_group_tabs', 'mla_remove_profile_group_tab' );

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
		'subnav_slug' => 'notifications',
	);

	bp_core_new_nav_default( $args );

}
add_action( 'bp_setup_nav', 'change_settings_subnav', 5 );

function remove_general_subnav() {
	global $bp;
	bp_core_remove_subnav_item( $bp->settings->slug, 'general' );
}

add_action( 'wp', 'remove_general_subnav', 2 );

// remove portfolio subnav area from member activity area settings tab.
// This page just had lots of visibility settings for CACAP profile areas,
// but they weren't working properly, and didn't include "free entry" areas.
function mla_remove_portfolio_subnav() {
	global $bp;
	bp_core_remove_subnav_item( $bp->settings->slug, 'profile' );
}

add_action( 'wp', 'mla_remove_portfolio_subnav', 2 );


/*
 * Remove misbehaving forums tab on profile pages.
 */

function remove_forums_nav() {
	bp_core_remove_nav_item( 'forums' );
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
function my_wp_default_styles( $styles )
{
	// Reload stylesheet every time the file has been modified.
	$styles->default_version = filemtime( get_stylesheet_directory() . '/style.css' );
}
add_action( 'wp_default_styles', 'my_wp_default_styles' );

function mla_remove_name_from_edit_profile($cols) {
	// Assuming "1" is going to be "name."
	// We have to rebuild the array, too.
	$cols['left'] = array_values( array_diff( $cols['left'], array( 1 ) ) );
	return $cols;
}
add_filter('cacap_header_edit_columns', 'mla_remove_name_from_edit_profile');

function mla_is_group_committee( $group_id = 0 ) {
	// use the current group if we're not passed one.
	if ( 0 == $group_id ) $group_id = bp_get_current_group_id();

	// if mla_oid starts with "M," it's a committee
	return ('M' == substr( groups_get_groupmeta( $group_id, 'mla_oid' ), 0, 1 ) ) ? true : false;
}

/* Remove the button "Request Membership" from committees. */
function mla_remove_membership_request_from_committees() {
	if ( mla_is_group_committee() ) {
		bp_core_remove_subnav_item( bp_get_current_group_slug(), 'request-membership' );
	}
}
add_action( 'bp_setup_nav', 'mla_remove_membership_request_from_committees', 100 );

/* Don't allow members to invite others to committees. */
function mla_disallow_invites_to_committees( $user_can_invite, $group_id, $invite_status ) {
	return ( mla_is_group_committee( $group_id ) ? false : $user_can_invite );
}
add_filter( 'bp_groups_user_can_send_invites', 'mla_disallow_invites_to_committees', 10, 3 );

// remove default profile link handling so we can override it below
remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data' );

// Custom xprofile interest linkifier that accepts semicolons as delimiters.
function mla_xprofile_filter_link_profile_data( $field_value, $field_type = 'textbox' ) {

	if ( 'datebox' === $field_type ) {
		return $field_value;
	}

	if ( ! strpos( $field_value, ',' ) && !strpos( $field_value, '; ' )  && ( count( explode( ' ', $field_value ) ) > 5 ) ) {
		return $field_value;
	}

	if ( strpos( $field_value, '; ' ) ) {
		$list_type = 'semicolon';
		$values = explode( '; ', $field_value ); // semicolon-separated lists
	} else {
		$list_type = 'comma';
		$values = explode( ',', $field_value ); // comma-separated lists
	}

	if ( ! empty( $values ) ) {
		foreach ( (array) $values as $value ) {
			$value = trim( $value );

			// remove <br>s at the ends of interest lists,
			// so that the final search term works
			$value = preg_replace( '/\<br \/\>$/', '', $value );

			// If the value is a URL, skip it and just make it clickable.
			if ( preg_match( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', $value ) ) {
				$new_values[] = make_clickable( $value );

			// Is not clickable
			} else {

				// More than 5 spaces
				if ( count( explode( ' ', $value ) ) > 5 ) {
					$new_values[] = $value;

				// Less than 5 spaces
				} else {
					if ( preg_match( '/\.$/', $value ) ) { // if it ends in a period
						$value = preg_replace( '/\.$/', '', $value ); // remove the period at the end
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>.'; // but add it back *after* the link.
					} else if ( preg_match( '/\.\<br \/\>/', $value ) ) {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>.<br />';
					} else if ( preg_match( '/\<br \/\>/', $value ) ) {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a><br />';

					} else {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>';
					}
				}
			}
		}

		if ( 'semicolon' == $list_type ) {
			$values = implode( '; ', $new_values );
		} else {
			$values = implode( ', ', $new_values );
		}
	}

	return $values;
}
add_filter( 'bp_get_the_profile_field_value', 'mla_xprofile_filter_link_profile_data', 9, 2 );

function mla_custom_bp_mofile( $mofile, $domain ){
	if ( 'buddypress' == $domain ) {
		$mofile = trailingslashit( WP_LANG_DIR ) . basename( $mofile );
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'mla_custom_bp_mofile', 10, 2 );

/* Load group membership data from member database on
 * group page load.
 */
function mla_update_group_membership_data() {
	if ( class_exists( 'MLAGroup' ) ) {
		$mla_group = new MLAGroup( $debug = false );
		$mla_group->sync();
	}
}
add_action( 'bp_before_group_body', 'mla_update_group_membership_data' );

function mla_update_member_data() {
	if ( class_exists( 'MLAMember' ) ) {
		$mla_member = new MLAMember( $debug = false );
		if ( $mla_member->sync() ) {
			//_log( 'Success! Member data synced.' );
		} else {
			_log( 'Something went wrong while trying to update member info from the member database.' );
		}
	}
}
add_action( 'cacap_header', 'mla_update_member_data' );
add_action( 'bp_before_member_groups_content', 'mla_update_member_data' );

/**
 * Override default BP Groupblog nav item functionality
 */
function my_custom_groupblog_setup_nav() {

	// only add for groups
	if ( !bp_is_group() ) return;

	// only act if blog not embedded in group template
	$checks = get_site_option('bp_groupblog_blog_defaults_options');
	if ( $checks['deep_group_integration'] ) return;

	// get current group
	$current_group = groups_get_current_group();

	if (

		// existing groupblog logic
		bp_groupblog_is_blog_enabled( $current_group->id )

		OR

		// mahype's fixes for the non-appearance of the groupblog tab
		// with the addition of a check for the array key to prevent PHP notices.
		(
			isset( $_POST['groupblog-create-new'] ) AND
			$_POST['groupblog-create-new'] == 'yes'
		)

	) {

		// access bp
		global $bp;

		// group link
		$group_link = bp_get_group_permalink( $current_group );

		// parent slug
		$parent_slug = isset( $bp->bp_nav[$current_group->slug] ) ?
					   $current_group->slug :
					   $bp->groups->slug;

		// add a filter so plugins can change the name
		$name = __( 'Blog', 'groupblog' );
		$name = apply_filters( 'bp_groupblog_subnav_item_name', $name );

		// add a filter so plugins can change the slug
		$slug = apply_filters( 'bp_groupblog_subnav_item_slug', 'blog' );

		// is this a private group?
		if ( $current_group->status == 'private' ) {

			// get group blog details
			$blog_id = get_groupblog_blog_id( $current_group->id );
			$details = get_blog_details( $blog_id );

			// is the group blog public?
			if( (bool) $details->public == true ) {

				// override slug
				$slug = $details->path;

				// override group link
				$group_link = $details->siteurl;

			}

		}

		// define subnav item
		bp_core_new_subnav_item(
			array(
				'name' => $name,
				'slug' => $slug,
				'parent_url' => $group_link,
				'parent_slug' => $parent_slug,
				'screen_function' => 'groupblog_screen_blog',
				'position' => 32,
				'item_css_id' => 'group-blog'
			)
		);
	}

}

// do we have the BP Groupblog action?
if ( has_action( 'bp_setup_nav', 'bp_groupblog_setup_nav' ) ) {

	// remove BP Groupblog's action
	remove_action( 'bp_setup_nav', 'bp_groupblog_setup_nav' );

	// replace with our own
	add_action( 'bp_setup_nav', 'my_custom_groupblog_setup_nav' );
}

/**
 * For group blogs, override the avatar with that of the group
 * Props to Christian Wach and CommentPress for this.
 * See #116.
 *
 */
function mla_get_blog_avatar( $avatar, $blog_id = '', $args ){
	// do we have groupblogs?
	if ( function_exists( 'get_groupblog_group_id' ) ) {
		// get the group id
		$group_id = get_groupblog_group_id( $blog_id );
	}
	// did we get a group for which this is the group blog?
	if ( isset( $group_id ) ) {
		return bp_core_fetch_avatar( array( 'item_id' => $group_id, 'object' => 'group' ) );
	} else {
		return $avatar;
	}
}
add_filter( 'bp_get_blog_avatar', 'mla_get_blog_avatar', 20, 3 );


/* This adds a `title` attribute to thumbnail images which it copies from
 * images' alt text. Not super standard but it accomplishes what we want
 * from the feature slider area.
 */
function mla_thumbnail_html( $attr ) {
	if ( ! isset( $attr['title'] ) ) {
		$attr['title'] = $attr['alt'];
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mla_thumbnail_html', 10 );


// Props to mgmartel and funkolector for the gist:
// https://gist.github.com/mgmartel/4463855
// see mlaa/cbox-mla#95: https://github.com/mlaa/cbox-mla/issues/95
/**
 * Sort users by last name
 *
 * Changes the querystring for the member directory to sort users by their last name
 *
 * @param BP_User_Query $bp_user_query
 */
function alphabetize_by_last_name( $bp_user_query ) {
	if ( 'alphabetical' == $bp_user_query->query_vars['type'] )
		$bp_user_query->uid_clauses['orderby'] = "ORDER BY substring_index(u.display_name, ' ', -1)";
}
add_action( 'bp_pre_user_query', 'alphabetize_by_last_name' );


/**
 * Stop Invite Anyone from adding a nav item to user profiles.
 * This effectively prevents users from being able to invite non-members
 * by email, and simplifies invites overall.
 * See #141 for details.
 */
remove_action( 'bp_setup_nav', 'invite_anyone_setup_nav' );

function mla_filter_gettext( $translated, $original, $domain ) {
	// This is an array of original strings
	// and what they should be replaced with
	$strings = array(
		'Username' => 'User name', // per MLA house style
		'login' => 'log-in', // per MLA house style
		'Group Blog' => 'Site', // bp-groupblog textdomain fix
		'Blogs' => 'Sites', // bp-groupblog textdomain fix
		'Blog' => 'Site', // bp-groupblog textdomain fix
		'Friends' => 'Contacts', // it's a formality thing
		'Friend' => 'Contact',
		'Friendships' => 'Contacts',
		// Add some more strings here
	);

	// See if the current string is in the $strings array
	// If so, replace it's translation
	if ( ! empty( $strings[ $original ] ) ) {
		// This accomplishes the same thing as __()
		// but without running it through the filter again
		$translations = &get_translations_for_domain( $domain );
		$translated = $translations->translate( $strings[ $original ] );
	}

	return $translated;
}
add_filter( 'gettext', 'mla_filter_gettext', 10, 3 );

/**
 * The plugin bp-groupblog doesn't load its textdomain from all the
 * usual locations, i.e. wp-content/languages/plugins, and so we have to load
 * it manually. In addition, we have to translate 'Group Blog' to 'Site' above
 * using mla_filter_gettext.
 *
 * Hooking this to `load_textdomain` apparently causes a feedback loop
 * that breaks everything. Hooking to `wp_footer` doesn't do anything.
 */
function mla_load_textdomains() {
	load_plugin_textdomain( 'groupblog', false );
}
add_action( 'wp_head', 'mla_load_textdomains' );

/**
 * Edits "Site Directory Directory" to "Site Directory"
 * If buddypress fixes #6339, this can be removed.
 * https://buddypress.trac.wordpress.org/ticket/6339
 */
function mla_ixnay_on_redundant_directory_titles( $title, $component ) {
	if ( 'blogs' == $component ) {
		if ( 'Site Directory Directory' == $title ) {
			$title = 'Site Directory'; // shorten to avoid redundancy
		}
	}
	return $title;
}
add_filter( 'bp_get_directory_title', 'mla_ixnay_on_redundant_directory_titles', 10, 2);

function mla_set_nonmember_flag( $user_id ) {
	update_user_meta( $user_id, 'mla_nonmember', 'yes' );
}
add_action( 'wpmu_new_user', 'mla_set_nonmember_flag' );

/**
 * Adds support for user at-mentions to the Suggestions API.
 */
class MLA_Name_Suggestions extends BP_Suggestions {

        /**
        * Default arguments for this suggestions service.
        *
        * @since BuddyPress (2.1.0)
        * @var array $args {
        *     @type int $limit Maximum number of results to display. Default: 200.
        *     @type string $term The suggestion service will try to find results that contain this string.
        *           Mandatory.
        * }
        */
        protected $default_args = array(
                'limit'        => 200,
                'term'         => '',
                'type'         => '',
        );

        /**
        * Validate and sanitise the parameters for the suggestion service query.
        *
        * @return true|WP_Error If validation fails, return a WP_Error object. On success, return true (bool).
        * @since BuddyPress (2.1.0)
        */
        public function validate() {
                $this->args = apply_filters( 'mla_name_suggestions_args', $this->args, $this );

                // Check for invalid or missing mandatory parameters.
                if ( empty( $this->args['term'] ) ) {
                        return new WP_Error( 'missing_requirement' );
                }

                return apply_filters( 'mla_name_suggestions_validate_args', parent::validate(), $this );
        }

        /**
        * Find and return a list of user name suggestions that match the query.
        *
        * @return array|WP_Error Array of results. If there were problems, returns a WP_Error object.
        * @since BuddyPress (2.1.0)
        */
        public function get_suggestions() {

                $user_query = array(
                        'count_total'     => '',  // Prevents total count
                        'populate_extras' => false,
                        'type'            => 'alphabetical',

                        'page'            => 1,
                        'per_page'        => $this->args['limit'],
                        'search_terms'    => $this->args['term'],
                        'search_wildcard' => 'right',
                );

                $user_query = apply_filters( 'mla_suggestions_query_args', $user_query, $this );

                if ( is_wp_error( $user_query ) ) {
                        return $user_query;
                }

                add_action( 'bp_pre_user_query', array( $this, 'mla_query_users_by_name' ) );

                $user_query = new BP_User_Query( $user_query );

                $results = array();
                foreach ( $user_query->results as $user ) {
                        $result        = new stdClass();
                        $result->ID    = $user->user_nicename;
                        $result->image = bp_core_fetch_avatar( array( 'html' => false, 'item_id' => $user->ID ) );
                        $result->name  = bp_core_get_user_displayname( $user->ID );

                        $results[] = $result;
                }

                return apply_filters( 'mla_name_suggestions_get_suggestions', $results, $this );
        }

        /**
        * Query users by name
        *
        * @param BP_User_Query $bp_user_query
        */
        public function mla_query_users_by_name( $bp_user_query ) {

                global $wpdb;

        if ( ! empty( $bp_user_query->query_vars['search_terms'] ) ) {
                        $bp_user_query->uid_clauses['where'] = " WHERE u.ID IN ( SELECT ID FROM {$wpdb->users} WHERE spam = 0 AND deleted = 0 AND user_status = 0 ) AND u.ID IN ( SELECT ID FROM {$wpdb->users} WHERE ( display_name LIKE '%" . ucfirst( strtolower(  $bp_user_query->query_vars['search_terms'] ) ) ."%' ) )";
                        $bp_user_query->uid_clauses['orderby'] = "ORDER BY substring_index(u.display_name, ' ', -1)";
                }

        }

}
add_filter( 'bp_suggestions_services', function() { return 'MLA_Name_Suggestions'; } );

/**
 * Override BP AJAX endpoint for Suggestions API lookups.
 *
 * @since BuddyPress (2.1.0)
 */
function mla_ajax_get_suggestions() {
        if ( ! bp_is_user_active() || empty( $_GET['term'] ) || empty( $_GET['type'] ) ) {
                wp_send_json_error( 'missing_parameter' );
                exit;
        }

        $results = bp_core_get_suggestions( array(
                'term' => sanitize_text_field( $_GET['term'] ),
                'type' => 'mla_members',
                'limit' => '200',
        ) );

        if ( is_wp_error( $results ) ) {
                wp_send_json_error( $results->get_error_message() );
                exit;
        }

        wp_send_json_success( $results );
}
remove_action( 'wp_ajax_bp_get_suggestions', 'bp_ajax_get_suggestions' );
add_action( 'wp_ajax_bp_get_suggestions', 'mla_ajax_get_suggestions' );

/**
 * Enqueue @mentions JS.
 *
*/
function mla_member_mentions_script() {
        if ( ! bp_activity_maybe_load_mentions_scripts() ) {
                return;
        }

        // Special handling for New/Edit screens in wp-admin
        if ( is_admin() ) {
                if (
                        ! get_current_screen() ||
                        ! in_array( get_current_screen()->base, array( 'page', 'post' ) ) ||
                        ! post_type_supports( get_current_screen()->post_type, 'editor' ) ) {
                        return;
                }
        }

	$min = '';
        //$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script( 'mla-mentions', get_stylesheet_directory_uri() . "/assets/js/mentions{$min}.js", array( 'jquery', 'jquery-atwho' ), bp_get_version(), true );
	wp_enqueue_style( 'mla-mentions-css', get_stylesheet_directory_uri() . "/assets/css/mentions{$min}.css", array(), bp_get_version() );

}
remove_action( 'bp_enqueue_scripts', 'bp_activity_mentions_script' );
remove_action( 'bp_admin_enqueue_scripts', 'bp_activity_mentions_script' );
add_action( 'bp_enqueue_scripts', 'mla_member_mentions_script' );
add_action( 'bp_admin_enqueue_scripts', 'mla_member_mentions_script' );

function mla_mentions_script_enable( $current_status ) {
        return $current_status || bp_is_groups_component() || bp_is_new_deposit_page();
}
add_filter( 'bp_activity_maybe_load_mentions_scripts', 'mla_mentions_script_enable' );

function mla_bp_core_override_common_scripts( $current_scripts ) {
        $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$current_scripts['jquery-atwho']['file'] = get_stylesheet_directory_uri() . "/assets/js/jquery.atwho{$min}.js";
	$current_scripts['jquery-caret']['file'] = get_stylesheet_directory_uri() . "/assets/js/jquery.caret{$min}.js";
        return $current_scripts;
}
add_filter( 'bp_core_register_common_scripts', 'mla_bp_core_override_common_scripts' );

