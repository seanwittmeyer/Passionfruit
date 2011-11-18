<?php

/*	Passionfruit 1.0.3, a simple photo gallery
 *	By Sean Wittmeyer (sean at zilifone dot net)
 *	http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery
 *
 *	Passionfruit is a simple and clean photo gallery that avoids the nonsense of
 *	backend interfaces and databases. The gallery uses a single file to read the
 *	contents of images in your gallery folder and outputs the gallery as html
 *	for viewing.
 *
 *	Passionfruit uses PHP to find images and metadata in your thumbs directory. 
 *	You can have any number of directories with pictures (hereafter events), and 
 *	you can cascade events as far as you wish.
 *
 *	To use passionfruit, place your full images in the 'images' directory and  
 *	thumbnails in the 'thumbs' directory. Make sure the full image and thumbnail
 *	have the same filename, and the path to these files are the same. 
 *
 *	Don't have thumbnails made? You can use the createthumbs.php script included 
 *	to make thumbs if you don't want to do it yourself. The script will make the 
 *	folders and put the thumbs in the right space.
 *	
 *	You may name the albums anything you want. The folder names should have NO 
 *	SPACES. To have a pretty album name, read about the meta.txt file below. 
 *	Avoid naming events/directories the foillowing: box, img, dir, bg, lb
 *	
 *
 *	QUESTIONS? See the included readme.txt file for instructions on using this
 *	gallery and for help with all of passionfruit's features.
 *	
 *
 *	This uses a number of javascripts by awesome people, these include:
 *	jQuery 1.6.2 by John Resig (http://jquery.org)
 *	Isotope 1.5.03 by David DeSandro (http://isotope.metafizzy.co)
 *	Fancybox 1.3.4 by Janis Skarnelis(http://fancybox.net/)
 *	BBQ 1.2.1 by Ben Alman (http://benalman.com/projects/jquery-bbq-plugin/)
 *	Cloud Zoom 1.0.2 by R. Cecco (http://www.professorcloud.com)
 *
 *	Known Issues in 1.0.3:
 *	Empty folders still show as blank boxes.
 *	Folders 4 levels deep don't show images
 *	Back button doesn't take you to the top level gallery listing
 *	
 *	Licensed under the MIT license:
 *	http://www.opensource.org/licenses/mit-license.php
 *
 *	Last update: 18-11-2011 (version 1.0.3)
 *
 *	Thanks for playing and have a nice day!
 */
 

/*  MOBILE DEVICE CHECK  *
	This script is optional. If you are using passionfruit with a large number of images (100+), you may 
	want to include this. All it does is checks to see if the visitor is on a device that may have a hard 
	time with lots of data, or a phone on a cell network. If a mobile user is detected, it forwards the 
	user to the warning.html page. If the user agrees, a cookie is set and the message goes away for good. */
	include('./resources/detectmobile.php');	// path to detectmobile.php (optional)


/*  SET UP  */

	$header = "./header.php";						// path to the website header (this file must include passionfruit.scripts.pack.js and jQuery)
	$footer = "./footer.php";						// path to the website footer (this file must include passionfruit.js)
	$dir = "thumbs/";								// path to the thumbs directory (thumbs/ is default, make sure you include the trailing slash)
	$dir_images = "images/";						// path to the images directory (images/ is default, make sure you include the trailing slash)
	$class_dir = "dir";								// css class for directory div (dir by default)
	$class_img = "img";								// css class for image div (img by default)
	$path = "";										// relative path to the thumbs, leave blank if the thumbs and images directories are in the same directory as this script.


//	DON'T EDIT ANYTHING BELOW or the gallery may no longer work. You have been warned.

/*  BENCHMARKING  *
	Passionfruit can be a processor intensive script if you have 100+ images in your gallery. To see how long the page takes to load, you can 
	uncomment these lines of PHP. It sets the current time in a variable which is used to calculate the time it takes to fun the gallery. The 
	second part of the script is at the bottom of the page. To fully optimize Passionfruit, see the readme.txt file. */
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 

/*  FUNCTIONS  */

function fetch_random_image($dir) {														// Randomly find a image for the cover if one isn't set
	$files = array(); $i = -1; 															// initialize
	$handle = opendir($dir);
	$exts = explode(',', 'jpg,jpeg,png,gif');
	while (false !== ($file = readdir($handle))) {
		foreach($exts as $ext) { 														// for each extension check the extension
			if (preg_match('/\.'.$ext.'$/i', $file, $test)) { 							// faster than ereg, case insensitive
				$files[] = $file; 														// it's good
				++$i;
			}
		}
	}
	closedir($handle);																	// We're not using it anymore
	mt_srand((double)microtime()*1000000); 												// seed for PHP < 4.2
	$rand = mt_rand(0, $i); 															// $i was incremented as we went along
	return $files[$rand]; 																// return the lucky winner
} // end function fetch_random_image();

