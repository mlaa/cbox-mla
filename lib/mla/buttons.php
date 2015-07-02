<?php
// Fancy-pants buttons.

function mla_get_group_join_button ( $button ) {
	_log( 'Button!', $button );
	_log( 'Button id is!', $button['id'] );
	if ( 'join_group' == $button['id'] ) {
		_log( 'Now we\'re looking at a join group!' );
		$button['link_text'] = '<span class="dashicons dashicons-plus"></span>';
		_log( 'Now link text is!', $button['link_text'] );
	}
	_log( 'Button is now!', $button );
	return $button;
}
add_filter( 'bp_get_group_join_button', 'mla_get_group_join_button' );
