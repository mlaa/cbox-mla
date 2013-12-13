<?php
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
				$sql = $wpdb->prepare("SELECT DISTINCT group_id from  {$bp->groups->table_name}_groupmeta WHERE group_id NOT IN (SELECT DISTINCT group_id FROM {$bp->groups->table_name}_groupmeta WHERE meta_key = 'mla_oid')");
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

function mla_group_type_filter($type, $group) { 
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

function mla_filter_out_membership_activities($a, $activities, $args){ 
	/* I don't know what is going wrong here. 
	 * The loop below appears to edit the activities, but then
	 * they either aren't passed correctly or the resulting activities list
	 * isn't displayed correctly. 
	 */ 

 
//	error_log("hello debugging!"); 
  	error_log(print_r($args)); 	
	$page = $args['page']; 
	$per_page = $args['per_page']; 
	$args['page'] = 1; 
	$args['per_page'] = 9999; //We have to edit the full list, otherwise we'd just be editing the first 20 items 
	global $activities_template; 
	$activities_template = new BP_Activity_Template($args); //rebuild activities based on new args
	$activities = $activities_template; 
	$i=0; 
//	error_log(print_r($activities->activities)); 
	foreach ($activities->activities as $activity) { 
//		error_log(print_r($activity)); 
		if ($activity->type == 'joined_group') { 
			unset($activities->activities[$i]); //get rid of membership activity items
			$activities->activity_count = $activities->activity_count - 1; //change the count to reflect this 
			$activities->total_activity_count = $activities->total_activity_count - 1; 
		} 
		++$i; 
	} 
	$activities->activities = array_values($activities->activities); 
	error_log(print_r($activities_template)); 
  
	return $activities; // this boolean needs to go through for has_activities() to work 
	//per http://buddypress.org/support/topic/notice-for-plugin-developers-using-bp_has_activities-filter/
}

//add_filter('bp_has_activities', 'mla_filter_out_membership_activities', 10, 3); 

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
