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

ob_implicit_flush( true ); 

function clean_up_twitter_username( $twitter_raw ) { 
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
		return $twitter_username; 
} 

function twitter_username_for_member( $user_id ) { 
	$fields = bp_xprofile_get_fields_by_visibility_levels( $user_id, array( 'public' ) ); 
	// check to see if field 4, twitter username, is public
	if ( in_array( 4, $fields ) ) { 
		// now get it!
		$twitter_raw = xprofile_get_field_data( 4, $user_id );
		if ( '' !== $twitter_raw ) {  
			// clean that up
			$twitter_username = clean_up_twitter_username( $twitter_raw ); 
			echo $twitter_username; 
			echo ', '; 
			return true; 
		} 
	} else { 
			return false; 
	} 
} 


$page = 1; 
$thisquery = 'type=active&action=active&per_page=500&page='; 

$counter = 0; 


while ( bp_has_members( $thisquery . $page ) ) {

	while ( bp_members() ) : bp_the_member(); 
		
		$user_id = bp_get_member_user_id(); 
		$return = twitter_username_for_member( $user_id ); 
		if ( $return ) $counter = $counter + 1; 

	endwhile;

	$page = $page + 1; 

}


?>
</div> 
