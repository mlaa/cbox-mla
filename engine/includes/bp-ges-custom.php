<?php
/**
 * This file contains custom behaviors for the plugin
 * BuddyPress-Group-Email-Subscription
 */ 

/* 
 * Remove subscription link from groups directory. 
 * Because we're about to rewrite it!
 * Get ready for the magic.  
 */ 
remove_action( 'bp_directory_groups_actions', 'ass_group_subscribe_button' );

function mla_ass_group_subscribe_button() {
	global $bp, $groups_template;

	if ( ! empty( $groups_template ) ) {
		$group =& $groups_template->group;
	}
	else {
		$group = groups_get_current_group();
	}

	if ( ! is_user_logged_in() || ! empty($group->is_banned) || ! $group->is_member)
		return;

	// if we're looking at someone elses list of groups hide the subscription
	if (bp_displayed_user_id() && (bp_loggedin_user_id() != bp_displayed_user_id()))
		return;

	$group_status = ass_get_group_subscription_status( bp_loggedin_user_id(), $group->id );

	if ($group_status == 'no')
		$group_status = NULL;

	$status_desc = __( 'Your email status is ', 'bp-ass' );
	$link_text = __( 'change', 'bp-ass' );
	$gemail_icon_class = ' gemail_icon';
	$sep = '';

	if ( ! $group_status ) {
		//$status_desc = '';
		$link_text = __( 'Get email updates', 'bp-ass' );
		$sep = '';
	}

	$status = ass_subscribe_translate( $group_status );

	$notifications_url = home_url().'/groups/'.groups_get_slug( $group->id ).'/notifications/'; 
	?>

	<div class="group-subscription-div">
		<a class="group-subscription-options-link" id="gsublink-<?php echo esc_attr( $group->id ); ?>" href="<?php echo esc_html( $notifications_url ); ?>" title="<?php _e( 'Change your email subscription options for this group', 'bp-ass' );?>"><span class="group-subscription-status<?php echo esc_attr( $gemail_icon_class ); ?>" id="gsubstat-<?php echo esc_attr( $group->id ); ?>"><?php echo $status; ?></span> <?php echo $sep; ?></a>
	</div>

	<?php
}

add_action( 'bp_directory_groups_actions', 'mla_ass_group_subscribe_button' );


/* Set default email subscription level for new group members to 'daily digest.' */ 
function mla_set_default_email_subscription_level( $level ) { 
	global $bp, $groups_template;
	if ( !$group )
		$group =& $groups_template->group;

	if ( isset( $group->id ) )
		$group_id = $group->id;
	else if ( isset( $bp->groups->new_group_id ) )
		$group_id = $bp->groups->new_group_id;

	$default_subscription =  groups_get_groupmeta( $group_id, 'ass_default_subscription' );
	_log( "Hey! default subscription is: $default_subscription for group_id $group_id" ); 
	if ( ! $default_subscription) return 'dig'; 
	else return $default_subscription; 
} 
add_filter( 'ass_default_subscription_level', 'mla_set_default_email_subscription_level', 99 );
add_filter( 'ass_get_default_subscription', 'mla_set_default_email_subscription_level', 99 );


/** 
 * Remove default save behavior for bp-ges, because we're about to rewrite it!
 */ 
remove_action( 'groups_group_after_save', 'ass_save_default_subscription' );

/**
 * Override buddypress-group-email-subscription behavior for saving default subscription,
 * since it doesn't work if we have customized our default default subscription as in the
 * above function. 
 */   
function mla_save_default_subscription( $group ) { 
	if ( isset( $_POST['ass-default-subscription'] ) && $postval = $_POST['ass-default-subscription'] ) {
		if ( $postval ) {
			groups_update_groupmeta( $group->id, 'ass_default_subscription', $postval );
			
			// during group creation, also save the sub level for the group creator
			if ( 'group-settings' == bp_get_groups_current_create_step() ) {
				ass_group_subscription( $postval, $group->creator_id, $group->id );
			}
		}
	} 
} 
add_action( 'groups_group_after_save', 'mla_save_default_subscription' );
