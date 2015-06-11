<?php
/*
 * Functions for customizing bp-groupblog.
 */

/**
 * Override default BP Groupblog nav item functionality
 */
function my_custom_groupblog_setup_nav() {

	// only add for groups
	if ( !bp_is_group() ) return;

	// only act if blog not embedded in group template
	$checks = get_site_option('bp_groupblog_blog_defaults_options');
	if ( $checks['deep_group_integration'] ) return;

	// get current group
	$current_group = groups_get_current_group();

	if (

		// existing groupblog logic
		bp_groupblog_is_blog_enabled( $current_group->id )

		OR

		// mahype's fixes for the non-appearance of the groupblog tab
		// with the addition of a check for the array key to prevent PHP notices.
		(
			isset( $_POST['groupblog-create-new'] ) AND
			$_POST['groupblog-create-new'] == 'yes'
		)

	) {

		// access bp
		global $bp;

		// group link
		$group_link = bp_get_group_permalink( $current_group );

		// parent slug
		$parent_slug = isset( $bp->bp_nav[$current_group->slug] ) ?
					   $current_group->slug :
					   $bp->groups->slug;

		// add a filter so plugins can change the name
		$name = __( 'Blog', 'groupblog' );
		$name = apply_filters( 'bp_groupblog_subnav_item_name', $name );

		// add a filter so plugins can change the slug
		$slug = apply_filters( 'bp_groupblog_subnav_item_slug', 'blog' );

		// is this a private group?
		if ( $current_group->status == 'private' ) {

			// get group blog details
			$blog_id = get_groupblog_blog_id( $current_group->id );
			$details = get_blog_details( $blog_id );

			// is the group blog public?
			if( (bool) $details->public == true ) {

				// override slug
				$slug = $details->path;

				// override group link
				$group_link = $details->siteurl;

			}

		}

		// define subnav item
		bp_core_new_subnav_item(
			array(
				'name' => $name,
				'slug' => $slug,
				'parent_url' => $group_link,
				'parent_slug' => $parent_slug,
				'screen_function' => 'groupblog_screen_blog',
				'position' => 32,
				'item_css_id' => 'group-blog'
			)
		);
	}

}

// do we have the BP Groupblog action?
if ( has_action( 'bp_setup_nav', 'bp_groupblog_setup_nav' ) ) {

	// remove BP Groupblog's action
	remove_action( 'bp_setup_nav', 'bp_groupblog_setup_nav' );

	// replace with our own
	add_action( 'bp_setup_nav', 'my_custom_groupblog_setup_nav' );
}
