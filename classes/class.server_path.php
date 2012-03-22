<?php
// Single function class to grab the current server path. May have more URL-related functions added to it later.
class server_path
{
	function __construct()
	{
	}

	// Returns the current server directory.
	public function get_server_path($steps = 0, $suffix = false, $url = false, $protocol = false)
	{
		// Get the current subdirectory.
		//$current_subdir = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/")+1); //Grabs PHP_SELF and removes the filename.
		$current_subdir = str_replace( basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['PHP_SELF'] );
		// Get the current filename path of this filename.
		$target_directory = dirname(__FILE__);
		// If $steps is set, cycle through and step out however many times given.
		if ($steps)
		{
			for ($step = 0; $step < $steps; $step++)
			$target_directory = dirname($target_directory);
		}
		// Append a trailing slash to the directory.
		$target_directory .= "/";
		// Go to the root of the web server path. Removes the local filesystem prefix.
		$path = substr($target_directory,strrpos($target_directory,$current_subdir));
		// If a suffix is provided, append it to the end-result.
		if ($suffix)
			$path .= $suffix;
		// If URL is set to true
		if ($url)
		{
			$path = $_SERVER['HTTP_HOST'].$path;
			if ($protocol)
			{
				$protocol_array = explode('/',$_SERVER['SERVER_PROTOCOL']);
				$protocol_string = strtolower($protocol_array[0]).'://';
				$path = $protocol_string.$path;
			}
		}

		return $path;
	}
}
?>