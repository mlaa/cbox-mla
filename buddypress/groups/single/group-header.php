<?php

use MLA\Tuileries\Custom;

do_action( 'bp_before_group_header' );

?>

<div id="item-header-avatar">
	<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

		<?php bp_group_avatar(); ?>

	</a>
	<div id="item-buttons">

		<?php do_action( 'bp_group_header_actions' ); ?>

	</div><!-- #item-buttons -->
</div><!-- #item-header-avatar -->


<div id="item-header-content">
	<h1><?php echo bp_current_group_name(); ?></h1>
	<span class="highlight"><?php bp_group_type(); ?></span>,
	<span class="activity"><?php printf( __( 'last active %s.', 'buddypress' ), bp_get_group_last_active() ); ?></span>
	<span class="admins"><?php _e( 'Group admins: ' ); Custom\group_list_admins(); ?>.</span>

	<?php do_action( 'bp_after_group_menu_admins' ); ?>

	<?php do_action( 'bp_before_group_header_meta' ); ?>

	<div id="item-meta">

		<?php bp_group_description(); ?>


		<?php do_action( 'bp_group_header_meta' ); ?>

	</div>
</div><!-- #item-header-content -->

<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
?>
