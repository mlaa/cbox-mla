<?php do_action( 'bp_before_directory_groups_page' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_groups' ); ?>

	<?php do_action( 'bp_before_directory_groups_content' ); ?>

	<form action="" method="post" id="groups-directory-form" class="dir-form">

		<?php do_action( 'template_notices' ); ?>

			<nav class="secondary" role="navigation">
				<ul>
					<li class="selected" id="groups-all"><a href="<?php bp_groups_directory_permalink(); ?>"><?php __( 'All Groups', 'buddypress' ); ?></a></li>

					<?php $url_stub = bp_get_groups_directory_permalink(); ?>
					<?php _log( 'url_stub is:', $url_stub ); ?>

					<li id="forums"><a href="<?php echo $url_stub . 'forums/'; ?>"><?php _e( 'Forums', 'buddypress' );?></a></li>
					<li id="committees"><a href="<?php echo $url_stub . 'committees/'; ?>"><?php _e( 'Committees', 'buddypress' );?></a></li>
					<li id="members-groups"><a href="<?php echo $url_stub . 'members-groups/'; ?>"><?php _e( "Members' Groups", 'buddypress' );?></a></li>

					<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
						<li id="groups-personal"><a href="<?php echo $url_stub . 'my-groups/'; ?>"><?php _e( 'My Groups', 'buddypress' ); ?></a></li>

					<?php else: ?>

						<li id="groups-personal"><a href="<?php echo wp_login_url(); ?>"><?php _e( 'My Groups', 'buddypress' ); ?></a></li>

					<?php endif; ?>


					<?php do_action( 'bp_groups_directory_group_filter' ); ?>

				</ul>
			</nav><!-- .secondary -->

		<div id="groups-dir-list" class="groups dir-list">
			<?php bp_get_template_part( 'groups/groups-loop' ); ?>
		</div><!-- #groups-dir-list -->

		<?php do_action( 'bp_directory_groups_content' ); ?>

		<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		<?php do_action( 'bp_after_directory_groups_content' ); ?>

	</form><!-- #groups-directory-form -->

	<?php do_action( 'bp_after_directory_groups' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_groups_page' ); ?>
