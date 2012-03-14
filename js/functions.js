/**
 * Javascript functions
 **/
// TODO: Investigate on how easy it would be to make navigation show up in the history. In other words, hitting "Back" on the browser would go up one directory.
function navigate(folder)
{
	var sound = document.getElementById("click_sound");
	sound.play();

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("dirListing").innerHTML=xmlhttp.responseText;
			eval(document.getElementById("newTimer").innerHTML);
		}
	}
	xmlhttp.open("GET","ajax.browseDirectory.php?f="+folder,true);
	xmlhttp.send();
}

function openFile(filename, mime)
{
	var sound = document.getElementById("click_sound");
	sound.play();
	//'fileinfo.php?f=' + filename + '&m=' + mime

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("dirListing").style.display = "none";
			document.getElementById("fileInfo").innerHTML = xmlhttp.responseText;
			document.getElementById("fileInfo").style.display = "block";
			//eval(document.getElementById("newTimer").innerHTML);
		}
	}
	xmlhttp.open("GET",'ajax.fileInfo.php?f=' + filename + '&m=' + mime,true);
	xmlhttp.send();
}

function closeFile()
{
	var sound = document.getElementById("click_sound");
	sound.play();

	document.getElementById("fileInfo").innerHTML = "";
	document.getElementById("fileInfo").style.display = "none";
	document.getElementById("dirListing").style.display = "block";
}

function updateRenderTimer(time)
{
	var timer = document.getElementById('renderTimer');
	if (timer != null)
		timer.innerHTML = time;
}

/* -- jQuery code -- */
$(function() {
    // Allow proper floating of the header player, also accomodating for the player container header
	var stickyHeaderTop = $('#fbHeader').offset().top;

	$(window).scroll(function(){
		if( $(window).scrollTop() > stickyHeaderTop ) {
			$('#fbHeader').addClass('stickTop');
		} else {
			$('#fbHeader').removeClass('stickTop');
		}
	});

});