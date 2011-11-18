

</div> <!-- #container --> <!-- you must include this for passionfruit to work -->

<script src="resources/js/jquery.isotope.min.js"></script> <!-- Required for passionfruit -->
<script src="resources/js/jquery.mousewheel-3.0.4.pack.js"></script> <!-- Required if you want to use the scroll wheel on a mouse in the image popups -->
<script src="resources/js/cloud-zoom.1.0.2.min.js"></script> <!-- Required if you want the zoom feature in the image popups -->
<script src="resources/js/jquery.fancybox-1.3.4.pack.js"></script> <!-- Required for image popups -->
<script src="resources/js/jquery.ba-bbq.min.js"></script> <!-- Required for passionfruit -->

<!-- BEGIN Passionfruit Javascript (include this to make passionfruit work) -->
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
<!-- END Passionfruit Javascript -->

    <footer id="site-footer">
    </footer>
    
  </section> <!-- #content -->

  <!-- analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-336608-24']);
  _gaq.push(['_setDomainName', 'none']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script> 
</body>
</html>