Passionfruit 1.0.4, a simple photo gallery

By Sean Wittmeyer (sean at zilifone dot net)
http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery

Contents:
1	Introduction
2	Getting Started
3	Album Metadata and meta.txt
4	Credits
5	Known Issues & Change Log
6	License (READ IF YOU ARE USING IN A COMMERCIAL/FOR PROFIT PROJECT)
7	Troubleshooting and Warranty
8	Common Errors
9	Customizing Passionfruit (all of the features)
10	Extra Options/Features (for folks familiar with PHP/HTML)


1	INTRODUCTION

	Passionfruit is a simple and clean photo gallery that avoids the nonsense of
	backend interfaces and databases. The gallery uses a single file to read the
	contents of images in your gallery folder and outputs the gallery as html
	for viewing.
	
	Passionfruit uses PHP to find images and metadata in your thumbs directory. 
	You can have any number of directories with pictures (hereafter events), and 
	you can cascade events as far as you wish.



2	GETTING STARTED

	Getting started is as easy as 1, 2, 3.
	
	Step 1 - Upload images
		To use passionfruit, place your full images in the 'images' directory. If 
		you have thumbnails, place them in the 'thumbs' directory. Make sure the 
		full image and thumbnail have the same filename, and the path to these files 
		are the same.
	
	Step 2: Make thumbnails (if you already have thumbnails, you can skip step 2)
		Don't have thumbnails made? You can use the createthumbs.php script included 
		to make thumbs if you don't want to do it yourself. The script will make the 
		folders and put the thumbs in the right space.
		
		To use createthumbs.php, go to your website where passionfruit is located, 
		then visit createthumbs.php in your browser.
		   ex. http://example.com/createthumbs.php
		
		If it says Script Disabled, find the following line in the script:
		
		   $active = false;		// enable thumb creator
		   
		and make sure it is set to true. 
		
		IF you want to turn it off, you can replace 
		'true' with 'false'. The script is only intended to make sure each big image 
		has a thumbnail. If an image already has a thumb, the script will skip it.
	
	Step 3: Optimize your gallery
		Passionfruit comes with a number of tools that help optimize the gallery
		out of the box. The most important tool in making your site faster is to use
		the htaccess file. To do this, upload the file to your server and rename it:
		
			rename htaccess to .htaccess (make sure you have the dot and no extension)
		This will enable php5 if available and will also optimize loading and speed
		of your gallery.
	
	Some notes on naming your images and folders (albums):
	  
		You may name the albums anything you want. The folder names should have no 
		spaces. To have a pretty album name, read about the meta.txt file below. 
		Avoid naming events/directories the foillowing: box, img, dir, bg, lb
	
		Your image thumbnails should all be the same width, height does not matter.
		The default is 200px wide. You can change this below and in the passionfruit
		javascript file (change both to make it work properly). Full Images can be 
		any size, just be aware that bigger images take more server space and longer 
		to load. You can utilize the fancybox + cloudzoom feature of this gallery for
		showcasing large images such as panoramas and detailed images.



3	ALBUM METADATA with meta.txt

	To use the metadata features for albums, you should place a 'meta.txt' file
	in each thumb event directory, with the following variables in a single line
	string separated by commas:

	$event_name,$zoom,$cover,$tags

	$event_name		The event title, you can use spaces here
	$zoom			'no' prevents the follow the cursor zoom feature in lightbox
	$cover			event cover image, enter filename only
	$tags			tags are single words, separated by spaces, for filtering  
    			images via isotope (i.e. spain barcelona casa_mila)

	An example meta.txt file could look like this:
	Tour of Casa Mila,no,image1234.jpg,spain barcelona casa_mila

	See the example meta.txt and gallery structure included with this build to  
	see sample foramtting of the meta file. If things are just not working out
	of the box, keep reading.



