<?php

/*	Passionfruit Thumb Creator (GD) 1.0.2
 *	By Sean Wittmeyer (sean at zilifone dot net)
 *
 *	This script allows you to create thumbs from full size images for the 
 *	Passionfruit gallery script. This script is only designed to build thumbs
 *	once, so you can turn it off below to prevent unnecessary use and server 
 *	processing power.
 *
 *	This script uses PHP & GD to find images and check for thumbs in your thumbs 
 *	directory. If a thumb is not detected, this will make one and place it in 
 *	it's rightful place. The script may take a while to run so be patient and 
 *	avoid interrupting it so we don't end up with corrupt images.
 *
 *	NOTE: This script is intended for users who do not have an alternate method
 *	for creating thumbnails. This script uses GD, which does not make the best
 *	quality thumbnails. If you have software that will export/resize images,
 *	you may want to use those options instead. 
 *
 *	NOTE: This script will crash if you try to create thumbnails for images larger 
 *	then ~7MB. You will see an error stating you reached the memory allotment.
 *	Try reloading the page (the thumbnails that were successfully created will be
 *	sipped the second time around which may free up the necessary memory to 
 *	generate the thumbnail of the larger image).
 *
 *	Known Issues in 1.0.2:
 *	Image quality of images created, will look into other options
 *	
 *	Licensed under the MIT license:
 *	http://www.opensource.org/licenses/mit-license.php
 *
 *	Last update: 17-11-2011 (version 1.0.2)
 *
 */
 
 
/*	INCREASE MEMORY ALLOWANCE
	This will allow this script more memory so we can muscle through larger files. The script includes functions to try to manage used memory 
	larger images will still need more memory. The default is usually ~8MB, this script will remove the limit. */
	ini_set('memory_limit','256M');
	
	
/*  BENCHMARKING  *
	Passionfruit can be a processor intensive script if you have 100+ images in your gallery. To see how long the page takes to load, you can 
	uncomment these lines of PHP. It sets the current time in a variable which is used to calculate the time it takes to fun the gallery. The 
	second part of the script is at the bottom of the page. To fully optimize Passionfruit, see the readme.txt file. */
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 


/*  SCRIPT ON/OFF SWITCH  *
	You can turn off this script by setting this to false */
	$active = true;									// enable thumb creator

/*  SET UP  */

	$path_thumbs = "thumbs/";						// path to the thumbs directory (thumbs/ is default, make sure you include the trailing slash)
	$path_images = "images/";						// path to the images directory (images/ is default, make sure you include the trailing slash)
	$path = "";										// relative path to the thumbs from this script, leave blank if the thumbs and images directories are in the same directory as this script.
	$desired_width = 200;							// width of thumbnails to create, 200 is default with passionfruit.

    //apache_setenv('no-gzip', 1);
    ini_set('zlib.output_compression', 0);
    ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);


function make_thumb($src,$dest,$desired_width) {
	// this is the function that makes a thumb if one doesnt exist
	//echo "source is $src \n";
	//echo "destination is $dest \n";
	//echo "width is $desired_width \n";
	$handle = fopen($src, "r");
	$contents = fread($handle, filesize($src));
	fclose($handle);
	$source_image = imagecreatefromstring($contents);
	unset($contents);																	// read source image, get height and width
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_height = floor($height*($desired_width/$width));							// calculate height
	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);				// create virtual image
	imagecopyresampled($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height); 	// resize
	imagejpeg($virtual_image,$dest,90);													// make an image at 90% quality
	imagedestroy($source_image);
	imagedestroy($virtual_image);														// clear up memory for next image
}

function findimages($dir,$dir_thumbs,$path,$desired_width) {
	// This function is the main function of the script, it does all of the work.

	// set some variables

	// open our thumbs dir, and proceed to read its contents
	if (is_dir($dir.$path)) {
		if ($dh = opendir($dir.$path)) {
			while (($file = readdir($dh)) !== false) {									// for each item, lets do something if it's a directory
				if (filetype($dir.$path.$file) == 'dir') {								// if the file is a directory, lets play with it
					if ($file == '..' || $file == '.') { 								// lets ignore the current and parent directories
						continue;
					} else {															// fetch the meta, if any
						echo "\nOpening '$file' directory... \n\n";
						flush(); ob_flush();														// send data to the browser, to make it seem like things are moving along
						$newpath = $path.$file.'/';										// append a trailing slash for opendir()
						findimages($dir,$dir_thumbs,$newpath,$desired_width);			// run this function again for the new directory
						echo "\nClosing '$file' directory... \n\n";
						flush(); ob_flush();														// send the new directory data to the browser
					}
				} else {																
					if (getimagesize($dir.$path.$file) !== false) {						// if it's not an image, it would be false and nothing would happen
						echo "Found image called '$file' \n";
						flush(); ob_flush();
						if (is_file($dir_thumbs.$path.$file) == false) {					
							echo "$file has no thumb, let's create one... \n";
							flush(); ob_flush();
							if (!is_dir($dir_thumbs.$path)) {
								echo "Folder does not exist, creating it... ";
								flush(); ob_flush();
								mkdir($dir_thumbs.$path,0755,true);
								if (filetype($dir_thumbs.$path) !== 'dir') { 
									exit("Can't make folder $path, this is the end. \n\n\nProcess Failed"); 
								} else {
									echo "success. \n";
									flush(); ob_flush();
								}
							}
							echo "... ";
							make_thumb($dir.$path.$file,$dir_thumbs.$path.$file,$desired_width);
							if (getimagesize($dir_thumbs.$path.$file) == false) {
								exit("Create image failed. This is the end. \n\n\nProcess Failed");
							} else {
								echo "success! Thumb for $file created, next... \n";
								flush(); ob_flush();
							}
						} else {
							echo "$file already has a thumb, skipping it. Next... \n";
							flush(); ob_flush();
						}
					}
				}
	        }
			closedir($dh);																// close the directory, we don't need it anymore
		}
	}
} // end function findimages();
if ($active) {
	echo "<html><body><pre style=\"font-size:11px;\">Hello! \nRunning Passionfruit's Create Thumbs script version 1.0.2 \n\nOpening the images folder at $path_images and writing thumbs to $path_thumbs \n";
	$imagetypes = imagetypes();
	ob_start();
	$memoryusage = ini_get('memory_limit');
	echo "The following image types are supported: $imagetypes \n";
	echo "Memory available for this process: $memoryusage \n";
	echo "Here we go, opening the first directory... \n\n";
	flush();
	findimages($path_images,$path_thumbs,$path,$desired_width);
	echo "\n\n\n";
	echo "Success, all images now have thumbs. Passionfruit gallery should now work with your images. \n\nWoo Hoo! \n\n";

} else { 
	echo "Script not active."; 
}


/*  BENCHMARKING  *
	So, how long did it take for passionfruit to run? */
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime); 
	echo "This page was synthesized from pure unicorn tears in a mere $totaltime seconds </pre></body></html>";
