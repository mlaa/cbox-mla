<?php

/**
 * BuddyPress - Blogs Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_blogs_loop' ); ?>

<?php if ( bp_has_blogs( bp_ajax_querystring( 'blogs' ) ) ) : ?>

	<?php do_action( 'bp_before_directory_blogs_list' ); ?>

	<ul id="blogs-list" class="item-list" role="main">

	<?php while ( bp_blogs() ) : bp_the_blog(); ?>

		<li>

			<div class="item-avatar">
				<a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_avatar( 'type=full' ); ?></a>
			</div>

			<div class="item">
				<div class="item-title"><a href="<?php bp_blog_permalink(); ?>"><?php bp_blog_name(); ?></a></div>

				<div class="item-meta"><span class="activity"><?php bp_blog_last_active(); ?></span>. <?php bp_blog_latest_post(); ?></div>

				<?php do_action( 'bp_directory_blogs_item' ); ?>

			</div>

			<div class="action">

				<?php
					// Disabling this for the moment, since the only action is "visit site,"
					// which seems a little redundant, considering that the site titles themselves
					// are links. If we need this later, though, uncomment this and figure out
					// another way to hide the "visit site" buttons.
					// do_action( 'bp_directory_blogs_actions' );
				?>


			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_blogs_list' ); ?>

	<?php bp_blog_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="blog-dir-count-bottom">

			<?php bp_blogs_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="blog-dir-pag-bottom">

			<?php bp_blogs_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there were no sites found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_blogs_loop' ); ?>
