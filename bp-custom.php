<?php

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
add_action( 'bp_groups_directory_group_types', 'mla_group_directory_status_filter');


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

$status_filter = '';
function add_status_filter() {
	global $status_filter;
	if($_COOKIE['bp-group-status']!='all')	$status_filter = new BP_Groups_Status_Filter();
}

function remove_status_filter() {
	global $status_filter;
	if($_COOKIE['bp-group-status']!='all') $status_filter->remove_filters();
}
add_action('bp_before_groups_loop','add_status_filter');
add_action('bp_after_groups_loop','remove_status_filter');

function status_filter_js() {
	 if( wp_script_is( 'jquery', 'done' ) ) { ?>
	<script type="text/javascript">
		jq('li.filter-status select').val(jq.cookie('bp-groups-status'));
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
add_action('wp_footer', 'status_filter_js');


?>
