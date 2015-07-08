
<?php if ( is_user_logged_in() ) : ?>

<main id="mla-newsfeed-area">

<?php do_action( 'bp_before_directory_activity' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_activity_content' ); ?>

	<?php do_action( 'template_notices' ); ?>

	<div class="item-list-tabs activity-type-tabs" role="navigation">
		<ul>
			<?php do_action( 'bp_before_activity_type_tab_all' ); ?>

			<li class="selected" id="activity-all"><a href="<?php bp_activity_directory_permalink(); ?>" title="<?php esc_attr_e( 'Your news feed.', 'buddypress' ); ?>"><?php _e( 'Newsfeed', 'buddypress' ); ?></a></li>


				<?php do_action( 'bp_before_activity_type_tab_friends' ); ?>

				<?php if ( bp_is_active( 'friends' ) ) : ?>

					<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of my friends only.', 'buddypress' ); ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

				<?php endif; ?>

				<?php do_action( 'bp_before_activity_type_tab_groups' ); ?>

				<?php if ( bp_is_active( 'groups' ) ) : ?>

					<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

						<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of groups I am a member of.', 'buddypress' ); ?>"><?php printf( __( 'My Groups <span>%s</span>', 'buddypress' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

				<?php endif; ?>

				<?php do_action( 'bp_before_activity_type_tab_favorites' ); ?>

				<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>

					<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>" title="<?php esc_attr_e( "The activity I've marked as a favorite.", 'buddypress' ); ?>"><?php printf( __( 'My Favorites <span>%s</span>', 'buddypress' ), bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

				<?php endif; ?>

				<?php if ( bp_activity_do_mentions() ) : ?>

					<?php do_action( 'bp_before_activity_type_tab_mentions' ); ?>

					<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="<?php esc_attr_e( 'Activity that I have been mentioned in.', 'buddypress' ); ?>"><?php _e( 'Mentions', 'buddypress' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'buddypress' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>

				<?php endif; ?>


			<?php do_action( 'bp_activity_type_tabs' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->

	<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<ul>
			<li class="feed"><a href="<?php bp_sitewide_activity_feed_link(); ?>" title="<?php esc_attr_e( 'RSS Feed', 'buddypress' ); ?>"><?php _e( 'RSS', 'buddypress' ); ?></a></li>

			<?php do_action( 'bp_activity_syndication_options' ); ?>

			<li id="activity-filter-select" class="last">
				<label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label>
				<select id="activity-filter-by">
					<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'buddypress' ); ?></option>

					<?php bp_activity_show_filters(); ?>

					<?php do_action( 'bp_activity_filter_options' ); ?>

				</select>
			</li>
		</ul>
	</div><!-- .item-list-tabs -->

	<?php do_action( 'bp_before_directory_activity_list' ); ?>

	<div class="activity" role="main">

		<?php bp_get_template_part( 'activity/activity-loop' ); ?>

	</div><!-- .activity -->

	<?php do_action( 'bp_after_directory_activity_list' ); ?>

	<?php do_action( 'bp_directory_activity_content' ); ?>

	<?php do_action( 'bp_after_directory_activity_content' ); ?>

	<?php do_action( 'bp_after_directory_activity' ); ?>

</div>

</main>

<aside id="mla-sidebar">
	<div class="mla-profile-box">
		<p>Hey I'm a profile box!</p>
	</div>

	<div class="item-list-tabs">
	<ul>
		<li class="current"><a href="">Item One</a></li>
		<li><a href="">Item Two</a></li>
		<li><a href="">Item Three</a></li>
	</ul>
	</div>

	<h3>Sidebar Here</h3>
	<p>Herp derpsum perp dee derp, mer herderder. Sherp berp derpler, herpem serp tee perper merpus terp dee. Sherpus berps herpsum herpler. Berps herderder herpsum herpderpsmer herp? Derperker der herpler derp derpsum berps perp sherpus. Merpus mer perper derpler perp tee. Berps derpus, derpler ler mer nerpy herpem derp der derps.</p>
	<p>Herp derpsum perp dee derp, mer herderder. Sherp berp derpler, herpem serp tee perper merpus terp dee. Sherpus berps herpsum herpler. Berps herderder herpsum herpderpsmer herp? Derperker der herpler derp derpsum berps perp sherpus. Merpus mer perper derpler perp tee. Berps derpus, derpler ler mer nerpy herpem derp der derps.</p>
	<p>Herp derpsum perp dee derp, mer herderder. Sherp berp derpler, herpem serp tee perper merpus terp dee. Sherpus berps herpsum herpler. Berps herderder herpsum herpderpsmer herp? Derperker der herpler derp derpsum berps perp sherpus. Merpus mer perper derpler perp tee. Berps derpus, derpler ler mer nerpy herpem derp der derps.</p>
	<p>Herp derpsum perp dee derp, mer herderder. Sherp berp derpler, herpem serp tee perper merpus terp dee. Sherpus berps herpsum herpler. Berps herderder herpsum herpderpsmer herp? Derperker der herpler derp derpsum berps perp sherpus. Merpus mer perper derpler perp tee. Berps derpus, derpler ler mer nerpy herpem derp der derps.</p>
</aside>

<?php else: ?>

<?php auth_redirect(); ?>

<?php endif; ?>
