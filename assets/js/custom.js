/**
 * Custom Stuff Copyright © 2011 Bowe Frankema
 */
jQuery(document).ready(function($) {

	// add titles to certain stuff for fancy tooltips
	$('#favorite-toggle a').attr('title', 'Add this topic to your favorites');

	// hide these by default
	$('.toggle_container').hide();

	// handle slide toggles
	$('h6.trigger').click(function(e){
		$(this).toggleClass("active").next().slideToggle("normal");
		e.preventDefault();
	});

});

// a jQuery hack to make a sticky footer, so that some pages 
// (like our Advanced Search page) don't show whitespace after the footer

jQuery(document).ready(function() {
	var bodyHeight = jQuery("body").height();
	var vwptHeight = jQuery(window).height();
	var footerHeight = jQuery("#footer").height();
	if (vwptHeight > bodyHeight) {
		var vwptDelta = vwptHeight - bodyHeight - 97; 
		// I don't know why this calculation is off by 97, but it is.j
		jQuery(".main-wrap").css({ 'padding-bottom' : vwptDelta });
	}
});
