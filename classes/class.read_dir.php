<?php

class dirReader
{
	private $url; // Used for generating links to files.
	// The following two variables are used to determine if we are in the root of the default path.
	private $path; // The "default path"
	private $folder; // The "folder" within the "default path"

	private $dir; // the directory to scan.
	private $omit_list; // an array containing a list of files to omit from the directory listing
	private $contents; // Directory contents
	// default settings for the directory listing output
	private $showIcons;
	private $getLastModified;
	private $getFileSizes;
	private $getMimeType;
	private $columns = 1;
	private $icons;
	private $iconDB = "config/icon.db";
	private $mimeTypes;
	private $mimeDB = "config/mime.db";
	// Other Settings
	private $dateFormat = "D d M Y h:i:s A T";
	//private $dateFormat = "m/d/Y H:i:s";

	public function __construct($getLastModified=false, $getFileSizes=false, $getMimeType=false, $showIcons=false)
	{
		// TODO: Make these "options" mandatory! The options will only determine if the given information is displayed. MIME type, in particular, is a dependency for too many features to leave optional.
		$this->getLastModified = $getLastModified;
		$this->getFileSizes = $getFileSizes;
		$this->getMimeType = $getMimeType;
		$this->showIcons = false;
		if ($getMimeType)
		{
			$this->mimeTypes = $this->get_mime_array();
			//var_dump($this->mimeTypes);die;//debug
			if ($showIcons)
			{
				$this->showIcons = true;
				$this->icons = $this->get_icon_array();
				//var_dump($this->icons);die;//debug
			}
			else
				$this->showIcons = false;
		}
		// columns hack... TODO: do this better later.
		if ($getLastModified)
			$this->columns += 1;
		if ($getFileSizes)
			$this->columns += 1;
		if ($getMimeType)
			$this->columns += 1;
	}

	public function openDirectory($url, $path, $folder, $omit_list=array())
	{
		$this->url = $url;
		$this->path = $path;
		$this->folder = $folder;
		$this->dir = $path . $folder;
		$this->omit_list = $omit_list;

		$this->contents = $this->readDirectory();
	}

	public function readDirectory()
	{
		// Setting $directory for ease of use...
		$directory = $this->dir;
		// Reading Directory
		$filelist = scandir($directory);

		// Declaring the output array
		/* -----
		 * $contents[]
		 * -- DIRECTORIES --
		 * - ['folders']
		 * -- [PATHNAME]
		 * --- TRUE
		 * -- FILES --
		 * - ['files']
		 * -- [FILENAME]
		 * --- TRUE (if no file information is required)
		 * --- ['modified']
		 * --- ['size']
		 * --- ['mime']
		   ----- */
		$contents = array();
		foreach ($filelist as $filename)
		{
			// Skips file if file is included the omission list
			if (!in_array($filename,$this->omit_list))
			{
				// TODO: Add in "sorting options" that can be set in the constructor. If files & folders should be mixed together, omit the ['folders']/['files'] array keys!
				// gets rid of the ./ listing, as it is useless in our listings.
				if ($filename == ".")
					continue;
				// Removes "../" if we are in the root path.
				if (empty($this->folder) && $filename == "..")
					continue;
				// If entry is a directory
				if (is_dir($directory.$filename))
				{
					// if we ONLY want a file listing
					if (!$this->getLastModified && !$this->getFileSizes && !$this->getMimeType)
						$contents['folders'][$filename] = true;
					// if not, check each setting individually
					else
					{
						if ($this->getFileSizes)
							$contents['folders'][$filename]['size'] = $this->get_foldersize($directory,$filename);
						if ($this->getLastModified)
							$contents['folders'][$filename]['modified'] = date($this->dateFormat,filemtime($directory.$filename));
						if ($this->getMimeType)
							$contents['folders'][$filename]['mime'] = "folder";
						if ($this->showIcons)
							$contents['folders'][$filename]['icon'] = $this->get_icon("folder",$filename);
					}
				}
				// If entry is a file
				else
				{
					// if we ONLY want a file listing
					if (!$this->getLastModified && !$this->getFileSizes && !$this->getMimeType)
						$contents['files'][$filename] = true;
					// if not, check each setting individually
					else
					{
						if ($this->getFileSizes)
							$contents['files'][$filename]['size'] = $this->get_filesize($directory,$filename);
						if ($this->getLastModified)
							$contents['files'][$filename]['modified'] = date($this->dateFormat,filemtime($directory.$filename));
						if ($this->getMimeType)
							$contents['files'][$filename]['mime'] = $this->mimeTypes[substr($filename,strrpos($filename,'.')+1)];
						if ($this->showIcons)
							$contents['files'][$filename]['icon'] = $this->get_icon($contents['files'][$filename]['mime']);
					}
				}
			}
			// TODO: When sorting options is implemented, sort the file list here. Determine if sorting should be case-sensitive (LINUX-"Awxyz,abc") or not (Windows-"abc,Awxyz")
		}

	// Debug var_dump!
	//var_dump($contents); die;
	return $contents;
	}

