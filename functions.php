<?php
//Custom functionality for your CBOX Child Theme.
require_once( 'engine/includes/custom.php' );

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


// [COMMUNITY STRUCTURE] Add private/public filter to group lists

function mla_group_directory_status_filter() {
	$str  = '<li class="last filter-status" style="margin-left: 1em; float: left;"><label for="groups-filter-by">Visibility:</label>';
	$str .= '<select id="groups-filter-by">';
	$str .= '<option value="all">All</option>';
	$str .= '<option value="public">Public</option>';
	$str .= '<option value="private">Private</option>';
	if (is_admin() || is_super_admin()) {
		$str .= '<option value="hidden">Hidden</option>';
	} 
	$str .= '</select></li>';
	echo $str;
}
function mla_group_directory_type_filter() { 
	$str  = '<li class="last filter-type" style="margin-left: 1em; float: left;"><label for="groups-filter-by-type">Type:</label>';
	$str .= '<select id="groups-filter-by-type">';
	$str .= '<option value="all">All</option>';
	$str .= '<option value="committees">Committees</option>';
	$str .= '<option value="divisions">Divisions</option>';
	$str .= '<option value="discussion_groups">Discussion Groups</option>';
	$str .= '<option value="other">Other</option>';
	$str .= '</select></li>';
	echo $str;
} 
add_action( 'bp_groups_directory_group_types', 'mla_group_directory_status_filter');
add_action( 'bp_groups_directory_group_types', 'mla_group_directory_type_filter');

class BP_Groups_Status_Filter { 
	protected $status;
	protected $group_ids = array();

	function __construct( ) {

		$this->status = $_COOKIE['bp-groups-status'];
		$this->setup_group_ids();

		add_filter( 'bp_groups_get_paged_groups_sql', array( &$this, 'filter_sql' ) );
		add_filter( 'bp_groups_get_total_groups_sql', array( &$this, 'filter_sql' ) );
	}

	function setup_group_ids() {
		global $wpdb, $bp;
		$sql = $wpdb->prepare( "SELECT id FROM {$bp->groups->table_name} WHERE status = %s", $this->status);
		$this->group_ids = wp_parse_id_list( $wpdb->get_col( $sql ) );
	}

	function get_group_ids() {
		return $this->group_ids;
	}

	function filter_sql( $sql ) {
		$group_ids = $this->get_group_ids();
		if ( empty( $group_ids ) ) {
			return $sql;
		}

		$sql_a = explode( 'WHERE', $sql );
		$new_sql = $sql_a[0] . 'WHERE g.id IN (' . implode( ',', $group_ids ) . ') AND ' . $sql_a[1];

		return $new_sql;
	}

	function remove_filters() {
		remove_filter( 'bp_groups_get_paged_groups_sql', array( &$this, 'filter_sql' ) );
		remove_filter( 'bp_groups_get_total_groups_sql', array( &$this, 'filter_sql' ) );
	}
}

class BP_Groups_Type_Filter extends BP_Groups_Status_Filter { 

	protected $status_type;
	protected $group_ids = array();

	function __construct() { 
		$this->status_type = $_COOKIE['bp-groups-type']; 
		$this->setup_group_ids();

		add_filter( 'bp_groups_get_paged_groups_sql', array( &$this, 'filter_sql' ) );
		add_filter( 'bp_groups_get_total_groups_sql', array( &$this, 'filter_sql' ) );
	} 
	function setup_group_ids() {
		global $wpdb, $bp;
		$sql_stub = "SELECT group_id FROM {$bp->groups->table_name}_groupmeta WHERE meta_key = 'mla_oid' AND LEFT(meta_value, 1) =  %s"; 
		switch ( $this->status_type ) { 
			case "committees": 
				$sql = $wpdb->prepare($sql_stub, 'M');
				break; 
			case "divisions": 
				$sql = $wpdb->prepare($sql_stub, 'D');
				break; 
			case "discussion_groups": 
				$sql = $wpdb->prepare($sql_stub, 'G');
				break; 
			case "other": 
				$sql = "SELECT DISTINCT group_id from  {$bp->groups->table_name}_groupmeta WHERE group_id NOT IN (SELECT DISTINCT group_id FROM {$bp->groups->table_name}_groupmeta WHERE meta_key = 'mla_oid')";
				break; 
		} 
		$this->group_ids = wp_parse_id_list( $wpdb->get_col( $sql ) );
	}
} 

