<?php
/**
 * Tuileries includes
 *
 * The $cpwpst_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 */
$cpwpst_includes = [
	'lib/assets.php',                  // Scripts and stylesheets
	'lib/conditional-tag-check.php',   // ConditionalTagCheck class
	'lib/config.php',                  // Configuration
	'lib/custom.php',                  // Custom functions
	'lib/customizer.php',              // Customizer functions
	'lib/extras.php',                  // Extra functions
	'lib/init.php',                    // Initial theme setup and constants
	'lib/titles.php',                  // Page titles
	'lib/utils.php',                   // Utility functions
	'lib/wrapper.php',                 // Theme wrapper class
	'lib/mla/activities.php',          // Custom behaviors for activities.
	'lib/mla/blog-avatars.php',        // Custom blog avatars
	'lib/mla/bp-ges.php',              // Customizations for BuddyPress Group Email Subscriptions
	'lib/mla/bp-global-search.php',    // Customizations for BuddyPress Global Search
	'lib/mla/committees.php',          // Committee behaviors
	'lib/mla/dashboard.php',           // Our awesome dashboard "My Commons" page.
	'lib/mla/group-members-search.php',// Group members search
	'lib/mla/group-filters.php',       // Filters for MLA groups
	'lib/mla/groupblog.php',           // Customizations for the BP-Groupblog plugin
	'lib/mla/messages-search.php',     // Functions for the messages search
	'lib/mla/nonmembers.php',          // Functions for handling nonmembers
	'lib/mla/oracle-api-sync.php',     // Functions for syncing membership data with the MLA API
	'lib/mla/portfolios.php',          // Functions to customize CACAP "Portfolios"
	'lib/mla/remove-unnecessary.php',  // Remove stuff
];

foreach ( $cpwpst_includes as $file ) {
	if ( ! $filepath = locate_template( $file ) ) {
		trigger_error( sprintf( __( 'Error locating %s for inclusion', 'cpwpst' ), $file ), E_USER_ERROR );
	}

	require_once $filepath;
}
unset($file, $filepath);

// Remove redundant subscription button from group header.
remove_action( 'bp_group_header_meta', 'ass_group_subscribe_button' );