	public function printDirectory($zebra = true)
	{
		// Initialize Zebra Striping Process if set to true.
		if ($zebra)
			$stripe = false;
		// initialize variables, for cleanliness' sake.
		$folders = ""; $files = "";

		$header = '<div class="header" id="fbHeader">
				<div class="title pad clearfix">
					Browsing directory...
					<span style="float:right;">'.$this->url.$this->folder/*$this->dir*/.'</span>
				</div>
				<div class="pad clearfix">';
		// Generate subheaders for output table
		$header .= 'Name';
		if ($this->getLastModified)
			$header .= '<div class="date fl_right">Date Modified</div>';
		if ($this->getMimeType)
			$header .= '<div class="mime fl_right">Type</div>';
		if ($this->getFileSizes)
			$header .= '<div class="size fl_right">Size</div>';
		$header .= '</div></div>
		<table id="file_listing">
			<tbody>';

		// Folders... this will be streamlined later when "seperation options" are implemented
		foreach ($this->contents['folders'] as $name => $folder)
		{
			// Zebra Striping
			// TODO: consolidate zebra striping to one process after the files / folders are integrated into a single data array and a single data printing loop
			if (isset($stripe))
			{
				if ($stripe)
				{
					$class = 'class="zebra"';
				}
				else
				{
					$class = "";
				}
				$stripe = !$stripe;
			}
			if ($name == "..")
			{
				// Strip the trailing slash to determine if we will be going to the root path or not.
				$path = rtrim($this->folder,'/');
				// If we find another slash, step up one directory
				if ($slash = strrpos($path, '/'))
					//echo substr($path,0,$slash);
					$link = substr($path,0,$slash).'/';//$link = '<a href="index.php?f='.substr($path,0,$slash).'/">'.$name.'</a>';
				else
					$link = "";//$link = '<a href="index.php">'.$name.'</a>';
			}
			else
				$link = $this->folder.$name.'/';//$link = '<a href="index.php?f='.$this->folder.$name.'/">'.$name.'</a>';
			$folders .= '<tr '.$class.' onclick="navigate(\''.$link.'\');"><td>';
			if ($this->showIcons)
				$folders .= '<img src="'.$folder['icon'].'" /> ';
			$folders .= $name.'</td>';
			if ($this->getFileSizes)
				$folders .= '<td class="size">'.$folder['size'].'</td>';
			if ($this->getMimeType)
				$folders .= '<td class="mime">'.$folder['mime'].'</td>';
			if ($this->getLastModified)
				$folders .= '<td class="date">'.$folder['modified'].'</td></tr>';
			$folders .= '</tr>'."\n";
		}
		// Files... this will be streamlined later when "seperation options" are implemented
		foreach ($this->contents['files'] as $name => $file)
		{
			// Zebra Striping
			if (isset($stripe))
			{
				if ($stripe)
				{
					$class = 'class="zebra"';
				}
				else
				{
					$class = "";
				}
				$stripe = !$stripe;
			}

			$files .= '<tr '.$class.' onClick="openFile(\''.$this->folder.$name.'\', \''.$file['mime'].'\');"><td>';
			if ($this->showIcons)
				$files .= '<img src="'.$file['icon'].'" /> ';
			$files .= $name.'</td>';
			if ($this->getFileSizes)
				$files .= '<td class="size">'.$file['size'].'</td>';
			if ($this->getMimeType)
				$files .= '<td class="mime">'.$file['mime'].'</td>';
			if ($this->getLastModified)
				$files .= '<td class="date">'.$file['modified'].'</td></tr>';
			$files .= '</tr>'."\n";
		}

		$footer = '</tbody></table>';

		$output = $header . $folders . $files . $footer;

		return $output;
	}

