

</div> <!-- #container -->

<script src="resources/js/jquery.isotope.min.js"></script>
<script src="resources/js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="resources/js/cloud-zoom.1.0.2.min.js"></script>
<script src="resources/js/jquery.fancybox-1.3.4.pack.js"></script>
<script src="resources/js/jquery.ba-bbq.min.js"></script>

<script>
	var $container = $('#container');
	$container.isotope({
		itemSelector : '.box',
		masonry : {
			columnWidth : 210
		}
	});

	$(document).ready(function() {
		$container.isotope({ filter: '.homebox' });
		if ("pushState" in history)
			history.pushState("", document.title, window.location.pathname);
		else
			window.location.hash = "";
		});

	$('.option-set a').click(function(){
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
		'showCloseButton'	: false,
		'hideOnContentClick' : true,
		'showNavArrows' 	: false,
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
</script>
    
    <footer id="site-footer">
    </footer>
    
  </section> <!-- #content -->

  <!-- analytics -->
  <script>
  </script>

</body>
</html>