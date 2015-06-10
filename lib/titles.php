<?php

namespace MLA\Tuileries\Titles;

/**
 * Page titles
 */
function title() {
	if ( is_home() ) {
		if ( get_option( 'page_for_posts', true ) ) {
			return get_the_title( get_option( 'page_for_posts', true ) );
		} else {
			return __( 'Latest Posts', 'cpwpst' );
		}
	} elseif ( is_archive() ) {
		return get_the_archive_title();
	} elseif ( is_search() ) {
		return sprintf( __( 'Search Results for %s', 'cpwpst' ), get_search_query() );
	} elseif ( is_404() ) {
		return __( 'Not Found', 'cpwpst' );
	} elseif ( bp_is_directory() || bp_is_group() ) {
		// Don't show titles here.
		// We'll add them in the templates instead.
		return '';
	} else {
		return get_the_title();
	}
}
