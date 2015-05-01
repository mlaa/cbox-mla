<?php

/**
 * BuddyPress - Users Plugins
 *
 * This is a fallback file that external plugins can use if the template they
 * need is not installed in the current theme. Use the actions in this template
 * to output everything your plugin needs.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header( 'buddypress' ); ?>

<?php get_sidebar( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">

        <?php if ( bp_has_members() ) : while ( bp_members() ) : bp_the_member(); ?>

        <?php do_action( 'bp_before_user_deposits_content' ); ?>


			<div id="item-header">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_user_deposits_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body" role="main">

				<?php do_action( 'bp_before_user_deposits_body' ); ?>

<!--				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>

						<?php bp_get_options_nav(); ?>

						<?php do_action( 'humcore_deposit_plugin_options_nav' ); ?>

					</ul>
				</div>--><!-- .item-list-tabs -->

				<h3><?php do_action( 'bp_template_title' ); ?></h3>

				<?php do_action( 'bp_template_content' ); ?>

				<?php do_action( 'bp_after_user_deposits_body' ); ?>

			</div><!-- #item-body -->

		<?php do_action( 'bp_after_user_deposits_content' ); ?>
        <?php endwhile; endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>
