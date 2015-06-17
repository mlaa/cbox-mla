<?php do_action( 'bp_before_directory_members_page' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_members' ); ?>

	<?php do_action( 'bp_before_directory_members_content' ); ?>

	<?php do_action( 'bp_before_directory_members_tabs' ); ?>

	<form action="" method="post" id="members-directory-form" class="dir-form">

			<!-- Because there are only two tabs here at the moment, we can wrap
			   - this whole thing in an `if` statement, since single tabs are of
			   - little use. -->
			<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
				<nav class="secondary">
					<div class="item-list-tabs" role="navigation">
						<ul>
							<li class="selected" id="members-all"><a href="<?php bp_members_directory_permalink(); ?>"><?php _e( 'All', 'buddypress' ); ?></a></li>

							<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/'; ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

							<?php do_action( 'bp_members_directory_member_types' ); ?>

						</ul>
					</div><!-- .item-list-tabs -->
				</nav>
			<?php endif; ?>

		<div id="members-dir-list" class="members dir-list">
			<?php bp_get_template_part( 'members/members-loop' ); ?>
		</div><!-- #members-dir-list -->

		<?php do_action( 'bp_directory_members_content' ); ?>

		<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

		<?php do_action( 'bp_after_directory_members_content' ); ?>

	</form><!-- #members-directory-form -->

	<?php do_action( 'bp_after_directory_members' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_members_page' ); ?>
