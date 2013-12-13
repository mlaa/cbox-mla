<?php

/**
 * BuddyPress - Activity Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_activity_loop' ); ?>

<?php 

// Fill this string with the list of activity types 
// you want to see when the filter is set to "everything." 
// An easy way to get this list is to check out the html source
// and get all the values of the <option>s.  
$my_querystring = "action=activity_update,new_forum_topic,new_forum_post,new_groupblog_post,added_group_document,edited_group_document,bp_doc_created,bp_doc_edited,bp_doc_comment,bbp_topic_create,bbp_reply_create";

// If the ajax string is empty, that usually means that
// it's the first page of the "everything" filter. 
$querystring = bp_ajax_querystring( 'activity' ); 
if (empty($querystring)) { 
	$querystring = $my_querystring;
} 

// Handle subsequent pages of the "Everything" filter
if (substr($querystring, 0, 4)=="page" && strlen($querystring)<8) { 
	$querystring = $my_querystring . "&" . $querystring; 
} 
?> 

<?php if ( bp_has_activities( $querystring ) ) : ?>

	<?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing */ ?>
	<noscript>
		<div class="pagination">
			<div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
			<div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
		</div>
	</noscript>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="activity-list item-list">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php locate_template( array( 'activity/entry.php' ), true, false ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="#more"><?php _e( 'Load More', 'buddypress' ); ?></a>
		</li>

	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_activity_loop' ); ?>

<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

</form>
