<?php
/**
 * Output the Private Message search form.
 * Based on bp_message_search_form(), and
 * customized here to remove some of the redundancy.
 */
function mla_bp_message_search_form() {

	// Get the default search text
	$default_search_value = bp_get_search_default_text( 'messages' );

	// Setup a few values based on what's being searched for
	$search_submitted     = ! empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value;
	$search_placeholder   = ( $search_submitted === $default_search_value ) ? ' placeholder="' .  esc_attr( $search_submitted ) . '"' : '';
	$search_value         = ( $search_submitted !== $default_search_value ) ? ' value="'       .  esc_attr( $search_submitted ) . '"' : '';

	// Start the output buffer, so form can be filtered
	ob_start(); ?>

	<form action="" method="get" id="search-message-form">
		<input type="text" name="s" id="messages_search"<?php echo $search_placeholder . $search_value; ?> />
		<input type="submit" class="button" id="messages_search_submit" name="messages_search_submit" value="<?php esc_html_e( 'Search', 'buddypress' ); ?>" />
	</form>

	<?php

	// Get the search form from the above output buffer
	$search_form_html = ob_get_clean();

	/**
	 * Filters the private message component search form.
	 *
	 * @since BuddyPress (2.2.0)
	 *
	 * @param string $search_form_html HTML markup for the message search form.
	 */
	echo apply_filters( 'bp_message_search_form', $search_form_html );
}
