<?php 
/* allow a few more tags in posts so that users can paste from Microsoft Word and not see any cruft */ 
function mla_allowed_tags() {
        return array(

                // Links
                'a' => array(
                        'href'     => array(),
                        'title'    => array(),
                        'rel'      => array(),
                        'target'   => array()
                ),

                // Quotes
                'blockquote'   => array(
                        'cite'     => array()
                ),

                // Code
                'code'         => array(),
                'pre'          => array(),

                // Formatting
                'em'           => array(),
                'strong'       => array(),
                'del'          => array(
                        'datetime' => true,
                ),
		// Tags used by Word, begrudgingly included so that users can paste from Word
		'b'            => array(), 	
		'i'            => array(),
		'h1'           => array(),
		'h2'           => array(),
		'h3'           => array(),
		'h4'           => array(),
		'h5'           => array(),
		'h6'           => array(),
		'sub'          => array(),
		'sup'        => array(),
		'p'            => array(
			'align'    => true, 
		),
		'span'         => array(
	 		'style'    => true,	
		), 

                // Lists
                'ul'           => array(),
                'ol'           => array(
                        'start'    => true,
                ),
                'li'           => array(),

                // Images
                'img'          => array(
                        'src'      => true,
                        'border'   => true,
                        'alt'      => true,
                        'height'   => true,
                        'width'    => true,
                )
        );
}

add_filter('bbp_kses_allowed_tags','mla_allowed_tags');

