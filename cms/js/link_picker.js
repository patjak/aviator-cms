function LinkPickerShow(link_id) {
	var name = $("#link_picker_"+link_id+"_name").val();
	var is_internal = $("#link_picker_"+link_id+"_is_internal").val();
	var in_new_window = $("#link_picker_"+link_id+"_in_new_window").val();
	var internal_page_id = $("#link_picker_"+link_id+"_internal_page_id").val();
	var external_url = $("#link_picker_"+link_id+"_external_url").val();
	var enabled = $("#link_picker_"+link_id+"_enabled").val();

	$.post("ajax/link_picker/edit_link.php", {link_id: link_id, name: name, is_internal: is_internal, in_new_window: in_new_window,
	internal_page_id: internal_page_id, external_url: external_url,
	enabled: enabled}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html);

	});
}

function UpdateLinkPicker(link_id) {
	// There can be only one
	var name = $("#link_picker_name").val();
	var is_internal = $("#link_picker_is_internal").val();
	var in_new_window = $("#link_picker_in_new_window").is(":checked");
	var internal_page_id = $("#link_picker_internal_page_id").val();
	var external_url = $("#link_picker_external_url").val();
	var enabled = $("#link_picker_enabled").is(":checked");

	if (enabled)
		enabled = 1;
	else
		enabled = 0;

	if (in_new_window)
		in_new_window = 1;
	else
		in_new_window = 0;

	$.post("ajax/link_picker/update_link.php", {link_id: link_id, name: name, is_internal: is_internal, in_new_window: in_new_window,
	internal_page_id: internal_page_id, external_url: external_url,
	enabled: enabled}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			$("#link_picker_"+link_id).html(json.html);
			DialogHide();
		}
	});
}

function UpdateLinkPickerType(select) {
	var type = parseInt(select.val());

	if (type == 1) {
		select.parent().parent().siblings('#link_picker_internal').show();
		select.parent().parent().siblings('#link_picker_external').hide();
	} else {
		select.parent().parent().siblings('#link_picker_internal').hide();
		select.parent().parent().siblings('#link_picker_external').show();
	}
}
