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

function ImageArchiveDeleteUnused() {
	$.post("ajax/image_archive/delete_unused.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);
		if (json.status == 0) {
			DialogSet(json.html, 400);
		}
	});
}
