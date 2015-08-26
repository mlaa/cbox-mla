<?php
/* This script contains Buddypress customizations for MLA group types. */ 

/* This part adds custom MLA filters to the BuddyPress 
 * Groups directory, allowing users to filter the groups by type
 * (committees, discussion groups, etc) and by visibility
 * (private, public, hidden, etc). 
 */
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
	$str .= '<option value="prospective_forums">Prospective Forums</option>';
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
			case "prospective_forums": 
				$sql = $wpdb->prepare($sql_stub, 'F');
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
	_log( "adding type filter:", $type_filter ); 
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

function mla_reset_filter_cookies() { 
	
	// If we have jQuery, set filter cookies to 'all' so that
	// we don't have persistent filters across pages.  
	if( wp_script_is( 'jquery', 'done' ) ): ?> 

		<script type="text/javascript">
			// Reset group filters cookies to 'all'.
			jq.cookie('bp-groups-type','all',{ path: '/' });
			jq.cookie('bp-groups-filter','active',{ path: '/' });
			jq.cookie('bp-groups-scope','all',{ path: '/' });
			jq.cookie('bp-groups-status','all',{ path: '/' });
			jq.cookie('bp-members-scope','all',{ path: '/' });
			jq.cookie('bp-members-filter','active',{ path: '/' });
			jq.cookie('bp-activity-scope','all',{ path: '/' });
			jq.cookie('bp-activity-filter','-1',{ path: '/' });
		</script> 

	<?php endif;

	// Now set them in PHP, since PHP will have already 
	// loaded the cookie vars before Javascript has had a 
	// chance to set them.  
	$_COOKIE['bp-groups-type'] = 'all';
	$_COOKIE['bp-groups-filter'] = 'active';
	$_COOKIE['bp-groups-status'] = 'all';
	$_COOKIE['bp-groups-scope'] = 'all'; 
	$_COOKIE['bp-members-scope'] = 'all';
	$_COOKIE['bp-members-filter'] = 'active';
	$_COOKIE['bp-activity-scope'] = 'all';
	$_COOKIE['bp-activity-filter'] = '-1';
} 
add_action('wp_head', 'mla_reset_filter_cookies'); 

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
			case "F": 
				$mla_type = "Prospective Forum";
				break;
		} 
		$type = $mla_type; 
		$type .= $visibility; 
	} 
	return $type; 	
} 
add_filter('bp_get_group_type', 'mla_group_type_filter'); 

/* 
 * Filter membership activities out of AJAX querystrings.
 * This effectively makes it so that the "Everything" filter
 * of the Activities page (the default filter that's set when 
 * no other filters are set) is actually set to filter out membership 
 * activities. This way, the activity list doesn't overflow with 
 * activities like "User ___ joined the group ___." 
 */ 
function mla_filter_querystring( $query_string, $object, $object_fitler, $object_score, $object_page, $object_search_terms, $object_extras ) { 
	if ( 'activity' == $object ) { 
		// let's just filter when we're on the activity page
		if ( strpos( $query_string, 'type=' ) === false ) { 
			// if there's no type filter, then the type 
			// filter is really "everything." In that case, 
			// hijack it and make it not-so-everything 
			// (i.e. remove membership data) 
			$my_querystring = "type=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create,new_deposit,new_group_deposit&action=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create,new_deposit,new_group_deposit";
			if ( strlen( $query_string ) > 0 ) { 
				$query_string = $my_querystring . '&'. $query_string; 
			} else { 
				$query_string = $my_querystring; 
			} 
		} 
	} 
	return $query_string; 
} 
add_filter( 'bp_dtheme_ajax_querystring', 'mla_filter_querystring', 10, 7 ); 
