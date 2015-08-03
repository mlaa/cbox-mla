<?php

// Functions for the dashboard

// Make a new sidebar for the main dashboard area. This will probably hold
// an RSS feed with MLA news items. 
function mla_dashboard_main(){
	register_sidebar( array(
		'id'          => 'mla-dashboard-main',
		'name'        => 'Dashboard Main Area',
		'description' => 'This is the left area of the main dashboard, visible only to logged-out users.',
	));
}
add_action( 'widgets_init', 'mla_dashboard_main', 9 );

// Make a new sidebar for tabbed widgets. This will hold
// "From the MLA," -> "News," "Sites," and "Resources."
function mla_dashboard_sidebars(){
	register_sidebar( array(
		'id'          => 'mla-dashboard-tabbed-sidebar',
		'name'        => 'Dashboard Tabbed Sidebar',
		'description' => 'This is the "From the MLA" sidebar, containing MLA news, sites, and resources.',
	));
}
add_action( 'widgets_init', 'mla_dashboard_sidebars', 10 );

// Make a new sidebar for logged-out stuff. This will hold
// "MLA Member Resources."
function mla_dashboard_logged_out(){
	register_sidebar( array(
		'id'          => 'mla-dashboard-logged-out',
		'name'        => 'Dashboard Sidebar for Logged-Out Users',
		'description' => 'This sidebar was originally meant to contain "MLA Member Resources."',
	));
}
add_action( 'widgets_init', 'mla_dashboard_logged_out', 11 );
/* A widget for displaying the logged-in user's avatar, name, affiliation,
 * and a few useful links to that user's pages.
 */
class MLA_BP_Profile_Area extends WP_Widget {
	function __construct() {
		parent::WP_Widget( false, $name = __( 'MLA Profile Area', 'tuileries' ) );
	}

	function widget($args, $instance) {

		if ( is_user_logged_in() ) { 

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
				<?php if ( function_exists( 'bppp_progression_block'  ) ) : ?>
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
	<?php } 
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

/**
 * Custom scope to allow mixing in official MLA activity items into the newsfeed.
 *
 * @param array $retval Empty array by default
 * @param array $filter Current activity arguments
 * @return array
 */
function mla_custom_activity_scope( $retval = array(), $filter = array() ) {

	// Determine the user_id
	if ( ! empty( $filter['user_id'] ) ) {
		$user_id = $filter['user_id'];
	} else {
		$user_id = bp_displayed_user_id()
			? bp_displayed_user_id()
			: bp_loggedin_user_id();
	}

	// Define official MLA blogs
	$blogs = array(
		'blogs' => array(
			14,  // FAQ
			15,  // News from the MLA
			35,  // From the President
			36,  // From the Executive Director
			37,  // PMLA
			38,  // Convention
			111, // Executive Council
			127, // The Trend
			221, // Connected Academics
			281, // MLA Resources
		),
	);

	// Should we show all items regardless of sitewide visibility?
	$show_hidden = array();
	if ( ! empty( $user_id ) && ( $user_id !== bp_loggedin_user_id() ) ) {
		$show_hidden = array(
			'column' => 'hide_sitewide',
			'value'  => 0
		);
	}

	$retval = array(
		'relation' => 'AND',
		array(
			'relation' => 'AND',
			array(
				'column' => 'component',
				'value'  => buddypress()->blogs->id
			),
			array(
				'column'  => 'item_id',
				'compare' => 'IN',
				'value'   => (array) $blogs['blogs']
			),
		),
		$show_hidden,

		// overrides
		'override' => array(
			'filter'      => array( 'user_id' => 0 ),
			'show_hidden' => true
		),
	);

	return $retval;
}
add_filter( 'bp_activity_set_mla_scope_args', 'mla_custom_activity_scope', 10, 2 );
