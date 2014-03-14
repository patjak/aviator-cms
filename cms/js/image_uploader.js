function ShowImageUploader(image_ref_id, max_width, max_height, min_width, min_height, show_link) {
	$.get("ajax/image_uploader/image_uploader.php",
	{image_ref_id: image_ref_id, max_width: max_width, max_height: max_height,
	 min_width: min_width, min_height: min_height, show_link: show_link},
	function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			DialogSet(json.html, 600);
			UpdateImageArchive('', 0, 0, image_ref_id, max_width, max_height, min_width, min_height);
			DialogShow();
		}
	});
}

function UpdateImageArchive(filter, category_id, page, image_ref_id, max_width, max_height, min_width, min_height) {
	$.get("ajax/image_uploader/image_archive.php",
	{filter: filter, category_id: category_id, page: page, image_ref_id: image_ref_id,
	 max_width: max_width, max_height: max_height, min_width: min_width, min_height: min_height},
	function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			$("#ImageArchive").html(json.html);
			$("table.ImageChooser").fadeIn();
		}
	});
}

// FIXME: This is quite ugly and might not work in all browsers
function HandleUploadResponse(iframe) {
	var data = iframe.contentWindow.document.body.textContent;

	if (!IsJsonValid(data)) {
		if (data != "") {
			LoadHide();
			ErrorSet(data);
		}
		return;
	}

	json = jQuery.parseJSON(data);

	if (json.status == 0 || json.status == 1) {
		LoadHide();
		eval(json.html);
	}
}

function UpdateImageRefs(image_ref_id, image_id, max_width, max_height, min_width, min_height) {
	$("span.ImageRef_"+image_ref_id).each(function() {

		$.get("ajax/image_uploader/image_update.php",
		{image_ref_id: image_ref_id, image_id: image_id, max_width: max_width, max_height: max_height, min_width: min_width, min_height: min_height}, function(data) {
			if (!IsJsonValid(data))
				return;

			json = jQuery.parseJSON(data);

			if (json.status == 0) {
				$("span.ImageRef_"+image_ref_id).html(json.html);
				$("#input_image_ref_id_"+image_ref_id).val(image_id);
				$("img.ImageRefNoImage_"+image_ref_id).hide();
				// DialogHide();
				if (image_id != null)
					ShowCroppingDialog(image_ref_id, image_id);
				else
					DialogHide();
			}
		});
	})
}

function ShowCroppingDialog(image_ref_id, image_id) {
	$.get("ajax/image_uploader/cropping_dialog.php", {image_ref_id: image_ref_id, image_id: image_id}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			DialogSet(json.html);
			UpdateCommon();
		}
	});
}

function SetCroppingPoint(e, elem) {
	var pos_x = Math.round(e.pageX - elem.offset().left);
	var pos_y = Math.round(e.pageY - elem.offset().top);
	var image_ref_id = elem.children("#CroppingImageRefId").val();
	var width = elem.width();
	var height = elem.height();

	elem.children("div.x").css("top", pos_y+"px");
	elem.children("div.y").css("left", pos_x+"px");

	var crop_horizontal = Math.round((pos_x / width) * 100);
	var crop_vertical = Math.round((pos_y / height) * 100);

	$("#input_crop_x_"+image_ref_id).val(crop_horizontal);
	$("#input_crop_y_"+image_ref_id).val(crop_vertical);
}

function SaveImageLink(link_id) {
	var enabled = 0;
	if ($("#image_link_enabled").is(":checked"))
		enabled = 1;

	var in_new_window = 0;
	if ($("#image_link_in_new_window").is(":checked"))
		in_new_window = 1;

	var is_internal = $("#image_link_is_internal").val();
	var internal_page_id = $("#image_link_internal_page_id").val();
	var external_url = $("#image_link_external_url").val();

	$.post("ajax/image_uploader/update_image_link.php",
	{link_id: link_id, enabled: enabled, is_internal: is_internal,
	 internal_page_id: internal_page_id, external_url: external_url,
	 in_new_window: in_new_window}, function(data) {

		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			ErrorSet("Link information was updated", false);
			UpdateCommon();
		}
	});
}

function UpdateLinkType() {
	var val = $("#image_link_is_internal").val();

	$("#image_link_internal").stop();
	$("#image_link_external").stop();

	if (val == 0) {
		$("#image_link_internal").hide();
		$("#image_link_external").show();
	} else if (val == 1) {
		$("#image_link_external").hide();
		$("#image_link_internal").show();
	}
}

function UpdateLinkVisibility() {
	var checkbox = $("#image_link_enabled");

	if (checkbox.is(":checked")) {
		checkbox.parent().parent().siblings().show();
		UpdateLinkType();
	} else {
		checkbox.parent().parent().siblings().hide();
		checkbox.parent().parent().siblings().last().stop();
		checkbox.parent().parent().siblings().last().show();
	}
}

function ShowImageInfo(image_id) {
	$.post("ajax/image_uploader/image_info.php",
	{image_id: image_id }, function(data) {

		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			DialogSet(json.html, 600);
			UpdateCommon();
		}
	});

}
