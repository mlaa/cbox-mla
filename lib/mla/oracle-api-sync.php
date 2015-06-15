<?php
/*
 * Functions for syncing with the Oracle API, which will update membership
 * information from the MLA member database. Requires the cbox-auth plugin, version >= 2.2.0.
 */

/* Load group membership data from member database on
 * group page load.
 */
function mla_update_group_membership_data() {
	if ( class_exists( 'MLAGroup' ) ) {
		$mla_group = new MLAGroup( $debug = false );
		$mla_group->sync();
	}
}
//add_action( 'bp_before_group_body', 'mla_update_group_membership_data' );

function mla_update_member_data() {
	if ( class_exists( 'MLAMember' ) ) {
		$mla_member = new MLAMember( $debug = false );
		if ( $mla_member->sync() ) {
			//_log( 'Success! Member data synced.' );
		} else {
			_log( 'Something went wrong while trying to update member info from the member database.' );
		}
	}
}
//add_action( 'cacap_header', 'mla_update_member_data' );
//add_action( 'bp_before_member_groups_content', 'mla_update_member_data' );
