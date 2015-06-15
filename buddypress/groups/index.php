<?php do_action( 'bp_before_directory_groups_page' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_groups' ); ?>

	<?php do_action( 'bp_before_directory_groups_content' ); ?>

	<form action="" method="post" id="groups-directory-form" class="dir-form">

		<?php do_action( 'template_notices' ); ?>

			<nav class="secondary" role="navigation">
				<ul>
					<?php $url_stub = bp_get_groups_directory_permalink(); ?>

					<li id="groups-personal"><a href="<?php echo $url_stub . 'my-groups/'; ?>"><?php printf( __( 'My Groups <span>%s</span>', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

					<li id="forums"><a href="<?php echo $url_stub . 'forums/'; ?>"><?php _e( 'Forums', 'buddypress' );?></a></li>
					<li id="committees"><a href="<?php echo $url_stub . 'committees/'; ?>"><?php _e( 'Committees', 'buddypress' );?></a></li>
					<li id="members-groups"><a href="<?php echo $url_stub . 'members-groups/'; ?>"><?php _e( "Members' Groups", 'buddypress' );?></a></li>

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
