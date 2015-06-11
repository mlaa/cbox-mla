<?php
/*
 * This file contains custom functions relating to "portfolios,"
 * or profiles. They're mostly customizations for the plugin
 * CAC-Advanced-Profiles.
 */

function mla_remove_name_from_edit_profile($cols) {
	// Assuming "1" is going to be "name."
	// We have to rebuild the array, too.
	$cols['left'] = array_values( array_diff( $cols['left'], array( 1 ) ) );
	return $cols;
}
add_filter('cacap_header_edit_columns', 'mla_remove_name_from_edit_profile');

// remove default profile link handling so we can override it below
remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data' );

// Custom xprofile interest linkifier that accepts semicolons as delimiters.
function mla_xprofile_filter_link_profile_data( $field_value, $field_type = 'textbox' ) {

	if ( 'datebox' === $field_type ) {
		return $field_value;
	}

	if ( ! strpos( $field_value, ',' ) && !strpos( $field_value, '; ' )  && ( count( explode( ' ', $field_value ) ) > 5 ) ) {
		return $field_value;
	}

	if ( strpos( $field_value, '; ' ) ) {
		$list_type = 'semicolon';
		$values = explode( '; ', $field_value ); // semicolon-separated lists
	} else {
		$list_type = 'comma';
		$values = explode( ',', $field_value ); // comma-separated lists
	}

	if ( ! empty( $values ) ) {
		foreach ( (array) $values as $value ) {
			$value = trim( $value );

			// remove <br>s at the ends of interest lists,
			// so that the final search term works
			$value = preg_replace( '/\<br \/\>$/', '', $value );

			// If the value is a URL, skip it and just make it clickable.
			if ( preg_match( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', $value ) ) {
				$new_values[] = make_clickable( $value );

			// Is not clickable
			} else {

				// More than 5 spaces
				if ( count( explode( ' ', $value ) ) > 5 ) {
					$new_values[] = $value;

				// Less than 5 spaces
				} else {
					if ( preg_match( '/\.$/', $value ) ) { // if it ends in a period
						$value = preg_replace( '/\.$/', '', $value ); // remove the period at the end
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>.'; // but add it back *after* the link.
					} else if ( preg_match( '/\.\<br \/\>/', $value ) ) {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>.<br />';
					} else if ( preg_match( '/\<br \/\>/', $value ) ) {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a><br />';

					} else {
						$search_url   = add_query_arg( array( 's' => urlencode( $value ) ), bp_get_members_directory_permalink() );
						$new_values[] = '<a href="' . esc_url( $search_url ) . '" rel="nofollow">' . $value . '</a>';
					}
				}
			}
		}

		if ( 'semicolon' == $list_type ) {
			$values = implode( '; ', $new_values );
		} else {
			$values = implode( ', ', $new_values );
		}
	}

	return $values;
}
add_filter( 'bp_get_the_profile_field_value', 'mla_xprofile_filter_link_profile_data', 9, 2 );
