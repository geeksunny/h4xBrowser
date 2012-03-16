/**
 * Javascript functions
 **/
// TODO: Convert ajax code to be json-based
// TODO: Investigate on how easy it would be to make navigation show up in the history. In other words, hitting "Back" on the browser would go up one directory.
function navigate(folder)
{
	var sound = document.getElementById("click_sound");
	sound.play();

	$.ajax({
		type: "POST",
		url: "ajax.browseDirectory.php",
		data: "f="+folder,
		success: function(data) {
			response = $.parseJSON(data);
			$("#dirListing").html(response.data);
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
		url: "ajax.fileInfo.php",
		data: "f="+filename+'&m='+mime,
		success: function(data) {
			response = $.parseJSON(data);
			$("#dirListing").css("display","none");
			$("#fileInfo").html(response.data);
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

	$("#fileInfo").html("");
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