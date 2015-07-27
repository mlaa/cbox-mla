<?php
/* This script contains Buddypress customizations for MLA group types. */

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
			case "forums":
				$sql = "SELECT DISTINCT group_id FROM {$bp->groups->table_name}_groupmeta WHERE meta_key = 'mla_oid' AND ( LEFT( meta_value, 1 ) = 'D' OR LEFT( meta_value, 1) = 'G' )";
				break;
			case "members-groups":
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
add_action('bp_before_groups_loop','add_status_filter', 20);
add_action('bp_after_groups_loop','remove_status_filter');
add_action('bp_before_groups_loop','add_type_filter', 20);
add_action('bp_after_groups_loop','remove_type_filter');

function type_filter_js() {
	if( wp_script_is( 'jquery', 'done' ) ) { ?>
	<script type="text/javascript">

	jq(window).on('popstate', function(e) {
		// Get $_GET-like queries from the URL
		function get_query(){
			var url = location.search;
			var qs = url.substring(url.indexOf('?') + 1).split('&');
			for(var i = 0, result = {}; i < qs.length; i++){
				qs[i] = qs[i].split('=');
				result[qs[i][0]] = decodeURIComponent(qs[i][1]);
			}
			return result;
		}
		var $_GET = get_query();

		// Check to see if we're looking at the groups directory.
		if ( jq('body').is('.groups, .directory') ) {
			alert( 'Welp, I guess this is the groups directory.' );
			var object = 'groups';

			// If there are URL flags, AJAX-filter content using them.
			if ( $_GET['type'].length ) {
				var object = 'groups';
				var status = $_GET['type'];
				if ( 'groups-personal' == status ) {
					var scope = 'groups';
				} else {
					var scope = 'all';
				}
				var filter = ''; var search_terms = '';
				jq.cookie('bp-groups-type',status,{ path: '/' });
				bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
			}

			// Me set cookies.
			jq.cookie('bp-groups-type',e.state,{ path: '/' });
		}

		// TODO: factor this out and put it with the other dashboard filter functions
		if ( jq('body').is('.page, .dashboard') ) {

			// If there are URL flags, AJAX-filter content using them.
			if ( $_GET['type'].length ) {
				alert( $_GET['type'] );
				var object = 'activity';
				var status = $_GET['type'];
				var search_terms = '';
				jq.cookie('bp-activity-type',status,{ path: '/' });
				if ( 'newsfeed' == filter ) {
					// This is the newsfeed, so filter by my groups,
					// my contacts, and official MLA stuff.
					var scope = 'friends,groups,mla';
					var filter = '';
				} else {
					var scope = '';
					var filter = status;
				}
				console.log( 'filtering request.' );
				console.log( 'filter:' );
				console.debug( filter );
				console.log( 'scope:' );
				console.debug( scope );

				bp_activity_request( scope, filter );
			}

			// Me set cookies.
			jq.cookie('bp-activity-type',e.state,{ path: '/' });
		}


	});

	jq('#groups-directory-form nav.secondary li.mla-tab').click( function() {
		var object = 'groups';
		var status = this.id;
		if ( 'groups-personal' == status ) {
			var scope = 'personal';
		} else {
			var scope = 'all';
		}
		var filter = jq('select#groups-order-by-type').val();
		var search_terms = '';

		jq.cookie('bp-groups-type',status,{ path: '/' });

		if ( jq('.dir-search input').length )
			search_terms = jq('.dir-search input').val();

		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );

		var url = [location.protocol, '//', location.host, location.pathname].join('');

		history.pushState( { type: status }, "", url + '?type=' + status );

		alert( 'heyo!');

		// Make sure the tabs are highlighted correctly.
		jq(this).parent().addClass('selected');

		return false;

	});
	</script>
<?php }
}
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
 bp* to the Buddypress Group Type display
 */

function mla_group_type_filter($type, $group="") {
	global $groups_template;

	if ( empty( $group ) )
		$group =& $groups_template->group;

	$mla_oid = ( groups_get_groupmeta( $group->id, 'mla_oid' ) );
	if ( !empty( $mla_oid ) ) {
		$mla_type_let = $mla_oid[0];

		switch($mla_type_let) {
			case "M":
				$mla_type = "Committee";
				break;
			case "D":
				// Formerly "Division";
				$mla_type = 'Forum';
				break;
			case "G":
				// Formerly "Discussion Group";
				$mla_type = 'Forum';
				break;
			case "F":
				$mla_type = "Prospective Forum";
				break;
		}
		$type = $mla_type;
	}
	return $type;
}
add_filter('bp_get_group_type', 'mla_group_type_filter');

function mla_filter_groups_from_url() {
	if ( array_key_exists( 'type', $_GET ) ) {
		$_COOKIE['bp-groups-type'] = $_GET['type'];
	}
}
add_action( 'bp_before_groups_loop', 'mla_filter_groups_from_url', 10 );