	// get_icon - returns the icon filename for the given file
	private function get_icon($mimeType, $filename=false)
	{
		$prefix = $this->icons['config']['directory'];
		// if there is no slash in the mimeType (ie "folder"), manually set $mime[0] for use.
		if (!$mime = explode("/",$mimeType))
			$mime[0] = $mimeType;

		if (array_key_exists($mime[0],$this->icons))
		{
			// Folders
			if ($mime[0] == "folder")
			{
				// Check for ".." folder
				if ($filename == "..")
					$iconFile = $this->icons["folder"][".."];
				else
					$iconFile = $this->icons["folder"]["default"];
			}
			// Other Files
			else
			{
				if (array_key_exists($mime[1],$this->icons[$mime[0]]))
					$iconFile = $this->icons[$mime[0]][$mime[1]];
				else
					$iconFile = $this->icons[$mime[0]]["default"];
			}
		}
		else
			$iconFile = $this->icons["default"];

		return $prefix . $iconFile;
	}
	// get_filesize - returns a formatted filesize
	private function get_filesize($directory,$file)
	{
		$size = filesize($directory.$file);
		if ($size/1073741824  >= 1)	// gigabyte
			$size = round($size/1073741824 , 2) ." GB";
		elseif ($size/1048576 >= 1)		// megabyte
			$size = round($size/1048576, 2) ." MB";
		elseif ($size/1024 >= 1)		// kilobyte
			$size = round($size/1024, 2) ." KB";
		else
			$size .= " bytes";

		return $size;
	}
	// get_foldersize - get number of items in the directory. counts files AND folders.
	private function get_foldersize($directory,$name)
	{
		$contents = scandir($directory.$name);
		$items = count($contents) - 2; // count - 2 to exclude the "." and ".."!
		unset($contents);

		return $items . " items";
	}
	/* ----- Functions to change default settings ----- */
	public function set_dateFormat($format)
	{
		// Change the default "date format" from "m/d/Y H:i;s" (09/11/2011 21:21:38)
		// TODO: set error checking here to ensure that it is a date() compatible string
		$this->dateFormat = $format;
	}
	/* ----- Functions to import data ----- */
	private function get_icon_array($iconPath = './config')
	{
		if (!file_exists($this->iconDB))
		{
			// TODO: Add code here to automatically call the "tools/genIconFile.php" file to generate a icon.db file to read from. If the file cannot be created successfully, tell the class constructor to disable the icon display option.
			// TODO: Have the "genIconFile.php" call create the file declared above ($this->iconDB) instead of whats explicitly set in the "genIconFile.php" file.
		}
		// TODO: Add further error checking and resolution below. This is a quick fix to get it working.
		$fh = fopen($this->iconDB, "r");
		$contents = fread($fh, filesize($this->iconDB));
		fclose($fh);
		$iconArray = unserialize($contents);
		return ($iconArray);
	}
	private function get_mime_array($mimePath = './config')
	{
		if (!file_exists($this->mimeDB))
		{
			// TODO: Add code here to automatically call the "tools/genMimeFile.php" file to generate a mime.db file to read from. If the file cannot be created successfully, tell the class constructor to disable the MIME reporting.
			// TODO: Have the "genMimeFile.php" call create the file declared above ($this->mimeDB) instead of whats explicitly set in the "genMimeFile.php" file.
		}
		// TODO: Add further error checking and resolution below. This is a quick fix to get it working.
		$fh = fopen($this->mimeDB, "r");
		$contents = fread($fh, filesize($this->mimeDB));
		fclose($fh);
		$mimeArray = unserialize($contents);
		return ($mimeArray);
	}
}
?>