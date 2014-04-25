<?php
/**
 * Template Name: Publications
 */

	infinity_get_header();
?>
	<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">
		<?php
			do_action( 'open_content' );
			do_action( 'open_page' );
		?>	
		<?php
			infinity_get_template_part( 'templates/loops/loop', 'page' );
		?>	
		<?php
			do_action( 'close_page' );
			do_action( 'close_content' );
		?>
	</div>
	<aside id="sidebar" role="complementary" class="<?php do_action( 'sidebar_class' ); ?>">
	<!-- sidebar -->
		<?php
			dynamic_sidebar( 'Publications Sidebar' );
		?>
	</aside>
<?php
	infinity_get_footer();
?>
