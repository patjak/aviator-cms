/* Do some ui tweaks and stuff here. Needs to be called then fetching new content with AJAX */
function UpdateCommon() {	

	// Site tree specifics
	$("#SiteTree li div.Page").mouseover(function() {
		$(this).children("span.Attributes").show();
	});

	$("#SiteTree div.Page").mouseout(function() {
		$(this).children("span.Attributes").hide();
	});

	$("textarea.Resizable").each(function() {
		if ($(this).next().hasClass("TextareaHeight")) {
			$(this).next().next().remove();
			$(this).next().remove();
		}

		var val = $(this).html().replace(/\n/g, "<br/>");
		$(this).after('<div class="TextareaHeight"></div><textarea class="Hidden"></textarea>');
		var tmp_div = $(this).next();
		var tmp_text = $(this).next().next();
		tmp_text.val($(this).val());
		tmp_div.html(val+'<br/>');

		tmp_div.css('padding-left', $(this).css('padding-left'));
		tmp_div.css('padding-right', $(this).css('padding-right'));
		tmp_div.css('padding-top', $(this).css('padding-top'));
		tmp_div.css('padding-bottom', $(this).css('padding-bottom'));
		tmp_div.css('font-size', $(this).css('font-size'));
		tmp_div.css('font-family', $(this).css('font-family'));
		tmp_div.css('font-weight', $(this).css('font-weight'));
		
		tmp_div.css('width', $(this).css('width'));
		var diff = tmp_div.outerWidth() - tmp_div.width();
		tmp_div.css('width', tmp_div.width() - diff + 'px');

		var padding = tmp_div.outerHeight() - tmp_div.height();
		var new_height = tmp_div.outerHeight() + padding;
		var old_height = parseInt($(this).css('height'));
		$(this).css('height', new_height+'px');
	});

	$("textarea.Resizable").keyup(function() {
		/* Textarea.html() isn't updated on the fly so do it manually,
		   that's why we need the fake textarea. */
		$(this).next().next().html($(this).val());
		$(this).next().next().val($(this).val());
		var val = $(this).next().next().html();
		var tmp = $(this).next();

		val = val.replace(/\n/g, "<br/>");
		tmp.html(val + '<br/>');
		var padding = tmp.outerHeight() - tmp.height();
		var new_height = tmp.outerHeight() + padding;
		var old_height = parseInt($(this).css('height'));
		$(this).css('height', new_height+'px');
	});

	$("div.CroppingHint").click(function(e) {
		SetCroppingPoint(e, $(this));
	});

	// Content edit specifics
	$("div.Content span.ContentButtons").hide();

	$("div.Content").mouseover(function() {
		$(this).children("h2").first().children("span.ContentButtons").show();
	});

	$("div.Content").mouseout(function() {
		$(this).children("h2").first().children("span.ContentButtons").hide();
	});

	$("#image_link_is_internal").change(function() {
		UpdateLinkType();
	});

	// Initialize link visibility
	$("#image_link_enabled").each(function() {
		UpdateLinkVisibility();
	});

	$("#image_link_enabled").change(function() {
		UpdateLinkVisibility();
	});
	$("table.Minimizable caption").each(function() {
		$(this).unbind('click');

		$(this).click(function() {
			if ($(this).next("tbody").is(":visible"))
				$(this).next("tbody").fadeOut(250);
			else
				$(this).next("tbody").fadeIn(250);
		});
	});

	$("table.Minimized").each(function() {
		$(this).children('tbody').hide();
	});

	$("div.Accordion div.AccordionHeading").off("click");
	$("div.Accordion div.AccordionHeading").click(function() {
			$(this).next().stop();
			if ($(this).next().is(":visible"))
				$(this).next().slideUp(250);
			else
				$(this).next().slideDown(250);
	});
}

// When cloning DOM elements the state can be lost. Because of that we make sure
// the DOM state is copied into the DOM tree for common form elements
function PrepareForClone() {
	$("textarea").each(function() {
		$(this).html($(this).val());
	});

}

$(document).ready(function() {
	$(document).ajaxError(function(xhr, textStatus, errorThrown) {
		ErrorSet(xhr.responseText, true);
	});

	/* Whenever something is loaded in with ajax, make sure we update events for that HTML */
	$(document).ajaxSuccess(function(e, xhr, settings) {

		if (!IsJsonValid(xhr.responseText)) {
			ErrorSet(xhr.responseText, true);
			return;
		}

		var json = jQuery.parseJSON(xhr.responseText);

		switch (json.status) {
		case 0:	// Success
			break;

		case 1: // Error
			ErrorSet('<p class="Notice"><img src="pics/icons_64/broken.png"/>'+
			'A fatal error occured</p><p style="font-size: 9pt;">'+json.html+'</p>', true);
			break;

		case 2: // Timeout
			ErrorSet('<p class="Notice"><img src="pics/icons_64/clock.png"/>'+
				 'Your session has timed out. Please <a href=""><u>log in</u></a> again</p', true);
			break;

		case 3: // Warning
			ErrorSet('<h2><img src="pics/icons_32/warning.png"/> Warning</h2>'+json.html, false);
			break;

		case 4: // Notice
			ErrorSet('<h2><img src="pics/icons_32/warning.png"/> Notice</h2>'+json.html, false);
			break;
		}

		UpdateCommon();
		LoadHide();
	});

	$(document).ajaxSend(function() {
		LoadShow();
	});

	UpdateCommon();
});
