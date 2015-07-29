<?php
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
		if ( ( strpos( $query_string, 'type=' ) === false ) ) { 
			// if there's no type filter, then the type
			// filter is really "everything." In that case,
			// hijack it and make it not-so-everything
			// (i.e. remove membership data)
			$query_string = "type=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create,new_deposit, new_member&action=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create,new_deposit,new_member&scope=friends,groups,mla";
		}
	}
	return $query_string;
}
add_filter( 'bp_legacy_theme_ajax_querystring', 'mla_filter_querystring', 10, 7 );

/**
 * Don't count joining a group as a "recent activity." This
 * makes it so that the "recently active groups" is a little more
 * useful, since it doesn't show just groups that have had membership
 * changes recently.
 *
 * This feature requires Buddypress >= 2.3.0.
 */
remove_action( 'groups_join_group',           'groups_update_last_activity' );
remove_action( 'groups_leave_group',          'groups_update_last_activity' );

/*
 * Don't show secondary avatars in activity items. These are little icons next
 * to group and member names. They clutter up activity items and generally make everything
 * look pretty messy. Get rid of them!
 *
 * Props to @r-a-y for the fix, from this BP support thread:
 * https://buddypress.org/support/topic/how-can-i-disable-secondary-avatars-in-activity-items/#post-240959
 */
function my_remove_secondary_avatars( $bp_legacy ) {
    remove_filter( 'bp_get_activity_action_pre_meta', array( $bp_legacy, 'secondary_avatars' ), 10, 2 );
}
add_action( 'bp_theme_compat_actions', 'my_remove_secondary_avatars' );

/**
 * Output the activity delete link.
 * Now with a fresh icon!
 *
 * @uses bp_get_activity_delete_link()
 */
function mla_bp_activity_delete_link() {
	echo mla_bp_get_activity_delete_link();
}

	/**
	 * Return an iconified activity delete link.
	 *
	 */
	function mla_bp_get_activity_delete_link() {

		$url   = bp_get_activity_delete_url();
		$class = 'delete-activity';

		// Determine if we're on a single activity page, and customize accordingly
		if ( bp_is_activity_component() && is_numeric( bp_current_action() ) ) {
			$class = 'delete-activity-single';
		}

		$link = '<a href="' . esc_url( $url ) . '" class="button item-button bp-secondary-action ' . $class . ' confirm" rel="nofollow">' .'<span class="dashicons dashicons-trash"></span></a>';

		/**
		 * Filters the activity delete link.
		 *
		 * @since BuddyPress (1.1.0)
		 *
		 * @param string $link Activity delete HTML link.
		 */
		return apply_filters( 'bp_get_activity_delete_link', $link );
	}
