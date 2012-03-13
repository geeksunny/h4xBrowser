<?php
////////// Config /////////
require_once("../config/settings.php");
///////////////////////////

// Get output directory
$output_dir = substr($icon_output_file,0,strrpos($icon_output_file,"/")+1);

// Generate new file contents
// TODO: Replace the following block of code with a system that reads a patterened file to generate the icon.db array.
$iconArray['config']['directory'] = "img/icons/";
$iconArray['folder']['..'] = "folder-up.png";
$iconArray['folder']['default'] = "folder.png";
$iconArray['application']['default'] = "application.png";
$iconArray['image']['default'] = "image.png";
$iconArray['audio']['default'] = "music.png";
$iconArray['text']['default'] = "page_white_text.png";
$iconArray['default'] = "page_white.png";
$output = serialize($iconArray);

// Test if the file/directory is writeable
if (!file_exists($icon_output_file))
{
	if (!is_writable($output_dir))
	{
		chmod($output_dir,0777);
		if (!is_writable($output_dir))
		{
			echo "Cannot create file. Ensure that you have write permissions on the given directory. ($output_dir)";
			die;
		}
	}
}
else
{
	if (!is_writable($icon_output_file))
	{
		chmod($icon_output_file,0777);
		if (!is_writable($icon_output_file))
		{
			echo "Cannot write to file. Ensure that you have write permissions on the filename provided. ($icon_output_file)";
			die;
		}
	}
}
// Write to file
if (!$fh = fopen($icon_output_file,'w'))
{
	echo "An error has occured. Cannot open file for writing. ($icon_output_file)";
	die;
}
if (fwrite($fh,$output) === FALSE)
{
	echo "Could not write to file. ($icon_output_file)";
	die;
}
echo "File ($icon_output_file) was successfully written!";
fclose($fh);
?>