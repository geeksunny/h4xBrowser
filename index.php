<?php
require_once("config/settings.php");			// Configuration settings.
require_once("classes/class.read_dir.php");		// For getting the directory listing.
// Page render timer
require_once("classes/class.utime.php");
$timer = new utime();
// Absolute URLs.
require_once("classes/class.server_path.php");	// For generating absolute URLs.
$srvpath = new server_path();
define("URL", $srvpath->get_server_path(1,false,true,true));
?>

<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Browse All the Things! - An HTML5 File Browser</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
	<link rel="stylesheet" href="<?=URL?>css/style.css">
  <!-- end CSS-->

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
	<!-- <script src="js/libs/modernizr-2.0.6.min.js"></script> -->

	<!-- jQuery - www.jquery.com -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

	<!-- Custom Javascript functions in the file below. -->
	<script type="text/javascript">var url_base = "<?=URL?>"; // Used to ensure AJAX calls inside 'js/functions.js' will work!</script>
	<script src="<?=URL?>js/functions.js"></script>
</head>

<body>

  <div id="container">
    <header>
		Browse All the Things!
		<div id="subheader"><?php echo $title; ?></div>
    </header>
    <div id="main" role="main">
		<div id="dirListing">
			<?php
			// Initial call of directory listing
			// - $omit_files, $path: declared in config/settings.php
			// GRABBING variables
			$folder = (!strstr($_REQUEST['f'],'../')) ? $_REQUEST['f'] : "";

			// Initialize the directory scanner class
			$listing = new dirReader($getLastModified, $getFileSizes, $getMimeType, $showIcons);

			// Setting the img_src variable for absolutely linked images.
			$listing->setImgSrc(URL);

			// Open the directory and scan it (scanning is currently ran by default from within this function call.
			$listing->openDirectory($url, $path, $folder, $omit_files);

			// Retrieve the header data.
			$header = $listing->getHeader();
			// Retrieve formatted table of directory contents
			$data = $listing->getListing();
			?>
			<div class="header" id="fbHeader">
				<?=$header ?>
			</div>
			<div id="fbBody">
				<?=$data ?>
			</div>
		</div>
		<div id="fileInfo" style="display:none;">
			<div class="header" id="fiHeader">
				<div class="title pad clearfix">
					<span onclick="closeFile();" style="cursor:pointer;"><img src="<?=URL?>img/icons/arrow_left.png" /> Go Back</span>
					<span id="fiPath" style="float:right;"></span>
				</div>
			</div>
			<div id="fiBody"></div>
		</div>
    </div>
    <footer>
		<?php
		// TODO: Add in a configuration option for page render time.
		// TODO: Perhaps change "Page rendered" to "Directory read/displayed"?
		// page render time
		echo "Page rendered in <span id='renderTimer'>".$timer->getTime()."</span> seconds.<br />";
		?>
		"Kernel Panic!" written by <a href="http://www.faecbawks.com/" target="_blank">Justin Swanson</a>.
    </footer>
  </div> <!--! end of #container -->

	<audio id="click_sound" preload="auto">
		<source src="audio/click.mp3"></source>
		<source src="audio/click.ogg"></source>
	</audio>
  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
</body>
</html>
