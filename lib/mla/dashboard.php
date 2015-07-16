<?php

// Functions for the dashboard

// Make a new sidebar for tabbed widgets. This will hold
// "From the MLA," -> "News," "Sites," and "Resources."
function mla_dashboard_sidebars(){
	register_sidebar( array(
		'id'          => 'mla-dashboard-tabbed-sidebar',
		'name'        => 'Dashboard Tabbed Sidebar',
		'description' => 'This is the "From the MLA" sidebar, containing MLA news, sites, and resources.',
	));
}
add_action( 'widgets_init', 'mla_dashboard_sidebars', 9 );

/* A widget for displaying the logged-in user's avatar, name, affiliation,
 * and a few useful links to that user's pages.
 */
class MLA_BP_Profile_Area extends WP_Widget {
	function __construct() {
		parent::WP_Widget( false, $name = __( 'MLA Profile Area', 'tuileries' ) );
	}

	function widget($args, $instance) {
		global $bp;

		extract( $args );

		$link_title = ! empty( $instance['link_title'] );

		echo $before_widget;
		?>

		<div class="user_profile">

			<?php $current_user = bp_loggedin_user_id();
			echo bp_core_fetch_avatar( array(
				'item_id' => $current_user,
				'type'    => 'full',
			)); ?>

			<div class="profile_meta">
				<p class="profile_widget name"><?php echo bp_core_get_user_displayname( $current_user ); ?></p>
				<p class="profile_widget title"><?php echo xprofile_get_field_data( 'title', $current_user ); ?></p>
				<p class="profile_area institutional_affiliation"><?php echo xprofile_get_field_data( 2, $current_user ); ?></p>

			</div>

		</div><!-- .user_profile -->

		<div id="profile_button">
			<?php if ( is_plugin_active( 'buddypress-profile-progression' ) ) : ?>
				<p class="profile_progression"><?php bppp_progression_block( $current_user ); ?></p>
			<?php endif; ?>
			<a class="button">View / Edit My Portfolio</a>
		</div>

		<div class="links">
			<?php
				$member_slug = bp_loggedin_user_domain();
			?>
				<a href="<?php echo $member_slug . BP_BLOGS_SLUG ?>">My Sites</a>
				<a href="<?php echo $member_slug . BP_GROUPS_SLUG ?>">My Groups</a>
				<a href="<?php echo $member_slug . BP_FRIENDS_SLUG ?>">My Contacts</a>
		</div>

		<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		return $instance;
	}

	function form( $instance ) {
		?>
		<p>This widget adds a profile area to a sidebar, including the logged-in user's avatar, title, affiliation, and a few useful links. </p>
		<?php
	}
}

/**
 * Register the above widget
 */
function mla_register_profile_widget()
{
	return register_widget( "MLA_BP_Profile_Area" );
}
add_action( 'widgets_init', 'mla_register_profile_widget' );