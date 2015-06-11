<?php
/*
 * Functions that handle the behavior of MLA committees.
 */

function mla_is_group_committee( $group_id = 0 ) {
	// use the current group if we're not passed one.
	if ( 0 == $group_id ) $group_id = bp_get_current_group_id();

	// if mla_oid starts with "M," it's a committee
	return ('M' == substr( groups_get_groupmeta( $group_id, 'mla_oid' ), 0, 1 ) ) ? true : false;
}

/* Remove the button "Request Membership" from committees. */
function mla_remove_membership_request_from_committees() {
	if ( mla_is_group_committee() ) {
		bp_core_remove_subnav_item( bp_get_current_group_slug(), 'request-membership' );
	}
}
add_action( 'bp_setup_nav', 'mla_remove_membership_request_from_committees', 100 );

/* Don't allow members to invite others to committees. */
function mla_disallow_invites_to_committees( $user_can_invite, $group_id, $invite_status ) {
	return ( mla_is_group_committee( $group_id ) ? false : $user_can_invite );
}
add_filter( 'bp_groups_user_can_send_invites', 'mla_disallow_invites_to_committees', 10, 3 );
