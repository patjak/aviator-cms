function LoadSiteTree(pid) {
	$.get("ajax/contents/site_tree.php", {pid: pid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			$("#SiteTree").html(json.html);
	});
}

function LoadToolbox(pid) {
	$.get("ajax/contents/toolbox.php", {pid: pid}, function(data) {
		if (!IsJsonValid(data)) 
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			$("#Toolbox").html(json.html);
	});
}

function ShowNewPage(parent_id) {
	$.get("ajax/contents/new_page_dlg.php", {parent_id: parent_id}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			DialogSet(json.html, 600);
		}
	});
}

function SetLayoutNewPage(layout_id, td) {
	$("table.ChooseLayout td").removeClass("Selected");
	$("#layout_id").val(layout_id);
	$(td).addClass("Selected");
}

function InsertNewPage(form, parent_id) {
	$.post("ajax/contents/insert_new_page.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadSiteTree(parent_id);
			LoadToolbox(parent_id);
		}
	});
}

function AskDeletePage(pid) {
	AskDialogSet("Delete page?", "<p>Do you really want to delete this page? "+
		     "All contents will be permanently removed and cannot be recovered!</p>", "DeletePage("+pid+")");
}

function DeletePage(pid) {
	$.get("ajax/contents/delete_page.php", { pid: pid }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			pid = 0;

		DialogHide();
		LoadSiteTree(pid);
		LoadToolbox(pid);
	});
}

function MovePageTop(pid) {
	$.get("ajax/contents/move_page.php", { pid: pid, top: 1 }, function(data) {
		LoadSiteTree(pid);
	});
}

function MovePageUp(pid) {
	$.get("ajax/contents/move_page.php", { pid: pid, up: 1 }, function(data) {
		LoadSiteTree(pid);
	});
}

function MovePageDown(pid) {
	$.get("ajax/contents/move_page.php", { pid: pid, down: 1 }, function(data) {
		LoadSiteTree(pid);
	});
}

function MovePageBottom(pid) {
	$.get("ajax/contents/move_page.php", { pid: pid, bottom: 1 }, function(data) {
		LoadSiteTree(pid);
	});
}

function ShowEditPage(pid) {
	$.get("ajax/contents/edit_page_dlg.php", {pid: pid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			// $("#DialogContents").html(json.html);
			// DialogShow();
			DialogSet(json.html, 600);
		}
	});
}

function ShowSelectStartPage() {
	$.get("ajax/contents/start_page_dlg.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			// $("#DialogContents").html(json.html);
			// DialogShow();
			DialogSet(json.html, 400);
		}
	});
}

function UpdateStartPage(form) {
	$.post("ajax/contents/update_start_page.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadSiteTree(0);
		}
	});
}


function UpdatePage(form, pid) {
	$.post("ajax/contents/update_page.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadSiteTree(pid);
			LoadToolbox(pid);
		}
	});
}

function SavePageImage(form) {
	$.post("ajax/contents/save_page_image.php", form.serialize(), function(data) {
		if (!IsJsonValid(data)) 
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			form.parent().slideUp(200);
	});
}


$(document).ready(function() {
	// Make sure the proper page is loaded
	if ($("#SiteTree").length <= 0)
		return;

	LoadSiteTree(0);
	LoadToolbox(0);


	// Global for keeping track of original top offset of the toolbox
	var toolbox_top_offset = $("#ToolboxBox").offset().top - 10;

	$(window).scroll(function() {
		var scroll_top = $(document).scrollTop();
		var top = 0;

		if (toolbox_top_offset < scroll_top)
			top = scroll_top - toolbox_top_offset;

		$("#ToolboxBox").css("top", top+"px");
	});
});
