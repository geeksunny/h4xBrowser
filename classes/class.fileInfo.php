<?php

class fileInfo
{
	private $path;
	private $url;

	private $filename; // Filename of the file we are looking at.
	private $mimetype; // MIME type of the file we are looking at.

	private $supertype; // File super-type. "image/png" -> "image". Used to determine the base report type
	private $subtype; // File sub-type. "image/png" -> "png".

	private $fileinfo;	// Array of given file's report information
	private $filepreview;

	// Other Settings
	private $dateFormat = "D d M Y h:i:s A T";
	//private $dateFormat = "m/d/Y H:i:s";

	private $getid3;

	public function __construct($path, $url)
	{
		// getID3 library inclusion
		require_once("lib/getid3/getid3.php");
		$this->getid3 = new getID3();

		// TODO: Add options to enable/disable item preview? ie picture thumbnails, built-in media player, text document excerpts.
		$this->path = $path;
		$this->url = $url;
	}

	public function setFile($file, $mimetype)
	{
		$this->filename = $file;
		$this->mimetype = $mimetype;

		// Determine the file types from the MIME type
		$mime = explode("/",$mimetype);
		$this->supertype = $mime[0];
		$this->subtype = $mime[1];

		// Generate the report information.
		$this->generate();
	}

	private function generate()
	{
		$info = $this->getid3->analyze($this->path . $this->filename);
		//var_dump($info)."<br /><br />"; //die;

		// Generic file data
		$this->fileinfo["Filename"] = $info["filename"];
		$this->fileinfo["Filesize"] = $info['filesize']. " (bytes)";
		$this->fileinfo["Filepath"] = $info["filepath"];
		if (!empty($info["fileformat"]))	// If the fileformat is not available, omit it from the list. Investigate an alternative method, more reliable than grabbing a substring...
			$this->fileinfo["File Format"] = $info["fileformat"];
		$this->fileinfo["Encoding"] = $info["encoding"];
		$this->fileinfo["MIME Type"] = (!empty($info["mime_type"])) ? $info["mime_type"] : $this->mimetype;

		// TODO: This generates the info that $this->display() uses.
		switch ($this->supertype)
		{
			case "image":
				$this->fileinfo["Data Format"] = $info["video"]["dataformat"];
				$this->fileinfo["Lossless"] = ($info["video"]["lossless"]) ? "Yes" : "No";
				$this->fileinfo["Bits Per Sample"] = $info["video"]["bits_per_sample"];
				if (!empty($info["video"]["pixel_aspect_ratio"]))
					$this->fileinfo["Pixel Aspect Ratio"] = $info["video"]["pixel_aspect_ratio"];
				$this->fileinfo["Resolution (x)"] = $info["video"]["resolution_x"];
				$this->fileinfo["Resolution (y)"] = $info["video"]["resolution_y"];
				$this->fileinfo["Compression Ratio"] = $info["video"]["compression_ratio"];

				$this->filepreview = '<img src="'.$this->url.$this->filename.'" />';
			break;
			case "audio":
				$this->fileinfo["Data Format"] = $info["audio"]["dataformat"];
				$this->fileinfo["Channels"] = $info["audio"]["channels"];
				$this->fileinfo["Sample Rate"] = $info["audio"]["sample_rate"];
				$this->fileinfo["Bitrate"] = $info["audio"]["bitrate"];
				$this->fileinfo["Channel Mode"] = $info["audio"]["channelmode"];
				$this->fileinfo["Bitrate Mode"] = $info["audio"]["bitrate_mode"];
				$this->fileinfo["Codec"] = $info["audio"]["codec"];
				$this->fileinfo["Encoder"] = $info["audio"]["encoder"];
				$this->fileinfo["Encoder Options"] = $info["audio"]["encoder_options"];
				$this->fileinfo["Compression Ratio"] = $info["audio"]["compression_ratio"];
				$this->fileinfo["Play Time"] = $info["playtime_string"];
				$this->fileinfo["Play Time (in seconds)"] = $info["playtime_seconds"];
				// TODO: Parse tags! ($info["audio"]["tags"]_["id3v1"]/["id3v2"]/etc... if v2 doesn't exist, check v1; if v1 doesn't exist, check others or display none.
			break;
			case "text":
				$this->fileinfo["Lines"] = count(file($this->path . $this->filename));
			break;
			case "application":
				// determine and differentiate if "archive" or "executable"
			break;
			//default:
				//
			//break;
		}
		// TODO: Append general info here! Date last modified, filesize, etc.
		// TODO: Generate "download links", "share links", etc.
	}

	public function display()
	{
		// TODO: This is what displays the information table on screen.
		//echo $this->filename . " - " . $this->mimetype . "<br /><br />";
		//$rows = count($this->fileinfo);

		// File Preview!
		echo '<div id="preview">'.$this->filepreview.'</div>';

		// TODO: Make this table "responsive"... utilize the examples in the "Dropbox/Code/PhpStorm/ResponsiveTables" directory!
		echo '<div id="info"><table width="100%" height="100%">';

		// File Info!...
		echo '<tr><td colspan="2" align="center"><h2><strong>File Information</strong></h2></td></tr>';

		// TODO: aside from file preview/thumbnail, most of this report will be generated with the $this->fileinfo array variable.
		foreach ($this->fileinfo as $field_label => $field_value)
		{
			echo '<tr><td><strong>'.$field_label.'</strong></td><td>'.$field_value.'</td></tr>';
		}

		echo '</table></div>';

		// Clear the float!
		echo '<div style="clear: both;"></div>';
	}
}
?>