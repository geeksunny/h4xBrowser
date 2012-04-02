/**
 * Javascript functions
 **/

// Variable 'url_base' is expected to be set globally on the index.php page that refrences this file! This is used to make the ajax files absolutely linked. // TODO: Figure out a better way to communicate this value between index.php and functions.js... I believe this is why the site doesn't work on mobile browsers.
function onPageLoad()	// used on initial page load.
{
	path = location.protocol + "//" + location.host + location.pathname;
	target = path.replace(window.url_base,"");

	$.ajax({
		type: "POST",
		url: window.url_base+"ajax.browse.php",
		data: "f="+target,
		success: function(data) {
			//console.log(data);	//debug
			try {
				response = $.parseJSON(data);
				$("#fbBody").fadeOut(150,function(){	// Fade the table out of view.
					$("#fbBody").html(response.data);	// Replaces the data once the table has faded out.
					$("#fbBody").fadeIn(150);			// Fade the table back in to view.
				});
				$("#fbPath").fadeOut(150,function(){	// Fade the path-text out of view.
					$("#fbPath").html(response.path);	// Replace the old path-text with the new path-text.
					$("#fbPath").fadeIn(150);			// Fade the path-text back in to view.
					// Determine the type of content that will be displayed (directory or file) and adjust the header accordingly!
					if (response.type == "directory")
					{
						// Display the column labels and the "browsing directory" label.
						$("#tableHeader").fadeIn(150);
						$("#labelBrowsing").fadeIn(150);
					}
					else	// Assuming the .type value returned was "file".
					{
						// Set the href value of the back link and fade it into view
						$("#backLink").attr("href",response.parent);
						$("#labelBack").fadeIn(150);
					}
				});
				if (response.renderTimer != '')
					$("#renderTimer").html(response.renderTimer);
				// Updating the browser history...
				history.pushState({ path: this.path }, '', window.url_base+target);
			} catch (e) {
				alert(data);		//debug	// Clean this up with another solution later?
				//console.log(e);	//debug
			}
		},
		error: function() {
			alert("AJAX ERROR RESPONSE");	// TODO: MAKE THIS MORE USEFUL...
		}
	});
}

