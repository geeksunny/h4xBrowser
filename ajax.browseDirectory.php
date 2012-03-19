<?php
// Page render timer
require_once("classes/class.utime.php");
$timer = new utime();

// This file is called via AJAX to step around the directory
require_once("classes/class.read_dir.php");

// Importing the Global Settings variables
// - $omit_files, $path: declared in config/settings.php
require_once("config/settings.php");
// GRABBING variables
$folder = (!strstr($_POST['f'],'../')) ? $_POST['f'] : "";

// Initialize the directory scanner class
$listing = new dirReader($getLastModified, $getFileSizes, $getMimeType, $showIcons);

// Open the directory and scan it (scanning is currently ran by default from within this function call.
$listing->openDirectory($url, $path, $folder, $omit_files);

// Retrieve formatted table of directory contents
$data = $listing->getListing();

// Get the path to update the header with
$path = $listing->getPath();

// Retrieve updated render timer!
$renderTimer = $timer->getTime();

exit(json_encode(array("data"=>$data,"path"=>$path,"renderTimer"=>$renderTimer)));
?>