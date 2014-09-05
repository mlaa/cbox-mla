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
		jQuery("#cacap-content").css({ 'padding-bottom' : vwptDelta-23 });
	}
});


// Show/hide the help dropdown
jQuery(document).ready(function($) {
	var helpMenuIsVisible = false;
	function stopProp(e) {
		if (e.stopPropagation) {
			e.stopPropagation();
		}
		if (e.cancelBubble) {
			e.cancelBubble();
		}
	}
	function hideDropdown() {
		if ( helpMenuIsVisible ) {
			$('#helpdropdown').fadeOut('600');
			$('#helpdropdown').removeClass('right');
			helpMenuIsVisible = false;
		}
	}
	$('#menu-item-2166').click(function(e) {
		e.preventDefault();
		stopProp(e);
		var rightoffset = $(document).width() - $(this).offset().left;
		var isSmallWindow = ( rightoffset < 200 );
		rightoffset = isSmallWindow ? ( rightoffset - 55 ) : ( rightoffset - 168 );
		$('#helpdropdown').toggleClass('right', isSmallWindow);
		$('#helpdropdown').css({
			position: 'absolute',
			top: '86px',
			right: rightoffset + 'px',
		});
		if (helpMenuIsVisible) { 
			hideDropdown(); 
		} else { 
			$('#helpdropdown').fadeIn('600');
			helpMenuIsVisible = true;
		} 
	});
	$(document).click(function() {
		// clicking outside the dropdown closes the dropdown
		hideDropdown();
	});
	$(window).resize(function(){
		// resizing the window hides the dropdown, because wild things can occur on window resize
		hideDropdown();
	});
	$('#helpdropdown').click(function(e) {
		stopProp(e); // don't hide dropdown when clicking in the dropdown
	});
});
