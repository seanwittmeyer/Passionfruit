<!DOCTYPE html> 
<html lang="en">
<head>
	<title>Passionfruit, a simple photo gallery</title> 		
	<meta name="description" content="Passionfruit is a simple photo gallery made from raw PHP and javascript that makes it easy to maintain a really nice photo gallery online. Passionfruit requires no database or headaches." />
	<meta charset="utf-8" />
	<meta name="author" content="Sean Wittmeyer" />
	<meta name="copyright" content="Sean Wittmeyer" />
	<meta name="robots" content="index,follow" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=870" />
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<link rel="stylesheet" href="./resources/passionfruit.css" />
	<link rel="stylesheet" href="./resources/styles.css" />
	<script src="/resources/jquery-1.6.2.min.js"></script>
	<script src="/resources/js/queryLoader.js"></script>
	<script src="/resources/js/modernizr-transitions.js"></script>

	<script>
		$(document).ready(function () {
			$("#container").queryLoader2();
		});
	</script>
	<!-- other scripts at bottom of page -->
</head>
<body class="homepage ">
	<header>
		<a href="http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery"><h1>Passionfruit 1.0.1, a simple photo gallery</h1></a>
		<p>
			Passionfruit is a simple and clean photo gallery that avoids the nonsense of backend interfaces and databases created by Sean Wittmeyer. The gallery uses a single file to read the contents of images in your gallery folder and outputs the gallery.
		</p><p>
			Passionfruit uses PHP to find images and metadata in your thumbs directory. You can have any number of directories with pictures (hereafter events), and you can cascade events as far as you wish. <a href="http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery">Download passionfruit 1.0.1</a>
			<br />&nbsp;
		</p>
		<nav id="site-nav">
			<div class="option-set">
				<a class="filter" id="backbutton" style="display:none;" href="#back" onclick="window.history.back();">&larr; Back</a>
				<a class="filter" href="#filter=.homebox" class="selected">Gallery Home</a>
				<a>|</a>
				<a class="filter" href="#filter=">All Images and Folders</a>
				<a class="filter" href="#filter=.dir">All Folders</a>
				<a class="filter" href="#filter=.img">All Images</a>
				<a>| Tags:</a>
				<a class="filter" href="#filter=.space">Space</a> <!-- space is a tag set in the meta.txt files -->
				<a class="filter" href="#filter=.planes">Planes</a>
				
				<!--
				Use jQuery param() to encode links for sorting with tags 
				$.param({ filter: '.metal' })
				// >> "filter=.metal"
				$.param({ filter: '.alkali, alkaline-earth' })
				// >> "filter=.alkali%2C+alkaline-earth"
				$.param({ filter: ':not(.transition)' })
				// >> "#filter=%3Anot(.transition)"

				<a href="#filter" data-option-value=":not(.transition)">not transition</a>
				<a href="#filter" data-option-value=".metal:not(.transition)">metal but not transition</a> -->

				<div class="clear"></div>
			</div>
		</nav> <!-- #site-nav -->
	</header>
	<section id="content">
		<div id="container" class="transitions-enabled clearfix">
  
