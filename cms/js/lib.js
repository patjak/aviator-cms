/* These are common library functions used to ease the use of jQuery tasks */

// We do a quick json check here, we know that there is always a status value in our json
function IsJsonValid(str) {
	var valid = false;
	if (str.substring(0, 10) == '{"status":' && str.substring(str.length - 1) == '}')
		valid = true;

	return valid;
}

// Help us get values of URL parameters
$.extend({
	GetUrlVars: function() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}

		return vars;
	}, GetUrlVar: function(name) {
		return $.GetUrlVars()[name];
	}
});


