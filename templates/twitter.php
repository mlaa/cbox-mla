<?php
/**
 * Template Name: Twitter Thing
 * Infinity Theme: twitter thing 
 *
 * @author Jonathan Reeve <jreeve@mla.org>
 * @link http://www.mla.org
 * @copyright none 
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage templates
 * @since 1.0
 */
?>	

<?php if ( ! is_super_admin() ) { 
	$error_msg = new WP_Error( 'broke', __( "This page is for super-admins only.", "cbox-mla" ) ); 
	echo $error_msg->get_error_message(); 
	die(); 
} 

?> 

	<div id="content" role="main" class="<?php do_action( 'content_class' ); ?>">

<h1>A Dynamically-Generated List of Public Twitter Usernames of MLA Commons Members</h1>
<h2>Sorted by Last Activity</h2>

<?php 

$query = 'type=active&action=active&per_page=999999';  
$counter = 0; 

if ( bp_has_members( $query ) ) : ?>

<?php while ( bp_members() ) : bp_the_member(); ?>
	
<?php 
$user_id = bp_get_member_user_id(); 
//echo "user id: $user_id"; 
//echo 'fields: '; 
$fields = bp_xprofile_get_fields_by_visibility_levels( $user_id, array( 'public' ) ); 
//foreach ( $fields as $field ) { echo $field; } 
if ( in_array( 4, $fields ) ) { 
	$twitter_raw = xprofile_get_field_data( 4, $user_id );
	// clean up these horrendous twitter usernames
	if ( '' !== $twitter_raw ) {  
		if ( 0 === strpos( $twitter_raw, 'http://twitter.com/' ) ) { 
			$twitter_username = substr( $twitter_raw, 19 );  
		} else if ( 0 === strpos( $twitter_raw, 'https://twitter.com/' ) ) { 
			$twitter_username = substr( $twitter_raw, 20 );  
		} else if ( 0 === strpos( $twitter_raw, 'twitter.com/' ) ) { 
			$twitter_username = substr( $twitter_raw, 12 );  
		} else if ( 0 === strpos( $twitter_raw, 'www.twitter.com/' ) ) { 
			$twitter_username = substr( $twitter_raw, 16 );  
		} else if ( 0 === strpos( $twitter_raw, '@' ) ) { 
			$twitter_username = substr( $twitter_raw, 1);  
		} else if ( strpos( $twitter_raw, '@' ) ) { 
			$twitter_username = substr( $twitter_raw, strpos( $twitter_raw, '@' ) + 1 );  
		} else if ( 0 === strpos( $twitter_raw, '#' ) ) { 
			$twitter_username = substr( $twitter_raw, 1 );  
		} else { 
			$twitter_username = $twitter_raw; 
		} 
		echo $twitter_username; 
		echo ', '; 
		$counter = $counter + 1;
	} 
} 

			?>

	<?php endwhile; ?>

<?php 
echo "<p>Total:";  
echo $counter; 
echo "</p>"; 
?> 


<?php endif; ?>
</div> 
