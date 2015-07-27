<?php do_action( 'bp_before_directory_activity' ); ?>

<div id="buddypress">

	<?php do_action( 'bp_before_directory_activity_content' ); ?>

	<?php do_action( 'template_notices' ); ?>

	<div class="item-list-tabs dashboard-type-tabs" role="navigation">
		<ul>
			<?php do_action( 'bp_before_activity_type_tab_all' ); ?>

			<li class="selected"><a id="newsfeed" href="<?php echo site_url() . '/dashboard/'; ?>" title="<?php esc_attr_e( 'Your personalized news feed.', 'buddypress' ); ?>"><?php _e( 'Newsfeed', 'buddypress' ); ?></a></li>

			<?php $dashboard_slug = site_url() . '/dashboard/'; ?>

			<li><a id="new_groupblog_post" href="<?php echo $dashboard_slug . '?type=new_groupblog_post'; ?>" title="<?php esc_attr_e( 'Posts from network sites', 'tuileries' ); ?>"><?php echo 'Posts'; ?></a></li>

			<li><a id="bbp_topic_create" href="<?php echo $dashboard_slug . '?type=bbp_topic_create'; ?>" title="<?php esc_attr_e( 'New discussion topics', 'tuileries' ); ?>"><?php echo 'Discussions'; ?></a></li>

			<li><a id="new_member" href="<?php echo $dashboard_slug . '?type=new_member'; ?>" title="<?php esc_attr_e( 'New members', 'tuileries' ); ?>"><?php echo 'Members'; ?></a></li>

			<li><a id="new_deposit" href="<?php echo $dashboard_slug . '?type=new_deposit'; ?>" title="<?php esc_attr_e( 'New deposits', 'tuileries' ); ?>" value="new_deposit"><?php echo 'Deposits'; ?></a></li>

			<?php do_action( 'bp_activity_type_tabs' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->

<?php if ( is_user_logged_in() ) : ?>

	<?php do_action( 'bp_before_directory_activity_list' ); ?>

	<div class="activity" role="main">

		<?php bp_get_template_part( 'activity/newsfeed' ); ?>

	</div><!-- .activity -->

	<?php do_action( 'bp_after_directory_activity_list' ); ?>

	<?php do_action( 'bp_directory_activity_content' ); ?>

	<?php do_action( 'bp_after_directory_activity_content' ); ?>

	<?php do_action( 'bp_after_directory_activity' ); ?>

</div> 

<aside class="sidebar" role="complementary">
	<?php dynamic_sidebar('sidebar-primary'); ?>
	<?php dynamic_sidebar('mla-dashboard-tabbed-sidebar'); ?>
</aside><!-- /.sidebar -->

<?php else: ?>

<?php dynamic_sidebar('mla-dashboard-main'); // show the logged-out dashboard area ?> 

</div>

<aside class="sidebar" role="complementary">
	<?php dynamic_sidebar('mla-dashboard-logged-out'); ?>
</aside><!-- /.sidebar -->

<?php endif; ?> 
