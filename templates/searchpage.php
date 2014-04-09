<?php
/**
 * Infinity Theme: search template
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */

infinity_get_header();
?>	
	<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">

			<div id="search-bar" role="search">

			<h1><?php echo __( 'Search Members, Groups, and Blogs', 'buddypress' ); ?></h1>  
				<form action="<?php echo bp_search_form_action(); ?>" method="post" id="advanced-search-form"> 
					<input type="text" id="search-terms" name="search-terms" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''; ?>" placeholder="Search for:" />

					<?php echo bp_search_form_type_select(); ?>

					<input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ); ?>" />

					<?php wp_nonce_field( 'bp_search_form' ); ?>

				</form><!-- #search-form -->

				<?php do_action( 'bp_search_login_bar' ); ?>

			</div><!-- #search-bar -->
			<h1><?php echo __( 'Search Forums', 'buddypress' ); ?></h1>  
			<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
				<div>
					<input type="hidden" name="action" value="bbp-search-request" />
					<input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr(            bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" placeholder="Search for:" />
					<input tabindex="<?php bbp_tab_index(); ?>" class="button" type="submit"                       id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
				</div>
			</form>
	</div>
<?php
infinity_get_sidebar();
infinity_get_footer();
?>
