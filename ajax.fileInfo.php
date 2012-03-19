<?php
// This file is called via AJAX to display information on the given file.
require_once("classes/class.fileInfo.php");

// Import the global Settings
require_once("config/settings.php");
// GRABBING variables
$file = $_POST['f'];
$mime = $_POST['m'];

// Initialize the information panel. Uses variables from the global settings file.
$info = new fileInfo($path, $url);

// Set the file to be reported on.
$info->setFile($file, $mime);

// Retrieve formatted table of directory contents
$data = $info->getOutput();

// Get the file path.
$path = $url . $file;

// Echo out the data to be returned, encoded as JSON.
exit(json_encode(array("data"=>$data,"path"=>$path)));
?>