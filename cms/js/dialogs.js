function DialogSet(contents) {
	$("#DialogContents").width(600);
	$("#DialogClose").width(600 + 40);
	$("#DialogContents").html(contents);
	DialogShow();
}

function AskDialogSet(heading, contents, callback) {
	var str = '<h2><img src="pics/icons_32/help.png"/>'+heading+'</h2>'+contents+
		  '<div class="Ask"><div class="Heading"></div>'+
		  '<button type="submit" onclick="eval('+callback+');"><img src="pics/icons_24/check.png"/> Yes</button>'+
		  '<button onclick="DialogHide();"><img src="pics/icons_24/cross.png"/> No</button></div>';
	$("#DialogContents").html(str);
	$("#DialogContents").width(400); // Ask dialogs have a little less width
	$("#DialogClose").width(400 + 40);
	DialogShow();
	
}

function DialogShow() {
	// $("#DialogContents").width(600); // Restore width if Ask dialog has resized it
	var width = $("#DialogContents").width();
	var height = $("#DialogContents").height();
	var win_width = $(window).width();
	var win_height = $(window).height();
	var scroll_top = $(document).scrollTop();
	var top = ((win_height - height) / 2) + scroll_top;
	var left = (win_width - width) / 2;

	if (top < 36)
		top = 36;

	$("#DialogContents").css("top", top);
	$("#DialogContents").css("left", left);
	$("#DialogContents").fadeIn(400);

	$("#DialogClose").css("top", (top - 34));
	$("#DialogClose").css("left", left);
	$("#DialogClose").fadeIn(400);

	var doc_height = $(document).height();
	$("#DialogDimmer").height(doc_height);
	$("#DialogDimmer").fadeIn(400);
};

function DialogHide() {
	$("#DialogDimmer").stop();
	$("#DialogContents").stop();
	$("#DialogClose").stop();
	$("#DialogDimmer").fadeOut(400);
	$("#DialogContents").fadeOut(400, function() {
		$("#DialogContents").html("");
	});
	$("#DialogClose").fadeOut(400);
};

function HelpSet(header, contents) {
	var icon = '<img src="pics/icons_32/help.png"/>';
	var str = '<h2>'+icon+' '+header+'</h2>'+contents;
	$("#ErrorContents").html(str);
	ErrorShow(false);
}

function WarningSet(header, contents) {
	var icon = '<img src="pics/icons_32/warning.png"/>';
	var str = '<h2>'+icon+' '+header+'</h2>'+contents;
	$("#ErrorContents").html(str);
	ErrorShow(false);
}

function ErrorSet(contents, is_fatal) {
	$("#ErrorContents").html(contents);
	ErrorShow(is_fatal);
}

// If is_fatal is true, the dialog cannot be closed
function ErrorShow(is_fatal) {
	var width = $("#ErrorContents").width();
	var height = $("#ErrorContents").height();
	var win_width = $(window).width();
	var win_height = $(window).height();
	var scroll_top = $(document).scrollTop();
	var top = ((win_height - height) / 2) + scroll_top;
	var left = (win_width - width) / 2;

	if (top < 36)
		top = 36;

	$("#ErrorContents").css("top", top);
	$("#ErrorContents").css("left", left);
	$("#ErrorContents").fadeIn(400);

	if (!is_fatal) {
		$("#ErrorClose").css("top", (top - 34));
		$("#ErrorClose").css("left", left);
		$("#ErrorClose").fadeIn(400);
	} else {
		LoadHideDisable();
	}

	var doc_height = $(document).height();
	$("#ErrorDimmer").height(doc_height);
	$("#ErrorDimmer").fadeIn(400);

};

function ErrorHide() {
	$("#ErrorDimmer").stop();
	$("#ErrorContents").stop();
	$("#ErrorClose").stop();
	$("#ErrorDimmer").fadeOut(400);
	$("#ErrorContents").fadeOut(400, function() {
		$("#ErrorContents").html("");
	});
	$("#ErrorClose").fadeOut(400);
};

$(document).ready(function() {
	$("#DialogClose img").click(function() {
		DialogHide();
	});

	$("#ErrorClose img").click(function() {
		ErrorHide();
	});

	if ($("#ErrorContents").html() != "")
		ErrorShow(false);

	$(document).keydown(function(e) {
		if (e.keyCode == 27)
			DialogHide();
	});
});
