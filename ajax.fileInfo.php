<div onclick="closeFile();" style="cursor:pointer;"><-- Go Back</div>
<?php
// This file is called via AJAX to display information on the given file.
require_once("classes/class.fileInfo.php");

// Import the global Settings
require_once("config/settings.php");
// GRABBING variables
$file = $_GET['f'];
$mime = $_GET['m'];

// Initialize the information panel. Uses variables from the global settings file.
$info = new fileInfo($path, $url);

// Set the file to be reported on.
$info->setFile($file, $mime);

// Display formatted table of directory contents
$info->display();
?>