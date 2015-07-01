<?php

/**
 * BuddyPress - Users Blogs
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php
switch ( bp_current_action() ) :

	// Home/My Blogs
	case 'my-sites' :
		do_action( 'bp_before_member_blogs_content' ); ?>

		<div class="blogs myblogs" role="main">

			<?php bp_get_template_part( 'blogs/blogs-loop' ) ?>

		</div><!-- .blogs.myblogs -->

		<?php do_action( 'bp_after_member_blogs_content' );
		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
