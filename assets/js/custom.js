/**
 * Custom Stuff Copyright © 2011 Bowe Frankema
 */
jQuery(document).ready(function($) {

	// add a new grid class for register page
	$('.register #content').addClass('column sixteen');

	// add titles to certain stuff for fancy tooltips
	$('#favorite-toggle a').attr('title', 'Add this topic to your favorites');

	// hide these by default
	$('.toggle_container').hide();

	// handle slide toggles
	$('h6.trigger').click(function(e){
		$(this).toggleClass("active").next().slideToggle("normal");
		e.preventDefault();
	});

	// initial hover effects
	cbox_theme_overlay();
});


// buddy press avatars, post thumbnails support and menus hover effect
function cbox_theme_overlay()
{
	jQuery('.wp-post-image,img.avatar, ul.item-list li img.avatar, .pie-easy-exts-features-header-logo, #primary-nav li a span, a.button-callout, #sidebar a img').hover( function() {
		jQuery(this).stop().animate({opacity : 0.7}, 200);
	}, function() {
		jQuery(this).stop().animate({opacity : 1}, 200);
	});

	jQuery('.plus').hover( function() {
		jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 0.8}, 200);
	}, function() {
		jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 1}, 200);
	});
}