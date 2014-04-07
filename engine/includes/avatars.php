<?php
/* Custom blog avatars, 'cause we roll like that!!! */
function custom_avatar($avatar, $blogid, $params) { 
	$custom_avatars = array(
		/* put blog avatars to be overridden here
		 * in the format: 
		 * 'blog_id' => array(item_id, 'Person Name'),
		 */ 
		'36' => array(14, 'Rosemary Feal'),
		'35' => array(205, 'Marianne Hirsch'),
		'153' => array(205, 'Marianne Hirsch'), 
	); 
	if (isset($custom_avatars[$blogid])) { 
		$id = $custom_avatars[$blogid][0]; 
		$name = $custom_avatars[$blogid][1]; 
		$avatar = bp_core_fetch_avatar(
			array(
				'item_id' => $id,
				'type' => 'thumb',
				'alt' => 'Profile picture of site author ' . $name,
				'width' => 40,
				'height' => 40,
				'class' => 'avatar'
			)
		); 
	} 
	return $avatar; 
} 
add_filter('bp_get_blog_avatar', 'custom_avatar', 10, 3);  
?>
