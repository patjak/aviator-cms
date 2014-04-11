function ImageArchiveClearCache() {
	$.post("ajax/image_archive/clear_cache.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);
		if (json.status == 0) {
			DialogSet(json.html, 400);
		}
	});
}

function ImageArchiveRemoveUnused() {
	$.post("ajax/image_archive/clear_cache.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);
		if (json.status == 0) {
			DialogSet(json.html, 400);
		}
	});
}
