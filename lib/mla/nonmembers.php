<?php
/*
 * Functions for handling non-members.
 */

/* This function is triggered when a super-admin manually
 * adds a new member via Network Dashboard -> Users -> Add New.
 * Users with the flag `mla_nonmember` get to skip verification
 * with the Oracle database.
 */
function mla_set_nonmember_flag( $user_id ) {
	update_user_meta( $user_id, 'mla_nonmember', 'yes' );
}
add_action( 'wpmu_new_user', 'mla_set_nonmember_flag' );
