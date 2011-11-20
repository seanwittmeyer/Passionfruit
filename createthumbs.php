<?php

/*	Passionfruit Thumb Creator (GD) 1.0.3
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
 *	live updates via flush() does not work.
 *	
 *	Licensed under the MIT license:
 *	http://www.opensource.org/licenses/mit-license.php
 *
 *	Last update: 18-11-2011 (version 1.0.3)
 *
 */


//////////////// SETTINGS - you can customize the script below ///////////////////

/*  SCRIPT ON/OFF SWITCH  *
	You can turn this script on by setting this to true */
	$active = true;								// enable thumb creator, set to true to make thumbs
 
 
/*	INCREASE MEMORY ALLOWANCE
	This will allow this script more memory so we can muscle through larger files. The script includes functions to try to manage used memory 
	larger images will still need more memory. The default is usually ~8MB, this script will remove the limit. 
	Uncomment this line (remove the 2 slashes) if you are running into memory errors with this script. */
	ini_set('memory_limit','256M');
	ini_set('max_execution_time','600');
	
	
/*  BENCHMARKING  *
	Passionfruit can be a processor intensive script if you have 100+ images in your gallery. To see how long the page takes to load, you can 
	uncomment these lines of PHP. It sets the current time in a variable which is used to calculate the time it takes to fun the gallery. The 
	second part of the script is at the bottom of the page. To fully optimize Passionfruit, see the readme.txt file. */
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 


/*  SET UP  */

	$path_thumbs = "thumbs/";						// path to the thumbs directory (thumbs/ is default, make sure you include the trailing slash)
	$path_images = "images/";						// path to the images directory (images/ is default, make sure you include the trailing slash)
	$path = "";										// relative path to the thumbs from this script, leave blank if the thumbs and images directories are in the same directory as this script.
	$desired_width = 200;							// width of thumbnails to create, 200 is default with passionfruit.

//////////////// DO NOT EDIT ANYTHING BELOW THIS LINE OR THE SCRIPT MAY NO LONGER WORK AS YOU EXPECT ///////////////////

	$GLOBALS["thumbscount"] = 0;

/*  FUNCTIONS  */

function make_thumb($src,$dest,$desired_width) {
	// this is the function that makes a thumb if one doesnt exist
	$handle = fopen($src, "r");
	$contents = fread($handle, filesize($src));
	fclose($handle);
	$source_image = imagecreatefromstring($contents);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_height = floor($height*($desired_width/$width));
	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);
	imagecopyresampled($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
	imagejpeg($virtual_image,$dest,90);
	imagedestroy($source_image);
	imagedestroy($virtual_image);
	$thumb_count = $GLOBALS["thumbscount"];
	$thumb_count++;
	$GLOBALS["thumbscount"] = $thumb_count;
} // end function make_thumb();

function findimages($dir,$dir_thumbs,$path,$desired_width) {
	// This function is the main function of the script, it does all of the work.

	
	// open our thumbs dir, and proceed to read its contents
	if (is_dir($dir.$path)) {
		if ($dh = opendir($dir.$path)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir.$path.$file) == 'dir') {
					if ($file == '..' || $file == '.') {
						continue;
					} else {
						echo "\nOpening '$file' directory... \n\n";
						flush(); ob_flush();
						$newpath = $path.$file.'/';
						findimages($dir,$dir_thumbs,$newpath,$desired_width);
						echo "\nClosing '$file' directory... \n\n";
						flush(); ob_flush();
					}
				} else {																
					if (getimagesize($dir.$path.$file) !== false) {
						echo "Found image called '$file' \n";
						flush(); ob_flush();
						if (is_file($dir_thumbs.$path.$file) == false) {					
							echo "$file has no thumb, let's create one... \n";
							flush(); ob_flush();
							if (!is_dir($dir_thumbs.$path)) {
								echo "Folder does not exist, creating it... ";
								flush(); ob_flush();
								$mkdirpath = $dir_thumbs.$path;
								mkdir($mkdirpath,0755,true);
								if (mkdir($mkdirpath,0755,true) == false) {
									echo "This script requires PHP5 to run, which you may not have. See the readme.txt for details.\n\nTrying to use older function... ";
									if (!is_dir($dir_thumbs)) { 				// If server isn't running PHP5, the recursive function will not work, so we need to check if
										mkdir($dir_thumbs,0755);				// the thumbs directory exists, if not, make it. then all will be warnings instead of failure.
									}
									mkdir($mkdirpath,0755);
								}
								if (filetype($dir_thumbs.$path) !== 'dir') { 
									exit("Can't make folder $path, this is the end. \n\n\n<a name=\"bottom\" style=\"color:red;\">Process Failed</a>"); 
								} else {
									echo "success. \n\n";
									flush(); ob_flush();
								}
							}
							echo "... ";
							make_thumb($dir.$path.$file,$dir_thumbs.$path.$file,$desired_width);
							if (getimagesize($dir_thumbs.$path.$file) == false) {
								exit("Create image failed. This is the end. \n\n\n<a name=\"bottom\" style=\"color:red;\">Process Failed</a>");
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
			closedir($dh);
		}
	}
} // end function findimages();

/*  RUN THE SCRIPT  */

ini_set('zlib.output_compression', 0);
ini_set('implicit_flush', 1);
if (isset($_GET['go'])) { $go = true; }
if ($active) {
	if ($go) {
		echo "<html><body style=\"background:white;padding:0;margin:0;\"><pre style=\"font-size:11px;\">Running createthumbs.php \n\n";
		echo "Starting log...\nHere we go, opening the first directory...\n\n";
	   	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	   	ob_implicit_flush(1);
		findimages($path_images,$path_thumbs,$path,$desired_width);
	   	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	   	ob_implicit_flush(1);
		echo "\n\nCleaning up...\n";
		$end_thumb_count = $GLOBALS["thumbscount"];
		echo "<b style=\"font-size:14px;color:green;\">Success! $end_thumb_count thumbs were made, all images now have thumbs.</b> \nPassionfruit gallery is now updated and should work. \n\nWoo Hoo! \n\n";
		/*  BENCHMARKING  *
			So, how long did it take for passionfruit to run? */
			$mtime = microtime(); 
			$mtime = explode(" ",$mtime); 
			$mtime = $mtime[1] + $mtime[0]; 
			$endtime = $mtime; 
			$totaltime = ($endtime - $starttime); 
			echo "<a name=\"bottom\">It took $totaltime seconds to get the dilithium chamber at maximum, captain. Scroll up to see the log.</pre></body></html>";
	} else {
		echo "<html><body><pre style=\"font-size:11px;\">Hello! \nPassionfruit's Create Thumbs version 1.0.3 \n\nOpening the images folder at $path_images and writing thumbs to $path_thumbs \n";
		$imagetypes = imagetypes();
		ob_start();
		$memoryusage = ini_get('memory_limit');
		$mtime = microtime();
		echo "The following image types are supported: IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM \n";
		echo "Memory available for this process: $memoryusage \nSearching for folders and images... \n\nplease wait, this may take a minute or two...\n\n\n";
		echo '<iframe src="./createthumbs.php?go&t='.$mtime.'#bottom" width="100%" height="65%" style="background: transparent url(resources/loading.gif) top left no-repeat; border: 1px solid #FFF;"><p>Your browser does not support iframes. <a href="./createthumbs.php?go&t='.$mtime.'">Click here to start the script</a>.</p></iframe></body></html>';
		flush();
		for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
		ob_implicit_flush(1);
	}
} else { 
	echo "Script disabled."; 
}

// That's all folks!
