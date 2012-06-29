function LoadPageContents() {
	var pid = $.GetUrlVar('pid');
	var section_id = $.GetUrlVar('section_id');

	$.get("ajax/contents_edit/page_contents.php", { pid: pid, section_id: section_id }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			$("#PageContents").html(json.html);
		}
	});
}

function ShowContent(content_div, plugin_id, internal_id, content_id) {
	$.get("ajax/contents_edit/show_content.php",
	{plugin_id: plugin_id, internal_id: internal_id, content_id: content_id}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			tmp = content_div.children('div.ContentEdit');
			tmp.html(json.html);
			tmp.show();
			UpdateCommon();
		}
	});

	// Change content name to an input and hide expand button
	var input_div = content_div.children('h2').children('input');
	var span_div = content_div.children('h2').children('span').last();
	input_div.val(span_div.text());
	input_div.show();
	input_div.focus(); // Always focus on input. This prevents bug in Chrome.
	span_div.hide();
}

function HideContent(content_div) {
	var input_div = content_div.children('h2').children('input');
	var span_div = content_div.children('h2').children('span').last();
	input_div.hide();
	span_div.show();

	// Hide content and show expand button
	content_div.children('div.ContentEdit').hide();
	content_div.children('div.Button').show();

	// Empty ContentEdit
	content_div.children('ContentEdit').html("");
}

function InsertContent(page_id, section_id, plugin_id, internal_id) {
	$.get("ajax/contents_edit/insert_content.php",
	{ page_id: page_id, section_id: section_id, plugin_id: plugin_id, internal_id: internal_id }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			$("#PageContents").append(json.html);
			$("#PageContents form").last().hide();
			$("#PageContents form").last().fadeIn();
			UpdateCommon();
		}

	});
}

function SaveContent(content_div, plugin_id, internal_id, content_id) {
	$.post("ajax/contents_edit/save_content.php?plugin_id="+plugin_id+"&internal_id="+internal_id+"&content_id="+content_id,
	content_div.parent().serialize(), function() {
		// Change content name to an input and hide expand button
		var input_div = content_div.children('h2').children('input');
		var span_div = content_div.children('h2').children('span').last();
		span_div.html(input_div.val());

		HideContent(content_div);
	});
}

function DeleteContent(plugin_id, internal_id, content_id) {
	$.get("ajax/contents_edit/delete_content.php", {plugin_id: plugin_id, internal_id: internal_id, content_id: content_id},
	function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			DialogHide();
			form = $("#content_form_"+content_id);
			form.fadeOut(function() {
				form.remove();
			});
		}
	});
}

function AskDeleteContent(plugin_id, internal_id, content_id) {
	AskDialogSet("Delete content?", "<p>Do you really want to delete this content?</p>",
		     "DeleteContent("+plugin_id+", "+internal_id+", "+content_id+")");
}

function MoveContentTop(content_id) {
	$.get("ajax/contents_edit/move_content.php", { content_id: content_id, top: 1 }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {

			form = $('#content_form_'+content_id);
			first = form.parent().children('form').first();
			if (first.length > 0) {
				form.fadeOut(function() {
					PrepareForClone();
					var clone = form.clone();
					first.before(clone);
					form.remove();
					clone.fadeIn();
					UpdateCommon();
				});
			}
		}
	});
}

function MoveContentUp(content_id) {
	$.get("ajax/contents_edit/move_content.php", { content_id: content_id, up: 1 }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			form = $('#content_form_'+content_id);
			prev = form.prev('form');
			if (prev.length > 0) {
				form.fadeOut(function() {
					PrepareForClone();
					var clone = form.clone();
					prev.before(clone);
					form.remove();
					clone.fadeIn();
					UpdateCommon();
				});
			}
		}
	});
}

function MoveContentDown(content_id) {
	$.get("ajax/contents_edit/move_content.php", { content_id: content_id, down: 1 }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			form = $('#content_form_'+content_id);
			next = form.next('form');
			if (next.length > 0) {
				form.fadeOut(function() {
					PrepareForClone();
					var clone = form.clone();
					next.after(clone);
					form.remove();
					clone.fadeIn();
					UpdateCommon();
				});
			}
		}
	});
}

function MoveContentBottom(content_id) {
	$.get("ajax/contents_edit/move_content.php", { content_id: content_id, bottom: 1 }, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) {
			form = $('#content_form_'+content_id);
			last = form.parent().children('form').last();
			if (last.length > 0) {
				form.fadeOut(function() {
					PrepareForClone();
					var clone = form.clone();
					last.after(clone);
					form.remove();
					clone.fadeIn();
					UpdateCommon();
				});
			}
		}
	});
}

$(document).ready(function() {
	if ($("#PageContents").length > 0)
		LoadPageContents();
});