function passionfruit($dir,$dir_images,$class_dir,$class_img,$path,$currentdir,$zoom,$properpath,$metatags) {
	// This function is the main function of the script, it does all of the work.

	// set some variables
	$currenttags = str_replace('/', " ", $path);										// turn the directories in the path into tags
	$box = 'box ';																		// box class, remember the space after the class
	$home = 'homebox ';																	// home box class, remember the space
	if ($currentdir == !'') {															// if elements ain't top level, we hide 'em
		$stylehide = 'display: none;'; 
		$home = '';
	}
	// open our thumbs dir, and proceed to read its contents
	if (is_dir($dir.$path)) {
		if ($dh = opendir($dir.$path)) {
			while (($file = readdir($dh)) !== false) {									// for each item, lets do something if it's a directory
				if (filetype($dir.$path.$file) == 'dir') {								// if the file is a directory, lets play with it
					if ($file == '..' || $file == '.') { 								// lets ignore the current and parent directories
					} else {															// fetch the meta, if any
						$meta = $dir.$path.$file.'/meta.txt';
						if (is_file($meta)) {
							$source = file_get_contents($meta);
							list($event_name[$file], $newzoom, $cover[$file], $metatags) = explode(",", $source);
						}
						if (!is_file($dir.$path.$file.'/'.$cover[$file])) {				// check the event cover image
							$cover[$file] = fetch_random_image($dir.$path.$file); 		// use random if cover isn't set in meta.txt
						}
						if ($event_name[$file] == '') $event_name[$file] = $file;		// use directory name if event name isn't set
						
						// make the dir box
						$rel[$file] = str_replace(' ', "", $currenttags);				// turn the directories in the path into tags
						echo '<a href="#filter=.'.$rel[$file].$file.'"><div class="'.$box.$class_dir.' '.$home.$rel[$file].' '.$metatags.'" onclick"spinner(this);"><div class="bg" style="background: url('.$dir_images.$path.$file.'/'.$cover[$file].') center center no-repeat;"><h2>'.$properpath.$event_name[$file]."</h2></div></div></a>\n";

						// read in contents of the directory
						$newpath = $currentdir.'/'.$file.'/';							// append a trailing slash for opendir()
						flush();														// send data to the browser, to make it seem like things are moving along
						$newproperpath = $properpath.$event_name[$file].'/';
						passionfruit($dir,$dir_images,$class_dir,$class_img,$newpath,$file,$newzoom[$file],$newproperpath,$metatags);	// run this function again for the new directory
						flush();														// send the new directory data to the browser
					}
				} elseif ($file == 'meta.txt') {										// the meta file isn't needed so we'll let it hang out in silence
				} else {																// not a dir or meta, maybe an image…
					if (getimagesize($dir.$path.$file) !== false) {						// if it's not an image, it would be false and nothing would happen
						list($width, $height, $type, $attr) = getimagesize($dir.$path.$file);
						list($bigwidth, $bigheight, $bigtype, $bigattr) = getimagesize($dir_images.$path.$file);
						
						// build the box
						if ($zoom == 'no') {											// if $zoom is set to no in the meta.txt, then lets abide
							$lb = 'lbnz';												// no-zoom class selector for fancybox
						} else {
							$lb = 'lb';													// zoom is on by default, this is the fancybox selector class
						}
						$rel[$file] = str_replace(' ', "", $currenttags);				// turn the directories in the path into tags
						echo "<a href=\"$dir_images$path$file\" class=\"$lb \" rel=\"$rel[$file]\"><div class=\"$box $class_img $rel[$file] $metatags\" style=\" background: url(".$dir.$path.$file.') no-repeat; width: '.$width.'px; height: '.$height.'px;" onclick=""></div></a>'."\n";
						flush();														// send the image box to the browser
					}
				}
	        }
			closedir($dh);																// close the directory, we don't need it anymore
		}
	}
} // end function passionfruit();


/*  BUILD THE GALLERY  
	Set the header and footer files above in the 'set up' section */
	include($header);																		// start with including the header file
	passionfruit($dir,$dir_images,$class_dir,$class_img,$path,'','','','');					// begin searching the base directory
	include($footer);																		// end with including the footer file

/*  BENCHMARKING  *
	So, how long did it take for passionfruit to run? */
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime); 
	echo "<!--This page was synthesized from pure unicorn tears in a mere $totaltime seconds -->"; 


/*  KARMA  *
	If you like Passionfruit, share it with your friends. A simple link will ensure happiness for all :) */
	print 'Powered by <a href="http://digital.seanwittmeyer.com/2301294/Passionfruit-a-photo-gallery" target="_blank">Passionfruit 1.0.3</a>';	

?>