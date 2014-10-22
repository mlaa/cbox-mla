<?php 
// Adds BBPress "Forums" select option to Advanced Search. - JR
function mla_bp_search_form_type_select_add_forums($options) { 
	_log("hello. is it me you're looking for?"); 
	unset($options['posts']); 
	_log($options); 
	$options['bbpforums']  = __( 'Forums',  'buddypress' ); 
	return $options; 
} 
add_filter('bp_search_form_type_select_options', 'mla_bp_search_form_type_select_add_forums'); 

// Fix forum search handling - JR
function mla_bp_core_action_search_site( $slug = '') { 

	if ( !bp_is_current_component( bp_get_search_slug() ) )
		return;

	if ( empty( $_POST['search-terms'] ) ) {
		bp_core_redirect( bp_get_root_domain() );
		return;
	}

	$search_terms = stripslashes( $_POST['search-terms'] );
	$search_which = !empty( $_POST['search-which'] ) ? $_POST['search-which'] : '';
	$query_string = '/?s=';

	if ( empty( $slug ) ) {
		switch ( $search_which ) {
			case 'posts':
				$slug = '';
				$var  = '/?s=';

				// If posts aren't displayed on the front page, find the post page's slug.
				if ( 'page' == get_option( 'show_on_front' ) ) {
					$page = get_post( get_option( 'page_for_posts' ) );

					if ( !is_wp_error( $page ) && !empty( $page->post_name ) ) {
						$slug = $page->post_name;
						$var  = '?s=';
					}
				}
				break;

			case 'blogs':
				$slug = bp_is_active( 'blogs' )  ? bp_get_blogs_root_slug()  : '';
				break;

			case 'forums':
				$slug = bp_is_active( 'forums' ) ? bp_get_forums_root_slug() : '';
				$query_string = '/?fs=';
				break;

			case 'bbpforums': 
				$slug = 'forums';
				$query_string = '/search/';
				break;

			case 'groups':
				$slug = bp_is_active( 'groups' ) ? bp_get_groups_root_slug() : '';
				break;

			case 'members':
			default:
				$slug = bp_get_members_root_slug();
				break;
		}

		if ( empty( $slug ) && 'posts' != $search_which ) {
			bp_core_redirect( bp_get_root_domain() );
			return;
		}
	}
	bp_core_redirect( apply_filters( 'bp_core_search_site', home_url( $slug . $query_string . urlencode( $search_terms ) ), $search_terms ) );
} 
//add_filter('bp_core_search_site', 'mla_bp_search_forums', 10, 2); 
remove_action('bp_init', 'bp_core_action_search_site', 7); 
add_action('bp_init', 'mla_bp_core_action_search_site', 7); 
