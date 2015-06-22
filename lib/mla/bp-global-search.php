<?php
/*
 * Customizations for the BuddyPress Global Search plugin.
 */

/*
 * Set default search items. This is a reasonable default
 * so that if the admin forgets to set these searchable items,
 * bp-global-search will still search roughly the things we want.
 */
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

/* Rename 'Forums' to 'Discussions'
 */
function mla_rename_forums( $item ) {
	if ( 'forums' == $item ) {
		return 'Discussions';
	} elseif ( 'groupblogs' == $item ) {
		return 'Site posts';
	} else {
		return ucfirst( $item ); // Title Case
	}
}
add_filter( 'bboss_global_search_label_search_type', 'mla_rename_forums' );
