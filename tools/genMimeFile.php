<?php
////////// Config /////////
require_once("../config/settings.php");
///////////////////////////

// Get output directory
$output_dir = substr($mime_output_file,0,strrpos($mime_output_file,"/")+1);

// Generate new file contents
$regex = "/([\w\+\-\.\/]+)\t+([\w\s]+)/i";
$lines = file($mime_input_file, FILE_IGNORE_NEW_LINES);
foreach($lines as $line)
{
	if (substr($line, 0, 1) == '#') continue; // skip comments
	if (!preg_match($regex, $line, $matches)) continue; // skip mime types w/o any extensions
	$mime = $matches[1];
	$extensions = explode(" ", $matches[2]);
	foreach($extensions as $ext) $mimeArray[trim($ext)] = $mime;
}
$output = serialize($mimeArray);

// Test if the file/directory is writeable
if (!file_exists($mime_output_file))
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
	if (!is_writable($mime_output_file))
	{
		chmod($mime_output_file,0777);
		if (!is_writable($mime_output_file))
		{
			echo "Cannot write to file. Ensure that you have write permissions on the filename provided. ($mime_output_file)";
			die;
		}
	}
}
// Write to file
if (!$fh = fopen($mime_output_file,'w'))
{
	echo "An error has occured. Cannot open file for writing. ($mime_output_file)";
	die;
}
if (fwrite($fh,$output) === FALSE)
{
	echo "Could not write to file. ($mime_output_file)";
	die;
}
echo "File ($mime_output_file) was successfully written!";
fclose($fh);
?>