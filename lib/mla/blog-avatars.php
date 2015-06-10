<?php
/* Custom blog avatar functions.
 */

/**
 * For group blogs, override the avatar with that of the group
 * Props to Christian Wach and CommentPress for this.
 * See #116.
 *
 */
function mla_group_blog_avatars( $avatar, $blog_id = '', $args ){
	// do we have groupblogs?
	if ( function_exists( 'get_groupblog_group_id' ) ) {
		// get the group id
		$group_id = get_groupblog_group_id( $blog_id );
	}
	// did we get a group for which this is the group blog?
	if ( isset( $group_id ) ) {
		return bp_core_fetch_avatar( array( 'item_id' => $group_id, 'object' => 'group', 'type' => 'full' ) );
	} else {
		return $avatar;
	}
}
add_filter( 'bp_get_blog_avatar', 'mla_group_blog_avatars', 20, 3 );

/* Custom blog avatars for custom people.
 */
function mla_custom_avatars($avatar, $blogid, $params) {
	$custom_avatars = array(
		/* put blog avatars to be overridden here
		 * in the format:
		 * 'blog_id' => array(item_id, 'Person Name'),
		 */
		'36' => array(14, 'Rosemary Feal'),
		'35' => array(596, 'Roland Greene'),
		'153' => array(205, 'Marianne Hirsch'),
	);
	if (isset($custom_avatars[$blogid])) {
		$id = $custom_avatars[$blogid][0];
		$name = $custom_avatars[$blogid][1];
		$avatar = bp_core_fetch_avatar(
			array(
				'item_id' => $id,
				'type' => 'thumb',
				'alt' => 'Profile picture of site author ' . $name,
				'width' => 40,
				'height' => 40,
				'class' => 'avatar'
			)
		);
	}
	return $avatar;
}
add_filter('bp_get_blog_avatar', 'mla_custom_avatars', 10, 3);
