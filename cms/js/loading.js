// Reference counter for the "loading dimmer"
var load_ref_counter = 0;
var load_timeout_id = 0;
var load_disabled = false;

/* These two functions puts a transparent div over the whole page
   and prevents any type of user interaction. This is usefull for
   preventing double clicks from novice users */
function DisableUserInput() {
	var height = $(document).height();

	$("#GrabUserInput").show();
	$("#GrabUserInput").height(height);
}

function EnableUserInput() {
	$("#GrabUserInput").hide();
}

function LoadShow() {
	/* If a timeout is active, we just increase the ref count */
	if (load_timeout_id != 0) {
		load_ref_counter++;
		return;
	}

	// Prevent user from double clicking (double sending)
	DisableUserInput();

	/* Give a chance to respond so we don't always get
	   a flickering screen even when doing quick loads */
	load_ref_counter++;
	load_timeout_id = setTimeout("LoadActuallyShow()", 250);
}

function LoadActuallyShow() {

	// We might have already been stopped
	if (load_ref_counter == 0)
		return;

	if (load_disabled)
		return;

	var height = $("#LoadContents").height();
	var doc_height = $(document).height();
	var win_height = $(window).height();
	var scroll_top = $(document).scrollTop();
	var top = ((win_height - height) / 2) + scroll_top;
	if (top < 0)
		top = 0;

	// A bug in jQuery seems to mess up the opacity, so restore it before showing
	$("#LoadDimmer").css('opacity', 0.85);
	$("#LoadContents").css('opacity', 1);

	$("#LoadDimmer").height(doc_height);
	$("#LoadDimmer").fadeIn(200);
	$("#LoadContents").css("top", top);
	$("#LoadContents").fadeIn(200);
};

function LoadHide() {
	load_ref_counter--;

	if (load_ref_counter <= 0) {
		load_timeout_id = 0; // Reset timeout
		load_ref_counter = 0; // Just to be on the safe side

		$("#LoadDimmer").stop();
		$("#LoadContents").stop();
		$("#LoadDimmer").fadeOut(200);
		$("#LoadContents").fadeOut(200);

		// Give back input control to user
		EnableUserInput();
	}
};

// Try hide all LoadShow() requests and stop any timer (might not always work)
function LoadHideDisable() {
	load_ref_counter = 0;
	LoadHide();
	clearTimeout(load_timeout_id);
	load_timeout_id = 0;
	load_disabled = true;
}