$status_filter = '';
function add_status_filter() {
	global $status_filter;
	if($_COOKIE['bp-group-status']!='all')	$status_filter = new BP_Groups_Status_Filter();
}
function remove_status_filter() {
	global $status_filter;
	if($_COOKIE['bp-group-status']!='all') $status_filter->remove_filters();
}
$type_filter = ''; 
function add_type_filter() { 
	global $type_filter; 
	if($_COOKIE['bp-group-type']!='all') $type_filter = new BP_Groups_Type_Filter();
} 
function remove_type_filter() {
	global $type_filter;
	if($_COOKIE['bp-group-type']!='all') $type_filter->remove_filters();
} 
add_action('bp_before_groups_loop','add_status_filter');
add_action('bp_after_groups_loop','remove_status_filter');
add_action('bp_before_groups_loop','add_type_filter');
add_action('bp_after_groups_loop','remove_type_filter');

function status_filter_js() {
	if( wp_script_is( 'jquery', 'done' ) ) { ?>
	<script type="text/javascript">
	if (jq.cookie('bp-groups-status')) { 
		jq('li.filter-status select').val(jq.cookie('bp-groups-status'));
	}
	jq('li.filter-status select').change( function() {

		if ( jq('.item-list-tabs li.selected').length )
			var el = jq('.item-list-tabs li.selected');
		else
			var el = jq(this);

		var css_id = el.attr('id').split('-');
		var object = css_id[0];
		var scope = css_id[1];
		var status = jq(this).val();
		var filter = jq('select#groups-order-by').val();
		var search_terms = '';

		jq.cookie('bp-groups-status',status,{ path: '/' });

		if ( jq('.dir-search input').length )
			search_terms = jq('.dir-search input').val();

		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );

		return false;

	});
	</script>
<?php }
}
function type_filter_js() {
	if( wp_script_is( 'jquery', 'done' ) ) { ?>
	<script type="text/javascript">
	if (jq.cookie('bp-groups-status')) { 
		jq('li.filter-type select').val(jq.cookie('bp-groups-type'));
	} 
	jq('li.filter-type select').change( function() {

		if ( jq('.item-list-tabs li.selected').length )
			var el = jq('.item-list-tabs li.selected');
		else
			var el = jq(this);

		var css_id = el.attr('id').split('-');
		var object = css_id[0];
		var scope = css_id[1];
		var status = jq(this).val();
		var filter = jq('select#groups-order-by-type').val();
		var search_terms = '';

		jq.cookie('bp-groups-type',status,{ path: '/' });

		if ( jq('.dir-search input').length )
			search_terms = jq('.dir-search input').val();

		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );

		return false;

	});
	</script>
<?php }
}
add_action('wp_footer', 'status_filter_js');
add_action('wp_footer', 'type_filter_js');

/* This part adds the MLA Group type
 * (i.e. Committee, Division, Discussion Group)
 * to the Buddypress Group Type display 
 */ 

function mla_group_type_filter($type, $group="") { 
	global $groups_template; 

	if ( empty( $group ) )
		$group =& $groups_template->group;

	$mla_oid = (groups_get_groupmeta($group->id, 'mla_oid')); 
	if (!empty($mla_oid)) { 
		$mla_type_let = $mla_oid[0]; 
		$type = substr($type, 0, -6); //this avoids redundancy like "Discussion Group (Private Group)" 
		$visibility = " (".$type.")";

		switch($mla_type_let) { 
			case "M": 
				$mla_type = "Committee"; 	
				break; 
			case "D": 
				$mla_type = "Division"; 	
				break; 
			case "G": 
				$mla_type = "Discussion Group"; 	
				break; 
		} 
		$type = $mla_type; 
		$type .= $visibility; 
	} 
	return $type; 	
} 
add_filter('bp_get_group_type', 'mla_group_type_filter'); 

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
?>
