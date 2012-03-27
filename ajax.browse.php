<?php
// Page render timer
require_once("classes/class.utime.php");
$timer = new utime();

// Absolute URLs.
require_once("classes/class.server_path.php");	// For generating absolute URLs.
$srvpath = new server_path();
define("URL", $srvpath->get_server_path(1,false,true,true));

// Importing the Global Settings variables
// - $omit_files, $path: declared in config/settings.php
require_once("config/settings.php");

$target = $path.$_REQUEST['f'];
if (is_dir($target))
{
	// Target is a directory.
	require_once("classes/class.read_dir.php");

	// GRABBING variables
	$folder = (!strstr($_REQUEST['f'],'../')) ? $_REQUEST['f'] : "";

	// Initialize the directory scanner class
	$listing = new dirReader($getLastModified, $getFileSizes, $getMimeType, $showIcons);

	// Setting the img_src variable for absolutely linked images.
	$listing->setImgSrc(URL);

	// Open the directory and scan it (scanning is currently ran by default from within this function call.
	$listing->openDirectory($url, $path, $folder, $omit_files);

	// Retrieve formatted table of directory contents
	$data = $listing->getListing();

	// Get the path to update the header with
	$path = $listing->getPath();

	// Retrieve updated render timer!
	$renderTimer = $timer->getTime();

	exit(json_encode(array("data"=>$data,"path"=>$path,"type"=>"directory","renderTimer"=>$renderTimer)));
}
elseif (is_file($target))
{
	// Target is a file.
	require_once("classes/class.fileInfo.php");

	// GRABBING variables
	$file = $_REQUEST['f'];
	$mime = $_REQUEST['m'];

	// Get the parent directory.
	$parent = str_replace($path,"", dirname($target)."/");

	// Initialize the information panel. Uses variables from the global settings file.
	$info = new fileInfo($path, $url);

	// Set the file to be reported on.
	$info->setFile($file, $mime);

	// Retrieve formatted table of directory contents
	$data = $info->getOutput();

	// Get the file path.
	$path = $url . $file;

	// Retrieve updated render timer!
	$renderTimer = $timer->getTime();

	// Echo out the data to be returned, encoded as JSON.
	exit(json_encode(array("data"=>$data,"path"=>$path,"type"=>"file","parent"=>$parent,"renderTimer"=>$renderTimer)));
}
else
	echo "Debug:\n".var_dump($_REQUEST);
?>