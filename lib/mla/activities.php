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
		if ( strpos( $query_string, 'type=' ) === false ) {
			// if there's no type filter, then the type
			// filter is really "everything." In that case,
			// hijack it and make it not-so-everything
			// (i.e. remove membership data)
			$my_querystring = "type=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create&action=activity_update,new_blog_post,new_blog_comment,created_group,updated_profile,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create";
			if ( strlen( $query_string ) > 0 ) {
				$query_string = $my_querystring . '&'. $query_string;
			} else {
				$query_string = $my_querystring;
			}
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

function my_remove_secondary_avatars( $bp_legacy ) {
    remove_filter( 'bp_get_activity_action_pre_meta', array( $bp_legacy, 'secondary_avatars' ), 10, 2 );
}
add_action( 'bp_theme_compat_actions', 'my_remove_secondary_avatars' );