/* -- jQuery code -- */
//$(function() {
$(document).ready(function() {
    // Allow proper floating of the header div.
	var stickyHeaderTop = $('#fbHeader').offset().top;
	$(window).scroll(function(){
		if( $(window).scrollTop() > stickyHeaderTop ) {
			$('#fbHeader').addClass('stickTop');
			//$('#fiHeader').addClass('stickTop');
		} else {
			$('#fbHeader').removeClass('stickTop');
			//$('#fiHeader').removeClass('stickTop');
		}
	});

	// TODO: Find a way to consolidate all these redundant ajax calls into a single function that can be called multiple times!
	// Event code for clicking a link in the directory listing.
	$("a.slider").live('click',function(e){
		e.preventDefault();
		target = $(this).attr('href').replace(window.url_base,'');	// Grab the variable

		var sound = document.getElementById("click_sound");
		sound.play();

		$.ajax({
			type: "POST",
			url: window.url_base+"ajax.browse.php",
			data: "f="+target,
			success: function(data) {
				//console.log(data);	//debug
				try {
					response = $.parseJSON(data);
					$("#fbBody").fadeOut(150,function(){	// Fade the table out of view.
						$("#fbBody").html(response.data);	// Replaces the data once the table has faded out.
						$("#fbBody").fadeIn(150);			// Fade the table back in to view.
					});
					$("#fbPath").fadeOut(150,function(){	// Fade the path-text out of view.
						$("#fbPath").html(response.path);	// Replace the old path-text with the new path-text.
						$("#fbPath").fadeIn(150);			// Fade the path-text back in to view.
						// Determine the type of content that will be displayed (directory or file) and adjust the header accordingly!
						if (response.type == "directory")
						{
							// If the table column lables are hidden, fade them into view
							if ($("#tableHeader").css("display") == "none")
								$("#tableHeader").fadeIn(150);
							// If the browsing directory label is hidden, fade the back link out and bring the label back into view.
							if ($("#labelBrowsing").css("display") == "none")
								$("#labelBack").fadeOut(150,function(){
									$("#labelBrowsing").fadeIn(150);
								});
						}
						else	// Assuming the .type value returned was "file".
						{
							// If the column labels are visible, fade them out
							if ($("#tableHeader").css("display") != "none")
								$("#tableHeader").fadeOut(150);
							// Set the href value of the back link, fade the browsing label out of view, and bring in the back link
							$("#backLink").attr("href",response.parent);
							$("#labelBrowsing").fadeOut(150,function(){
								$("#labelBack").fadeIn(150);
							});
						}
					});
					if (response.renderTimer != '')
						$("#renderTimer").html(response.renderTimer);
					// Updating the browser history...
					history.pushState({ path: this.path }, '', window.url_base+target);
				} catch (e) {
					alert(data);		//debug	// Clean this up with another solution later?
					//console.log(e);	//debug
				}
			},
			error: function() {
				alert("AJAX ERROR RESPONSE");	// TODO: MAKE THIS MORE USEFUL...
			}
		});
	});

	// Event code for clicking the "back" button on the web browser.
	$(window).bind('popstate', function(e) {
		if (!e.originalEvent.state)
			return;	// Workaround for popstate on load

		path = location.protocol + "//" + location.host + location.pathname;
		target = path.replace(window.url_base,"");

		var sound = document.getElementById("click_sound");
		sound.play();

		$.ajax({
			type: "POST",
			url: window.url_base+"ajax.browse.php",
			data: "f="+target,
			success: function(data) {
				//console.log(data);	//debug
				try {
					response = $.parseJSON(data);
					$("#fbBody").fadeOut(150,function(){	// Fade the table out of view.
						$("#fbBody").html(response.data);	// Replaces the data once the table has faded out.
						$("#fbBody").fadeIn(150);			// Fade the table back in to view.
					});
					$("#fbPath").fadeOut(150,function(){	// Fade the path-text out of view.
						$("#fbPath").html(response.path);	// Replace the old path-text with the new path-text.
						$("#fbPath").fadeIn(150);			// Fade the path-text back in to view.
						// Determine the type of content that will be displayed (directory or file) and adjust the header accordingly!
						if (response.type == "directory")
						{
							// If the table column lables are hidden, fade them into view
							if ($("#tableHeader").css("display") == "none")
								$("#tableHeader").fadeIn(150);
							// If the browsing directory label is hidden, fade the back link out and bring the label back into view.
							if ($("#labelBrowsing").css("display") == "none")
								$("#labelBack").fadeOut(150,function(){
									$("#labelBrowsing").fadeIn(150);
								});
						}
						else	// Assuming the .type value returned was "file".
						{
							// If the column labels are visible, fade them out
							if ($("#tableHeader").css("display") != "none")
								$("#tableHeader").fadeOut(150);
							// Set the href value of the back link, fade the browsing label out of view, and bring in the back link
							$("#backLink").attr("href",response.parent);
							$("#labelBrowsing").fadeOut(150,function(){
								$("#labelBack").fadeIn(150);
							});
						}					});
					if (response.renderTimer != '')
						$("#renderTimer").html(response.renderTimer);
				} catch (e) {
					alert(data);		//debug	// Clean this up with another solution later?
					//console.log(e);	//debug
				}
			},
			error: function() {
				alert("AJAX ERROR RESPONSE");	// TODO: MAKE THIS MORE USEFUL...
			}
		});
	});

	// File Preview image stuff
	$("#preview img").live("click", function() {
		//console.log("img: "+$(this).width());			//debug
		//console.log("window: "+$(window).width());	//debug

		if ($(this).width() == $(window).width())
		{
			$(this).css("width","auto");
		}
		else
		{
			$(this).css("width","100%");
		}
	});

});