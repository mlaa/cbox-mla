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
