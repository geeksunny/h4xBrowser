/**
 * Javascript functions
 **/
// TODO: Convert ajax code to be json-based
// TODO: Investigate on how easy it would be to make navigation show up in the history. In other words, hitting "Back" on the browser would go up one directory.
// Variable 'url_base' is expected to be set globally on the index.php page that refrences this file! This is used to make the ajax files absolutely linked.
function navigate(folder)
{
	var sound = document.getElementById("click_sound");
	sound.play();

	$.ajax({
		type: "POST",
		url: url_base+"ajax.browseDirectory.php",
		data: "f="+folder,
		success: function(data) {
			console.log(data);	//debug
			response = $.parseJSON(data);
			$("#fbBody").html(response.data);
			$("#fbPath").html(response.path);
			if (response.renderTimer != '')
				$("#renderTimer").html(response.renderTimer);
		},
		error: function() {
			alert("AJAX ERROR RESPONSE");	// TODO: MAKE THIS MORE USEFUL...
		}
	});
}

function openFile(filename, mime)
{
	var sound = document.getElementById("click_sound");
	sound.play();

	$.ajax({
		type: "POST",
		url: url_base+"ajax.fileInfo.php",
		data: "f="+filename+'&m='+mime,
		success: function(data) {
			//console.log(data);	//debug
			response = $.parseJSON(data);
			$("#dirListing").css("display","none");
			$("#fiBody").html(response.data);
			$("#fiPath").html(response.path);
			$("#fileInfo").css("display","block");
		},
		error: function() {
			alert("AJAX ERROR RESPONSE");	// TODO: MAKE THIS MORE USEFUL...
		}
	});
}

function closeFile()
{
	var sound = document.getElementById("click_sound");
	sound.play();

	$("#fiBody").html("");
	$("#fileInfo").css("display","none");
	$("#dirListing").css("display","block");
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