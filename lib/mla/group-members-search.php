<?php
/**
 * Output the Group members template.
 * Based on bp_groups_members_template_part(), but simplified.
 *
 * @since BuddyPress (?)
 *
 * @return string html output
 */
function mla_bp_groups_members_template_part() {
	?>

	<?php bp_directory_members_search_form(); ?>

	<div id="members-group-list" class="group_members dir-list">

		<?php bp_get_template_part( 'groups/single/members' ); ?>

	</div>
	<?php
}
