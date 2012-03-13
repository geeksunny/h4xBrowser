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

/* OLD openFile()! Uses fancybox!
function openFile(filename, mime)
{
	var sound = document.getElementById("click_sound");
	sound.play();

	$.fancybox({
            'width': '90%',
            'height': '90%',
			'padding': 0,
			'centerOnScroll': true,
            'autoScale': true,
			'onComplete': function () { $("body").css("overflow", "hidden"); $("html").css("overflow", "hidden"); },
			'onClosed': function () { $("body").css("overflow", "auto"); $("html").css("overflow", "auto"); },
			'scrolling': 'auto',
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
			'href': 'fileinfo.php?f=' + filename + '&m=' + mime
        });
	//window.open(filename);
}
*/
function updateRenderTimer(time)
{
	var timer = document.getElementById('renderTimer');
	if (timer != null)
		timer.innerHTML = time;
}

function openOverlay()
{
	fbOverlay({
            'width': '90%',
            'height': '90%',
			'padding': 5,
            //'transitionIn': 'fade',
            //'transitionOut': 'fade',
            'type': 'ajax',
            'url': 'test/ajaxContent.html'
        });
}

function moveHeader()
{
	var headerElem = document.getElementById('fbHeader');
	var headerOffset = headerElem.offsetTop;

	var pov = window.pageYOffset;

	if (pov > headerOffset)
	{
		headerElem.setAttribute("class","header stickTop");
	}
	else
	{
		headerElem.setAttribute("class","header");
	}
}