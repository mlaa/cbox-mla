<?php
// customizations for the BuddyPress Global Search plugin

function mla_filter_bp_global_search_items( $search_items ) {
	if ( empty( $search_items ) ) {
		$search_items = array(
			'activities',
			'forums',
			'posts',
			'members',
			'groups',
			'member_field_user_login',
			'member_field_user_nicename',
		);
	}
	return $search_items;
}
add_filter( 'buddyboss_global_search_option_items-to-search', 'mla_filter_bp_global_search_items', 5 );