4	CREDITS 

	Passionfruit relies on the jQuery javascript library and uses a number of 
	scripts by awesome people, these include:
		jQuery 1.6.2 by John Resig (http://jquery.org)
		Isotope 1.5.03 by David DeSandro (http://isotope.metafizzy.co)
		Fancybox 1.3.4 by Janis Skarnelis(http://fancybox.net/)
		BBQ 1.2.1 by Ben Alman (http://benalman.com/projects/jquery-bbq-plugin/)
		Cloud Zoom 1.0.2 by R. Cecco (http://www.professorcloud.com)

5	KNOWN ISSUES & CHANGE LOG
	See the included wishlist.txt file for issues and roadmap for future features.
	The Change Log is also included in that file.


6	LICENSE

	Licensed under the MIT license:
	http://www.opensource.org/licenses/mit-license.php

	Note: If you are going to use this in a commercial project, you need to buy a 
	one-time license for Isotope via (http://isotope.metafizzy.co).

	Last update: 19-12-2011 (version 1.0.4)

	Thanks for playing and have a nice day!



7	TROUBLESHOOTING & WARRANTY

	As with any computer related software, there are always the inevitable errors 
	and problems with implementation. Passionfruit began as a personal project so
	it comes as-is with NO WARRANTY. I have done my best to document the software
	but if you have a problem, try searching for a solution before giving up.
	
	Below is a small attempt at offering some help for some more common issues.
	
	First, before changing functions and code, make sure your server has the 
	necessary pre-reqs. They are a LAMP server with Apache, PHP5 and GD. It will 
	work on other servers (IIS, etc…) but I have not tested on anything other then
	Apache, and the .htaccess file included to help optimize the gallery may not
	work on other systems without custom setup.
	
	Upload the provided package and put it on your server, if it loads and looks 
	right, your server is all fine and dandy. If not, try checking your software.
	
		You can check your PHP version with <?php php_info(); ?>
		You can check your GD version with <?php var_dump(gd_info()); ?>
	


8	COMMON ERRORS

	You only see the loading bar and the percentage stays at 0%
		This means that the thumbnails are missing and you need to generate them. You can do
		this by going to http://example.com/createthumbs.php (replace example.com with your website)
		
		
	Error: Warning: getimagesize(images/event/IMG_1234.jpg) [function.getimagesize]: failed to open stream: No such file or directory in /path/to/your/website/index.php on line 146

		This means your image in the thumbs folder doesn't have a match in the images folder. 
		Check naming (ie the extension must be the same) and path. So if my image (IMG_1234.jpg) 
		is in my thumbs folder (/thumbs/event/IMG_1234.jpg), then the big version of this image
		must be in (/images/event/IMG_1234.jpg)
	
		NOTE: IMG_1234.JPG is not equal to IMG_1234.jpg (note the extension).

	If your event/folder shows up but no images show up, this may mean you have a space in your 

		folder name. Make sure all folders have no spaces in them, so instead of (Family Trip 2011), 
		you can use (family_trip_2011). Keep in mind that you can set event covers and the title 
		(with spaces) in a meta.txt file.
		
	When making thumbnails with createthumbs.php:
	
	Warning:  mkdir() expects at most 2 parameters, 3 given in /path/to/your/website/createthumbs.php on line 124
		This error comes up if you do not have PHP5 installed. If you have php4 or lower, you will get this error
		when the script tries to make folders. You may want to look into upgrading php5 for passionfruit.
		
		This is a warning, if you get a red "process failed" message, you need to upgrade to php5, 
		if you see the green success message at the bottom of the page, the script tried an alternate
		method and it worked, you don't need to worry, all is good :)



9	CUSTOMIZING PASSIONFRUIT

	Passionfruit includes a number of features that can be customized. First, you can 
	put passionfruit in any webpage, as long as you include the required javascript and
	css files. 

	Customizing the loading bar:
		You can change and customize the color, background color, size and other options of the
		loading bar easily by finding the QueryLoader javascript in the header.php file.
		
		You can replace the default:
		<script>
			$(document).ready(function () {
				$("#container").queryLoader2();
			});
		</script>
		
		
		With this:
		<script>
			$(document).ready(function () {
				$("body").queryLoader2({
					barColor: "#999999",
					backgroundColor: "#DDDDDD",
					percentage: true,
					barHeight: 2
				});
			});	
		</script>
		
		barColor is the color of the loading bar, use web colors or hex colors.
		backgroundColor is the background color.
		percentage is either true or false, if false, the percentage will not show.
		barHeight sets in pixels how big the bar should be. Default is 2 pixels.
	
		

10	EXTRA OPTIONS AND FEATURES

	Passionfruit makes it easy to embed in a site. The easiest way to get the gallery working
	is to start with the provided package. If you want to embed it into your website and you 
	have images organized as outlined above, you can edit the bottom of the index.php file.
	
	Passionfruit includes a simple header and footer by default, you can remove the include lines 
	in the gallery script and start the function by including the script and by calling the 
	passionfruit() function when and where you want a gallery. I would recommend renaming the 
	script to passionfruit.php if you are going to do this.

	Include the php script
		include('path/to/passionfruit.php);
	
	Call the function
		passionfruit($dir,$dir_images,$class_dir,$class_img,$path,$currentdir,$zoom,$properpath,$metatags)
	
	The variables in the function are listed below. All are required (some can be left blank with '')

	$variable		type		['default']  description
	
	$dir			string		['thumbs ']  path to the thumbs directory (thumbs/ is default, make sure you include the trailing slash)
	$dir_images		string		['images ']  path to the images directory (images/ is default, make sure you include the trailing slash)
	$class_dir		string		['dir']  css class for directory div (dir by default)
	$class_img		string		['img']  css class for image div (img by default)
	$path			string		['']  relative path to the thumbs, leave blank if the thumbs and images directories are in the same directory as this script.
	$currentdir		string		['']  the current directory, leave blank at beginning. the function uses this to search directories in directories
	$zoom			string		['yes' or 'no']  cloud zoom feature for the top level gallery, on by default. this is set by the meta.txt files for recursive albums. 
	$properpath		string		['']  used to make nice thumbnails, uses information from the meta.txt file if available
	$metatags		string		['']  css classes for sorting, uses information from the meta.txt file if available 
	
	
	
	OPTIONS FOR NAVIGATION

	You can make text links (<a>) to control and sort the gallery. Links use classes to sort 
	through images and folders so if a folder has a class, you can sort it. You set classes 
	for folders and images within by placing tags in the meta.txt files for each album/folder. 

	A sorting link should have the class filter and link to #filter-[html element you want]
	Examples:

	A back button
		<a class="filter" id="backbutton" style="display:none;" href="#back" onclick="window.history.back();">&larr; Back</a>
		
	Gallery Home link (resets the gallery)
		<a class="filter" href="#filter=.homebox" class="selected">Gallery Home</a>
		
	Show all images and folders (filter is blank)
		<a class="filter" href="#filter=">All Images and Folders</a>
		
	Show All folders (folders always have the .dir class)
		<a class="filter" href="#filter=.dir">All Folders</a>
		
	Show all images (images always have the .img class)
		<a class="filter" href="#filter=.img">All Images</a>
		
	Custom Tags (space or planes)
		<a class="filter" href="#filter=.space">Space</a> <!-- space is a tag set in the meta.txt files -->
		<a class="filter" href="#filter=.planes">Planes</a>
		
	Make sure you include the . for classes. You can select multiple classes as well, you must 
	encode the link tho. The jQuery param() can encode links for sorting with tags 
	
		$.param({ filter: '.metal' })						>>		"#filter=.metal"
		$.param({ filter: '.alkali, alkaline-earth' })		>>		"#filter=.alkali%2C+alkaline-earth"
		$.param({ filter: ':not(.transition)' })			>>		"#filter=%3Anot(.transition)"

	ENABLING/DISABLING IMAGE ZOOM
	
	Image zoom is, by default, enabled. While this feature proves to be handy for many users, some may not 
	enjoy it. Passionfruit now includes a toggle switch in the demo navigation that turns this on and off. 
	You can remove this toggle switch by simply removing the link in the navigation bar.
	See the html in the header file to learn more about this.


	DOWNLOAD PARALLELIZATION (new in 1.0.4)
	
	If your gallery has more then 100 images, you may have noticed that the site can take some time to load, 
	especially on slower network connections. One way to speed up the load time is to utilize download 
	parallelization, which allows the browser to download the images from many servers instead of one. While
	most users are only using one server (as the gallery was designed for), we can trick the browser into 
	giving us faster load speeds while only using one server. 
	
	To do this, we utilize Apache's wildcard subdomain feature. If your server has this enabled, then you can
	utilize this feature. Basically, what we do is change the path to the image slightly so that you access the
	same file from multiple URLs. 
	
	So, if your image is at this URL:
		http://example.com/images/image_01.jpg
		
	You can also access it with this URL: 
		http://images.example.com/images/image_01.jpg
		
	This only works if the subdomain is not already being used, and if you have the subdomain wildcard setup.
	The way the feature works for passionfruit is quite simple. When enabled, the gallery will add the current
	directory as the subdomain. So if the image "image_01.jpg" is in the "birthday2011" directory, then the 
	image will load	from 
		http://birthday2011.example.com/images/birthday2011/image_01.jpg 
	instead of it's real location at
		http://example.com/images/birthday2011/image_01.jpg 

	Hey, I want this!
	I bet you do if you are a power user, of if you have lots of folders/albums. To get started, you must make 
	sure you have subdomain wildcard support on your server. It's simple to test if you do. Just visit your site
	with any random subdomain. If you get the same site, you can use this feature, if not, you can go about learning
	how to get it enabled by reading about it here: http://en.wikipedia.org/wiki/Wildcard_DNS_record
	
	If all is good and ready to work, you can enable it by finding the following line in the index.php file and adding 
	your domain, with a trailing forward slash.

		Find:	$dnspth = "";
		
	Add your domain in the quotes, so if your gallery is located at "http://photos.example.com/gallery/", you should have:
		
		$dnspth = "photos.example.com/gallery";
		
	Don't worry about the "http://" part, the gallery automatically deals with that.
	
	Once you do this, save and upload the index.php file and reload your gallery. If you view the source, you will see 
	each image's background now has the subdomain. This only affects the background images (the second URL in each line 
	of code), and the source code will	look something like this: 
	
	<a href="images/birthday2011/image_01.jpg" class="lb" rel="arrival"><div class="box img birthday2011" style="background: url(http://birthday2011.example.com/thumbs/birthday2011/image_01.jpg) no-repeat; width: 200px; height: 133px;" onclick=""></div></a>
	
	This feature, if used on a server not supporting wildcard subdomains, will not load any images, you can test 
	by clicking on your album in the gallery webpage and if you don't see your images, something isn't working.
	
	This is an experimental feature, meaning it has bugs and issues. If you use it, the loading bar will no longer
	work. So it depends on what you want more, a loading bar or faster load times. I am aware of the issue and am working 
	on a fix. The problem lies within the rules of how JSON works with cross-domains. When the loading script runs, it 
	finds all of the images we are loading and gets a preview of it. It is not allowed to open files on other domains
	which means it gives up, so your loading bar may not move or load at all. You can turn off the loading bar by 
	removing the following lines of code from your header.php file:
	
		<script>
			$(document).ready(function () {
				$("#container").queryLoader2();
			});
		</script>
		
		
	Cheers and good luck. The demo files included in the passionfruit zipfile do not have download parallelization
	enabled, but you can see a working demo of it at http://photos.seanwittmeyer.com
 