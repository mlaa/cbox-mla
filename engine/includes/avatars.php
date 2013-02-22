<?php

/* Custom blog avatars, 'cause we roll like that!!! */

function custom_avatar ($id, $name) {
   echo bp_core_fetch_avatar (
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

// From the Executive Director
function custom_avatar_14 () { custom_avatar (14, 'Rosemary Feal'); }
add_filter('bp_get_blog_avatar_36', 'custom_avatar_14');

// From the President
function custom_avatar_205 () { echo custom_avatar (205, 'Marianne Hirsch'); }
add_filter('bp_get_blog_avatar_35', 'custom_avatar_205');

?>
