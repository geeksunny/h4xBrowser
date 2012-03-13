<?php
/////
// Universal configuration file
/////

/* -- File browsing -- */
// Settings for the directory listing.
$getLastModified = true;
$getFileSizes = true;
$getMimeType = true;
$showIcons = true;

// files and directories in the top level of the
$omit_files = array("html5-filebrowser","backup");
$title = "beta.h4xful.net";					// Title for the page
$url = "http://where.your.files.are/";	// URL where the files actually are web-accessable
$path = "../";										// Location of the files on the server's filesystem

/* -- Icon Cache generator -- */
// Where the mime.types file is stored
$icon_input_file = "../config/icon.types";
// Where the new mime db file will be stored
$icon_output_file = "../config/icon.db";

/* -- MIME Cache generator -- */
// Where the mime.types file is stored
$mime_input_file = "../config/mime.types";
// Where the new mime db file will be stored
$mime_output_file = "../config/mime.db";
?>