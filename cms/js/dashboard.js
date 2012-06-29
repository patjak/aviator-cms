function ShowDashboard(title, filename) {
	$.get(filename, function(data) {
		if (!IsJsonValid(data)) {
			ErrorSet(data, true);
			return;
		}

		var json = jQuery.parseJSON(data);

		if (json.status != 0)
			return;

		DialogSet("<h2>"+title+"</h2>"+json.html);
	});
}
