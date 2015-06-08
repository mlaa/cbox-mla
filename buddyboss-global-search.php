<?php
/*
 * This file is just Sage's base.php with a standard post template plugged in it,
 * which serves as a placeholder for the magic that BuddyPress Global Search does
 * to inject fancy search results into this page.
 *
 * TODO: Replace everything until and including `<main>` with some call to a
 * `get_template_part()` or equivalent. DRY it up!
 */
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
	<?php get_template_part( 'templates/head' ); ?>
	<body <?php body_class(); ?>>
		<!--[if lt IE 9]>
			<div class="alert">
				<?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'cpwpst' ); ?>
			</div>
		<![endif]-->
		<?php
			do_action( 'get_header' );
			get_template_part( 'templates/header' );
		?>
		<div class="wrap" role="document">
			<div class="content">
				<main class="main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'templates/page', 'header' ); ?>
					<?php get_template_part( 'templates/content', 'page' ); ?>
				<?php endwhile; ?>

				</main><!-- /.main -->
			</div><!-- /.content -->
		</div><!-- /.wrap -->
		<?php
			get_template_part( 'templates/footer' );
			wp_footer();
		?>
	</body>
</html>
