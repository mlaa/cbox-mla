<?php

// Custom avatars.
require_once( 'avatars.php' );

function modify_some_widgets(){

   register_sidebar(array(
      'name' => 'Homepage Center Left',
      'id' => 'homepage-center-left',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   register_sidebar(array(
      'name' => 'Homepage Center Middle',
      'id' => 'homepage-center-middle',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   register_sidebar(array(
      'name' => 'Homepage Center Right',
      'id' => 'homepage-center-right',
      'description' => "The Left Center Widget on the Homepage",
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
   ));

   // Unregsiter CBox sidebar
   unregister_sidebar( 'homepage-center-widget' );

   register_sidebar( array(
      'id' => 'help-dropdown',
      'name' => 'Help Dropdown',
      'description' => 'The dropdown menu displayed when the help menu item is clicked.',
   ));

}
add_action( 'widgets_init', 'modify_some_widgets', 9 );

/* Here's a widget for displaying recent blog posts on the homepage, 
 * adapted from a similar widget in cbox-theme/engine/includes/buddypress. 
 * The only difference between this and the CBOX version is that this one
 * will also display group blogs in addition to network blogs. There's an open 
 * PR about this in cbox-theme at https://github.com/cuny-academic-commons/cbox-theme/pull/227 
 * so this function can be removed if that PR is merged upstream. 
 */ 
class MLA_BP_Blogs_Recent_Posts_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget( false, $name = __( 'MLA Recent Networkwide Blog Posts', 'cbox-theme' ) );
	}

	function widget($args, $instance) {
		global $bp;

		extract( $args );

		$link_title = ! empty( $instance['link_title'] );

		echo $before_widget;
		echo $before_title;

		if ( $link_title ) {
			$dir_link = trailingslashit( bp_get_root_domain() ) . trailingslashit( bp_get_blogs_root_slug() );
			$title = '<a href="' . $dir_link . '">' . $instance['title'] . '</a>';
		} else {
			$title = $instance['title'];
		}
		echo $title;
		echo $after_title;

		if ( empty( $instance['max_posts'] ) || !$instance['max_posts'] )
			$instance['max_posts'] = 10;

		// Load more items that we need, because many will be filtered out by privacy
		$real_max = $instance['max_posts'] * 10;
		$counter = 0;

		if ( bp_has_activities( 'action=new_blog_post,new_groupblog_post&max=' . $real_max . '&per_page=' . $real_max ) ) : ?>

			<ul id="blog-post-list" class="activity-list item-list">

				<?php while ( bp_activities() ) : bp_the_activity(); ?>

					<?php if ( $counter >= $instance['max_posts'] ) break ?>

					<li>
						<div class="activity-content" style="margin: 0">
							<div class="activity-avatar">
								<?php bp_activity_avatar() ?>
							</div>

							<div class="activity-header">
								<?php bp_activity_action() ?>
							</div>

							<?php if ( bp_get_activity_content_body() ) : ?>

									<?php bp_activity_content_body() ?>

							<?php endif; ?>

						</div>
					</li>

					<?php $counter++ ?>

				<?php endwhile; ?>

			</ul>

		<p class="cac-more-link"><a href="<?php bp_blogs_directory_permalink(); ?>">More Blogs</a></p>

		<?php else : ?>
			<div id="message" class="info">
				<p><?php _e( 'Sorry, there were no blog posts found. Why not write one?', 'buddypress' ) ?></p>
			</div>
		<?php endif; ?>

		<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['max_posts']  = strip_tags( $new_instance['max_posts'] );
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['link_title'] = empty( $new_instance['link_title'] ) ? '0' : '1';

		return $instance;
	}

	function form( $instance ) {
		$instance   = wp_parse_args( (array) $instance, array(
			'max_posts'  => 10,
			'title'      => __( 'Recent Blog Posts', 'cbox-theme' ),
			'link_title' => true,
		) );
		$max_posts  = strip_tags( $instance['max_posts'] );
		$title      = strip_tags( $instance['title'] );
		$link_title = (bool) $instance['link_title'];

		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e('Title: ', 'cbox-theme'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 90%" /></label></p>

		<p><label for="<?php echo $this->get_field_name( 'link_title' ) ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'link_title' ) ?>" value="1" <?php checked( $link_title ) ?> /> <?php _e( 'Link widget title to Blogs directory', 'cbox-theme' ) ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'max_posts' ) ?>"><?php _e('Max posts to show:', 'buddypress'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_posts' ); ?>" name="<?php echo $this->get_field_name( 'max_posts' ); ?>" type="text" value="<?php echo esc_attr( $max_posts ); ?>" style="width: 30%" /></label></p>

	<?php
	}
}

/**
 * Register the above widget
 */
function mla_register_widgets()
{
	return register_widget( "MLA_BP_Blogs_Recent_Posts_Widget" );
}
add_action( 'widgets_init', 'mla_register_widgets' );
