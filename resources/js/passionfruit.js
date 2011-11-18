/*
 * Passionfruit Frontend v1.0.1
 * Sean Wittmeyer (sean at zilifone dot net)
 * http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery
 *
 * This script includes the nessary functions that make up the look and feel of the 
 * gallery. It starts Isotope (layout and sorting), BBQ (hash/back button), and the
 * fancybox feature (for big images and slideshows with CloudZoom).
 *
 * Feel free to customize your gallery with the variables below, just make a backup 
 * if something doesnt work as you expected.
 *
 * Include these scripts (copy them) above your or </body> tags.
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Last update: 14-11-2011
 *
 */
 
	var $container = $('#container');		// Set the div id for the gallery container, #container by default
	$container.isotope({
		itemSelector : '.box',				// Set the div class for the image thumbnail and album divs, .box by default
		masonry : {
			columnWidth : 210				// Set the width in pixels of each column, with the gutter/spacing, 210 by default (200px + 10px gutter)
		}
	});

	$(document).ready(function() {			
		$container.isotope({ filter: '.homebox' });
		if ("pushState" in history)			// This sets removes the existing GET and hash variables, you can remove this if you want to have bookmarkable albums.
			history.pushState("", document.title, window.location.pathname);
		else
			window.location.hash = "";
		});

	$('.option-set a').click(function(){	// This enables BBQ to make the backbutton work.
		// get href attr, remove leading #
		var href = $(this).attr('href').replace( /^#/, '' ),
		// convert href into object
		// i.e. 'filter=.inner-transition' -> { filter: '.inner-transition' }
		option = $.deparam( href, true );
		// set hash, triggers hashchange on window
		$.bbq.pushState( option );
		return false;
	});

	$(window).bind( 'hashchange', function( event ){
		// get options object from hash
		var hashOptions = $.deparam.fragment();
		// apply options from hash
		$container.isotope( hashOptions );
	})
	// trigger hashchange to capture any hash data on init
	.trigger('hashchange');

	$('a').click(function(){			
		$('#backbutton').show();
	});

	function spinner(ele) {
		$(ele).addClass('loading');
		setTimeout(function() {
			$(ele).removeClass('loading');
		}, 1000 );
	}

	$(document).ready(function() {
		$(".lb").fancybox({				// Light box with zoom (class = .lb)
		'width'				: '100%',
		'height'			: '90%',
		'padding'			: '0',
		'transitionIn'		: 'fade',
		'transitionOut'		: 'none',
		'overlayColor'		: '#FFF',
		'overlayOpacity'	: 0.7,
		'titleShow' 		: false,
		'showCloseButton'	: false,	// This when true causes errors.
		'hideOnContentClick' : true,
		'showNavArrows' 	: false,	// This when true interferes with the zoom function.
		'onClosed'			: function() {},
		'onComplete' : function(arg, cur) {
		// Here does the magic starts
			$('#fancybox-img').wrap(
			$('<a>')
			.attr('href', $(arg[cur]).attr('href'))
			.addClass('cloud-zoom')
			.attr('rel', "position: 'inside'")
			);
			// That's it
		$('.cloud-zoom').CloudZoom();
		}
		});
	});
	$(document).ready(function() {
		$(".lbnz").fancybox({			// Light box with no zoom (class = .lbnz)
		'width'				: '100%',
		'height'			: '90%',
		'padding'			: '0',
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'none',
		'overlayColor'		: '#FFF',
		'overlayOpacity'	: 0.7,
		'titleShow' 		: false,
		'showCloseButton'	: false,
		'hideOnContentClick' : true,
		'showNavArrows' 	: false,
		'onClosed'			: function() {}
		});
	});
