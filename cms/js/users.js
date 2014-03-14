function LoadUsersMain() {
	$.get("ajax/users/main.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			$("#UsersMain").html(json.html);
	});
}

function InsertNewUser(form) {
	$.post("ajax/users/insert_user.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadUsersMain();
		}
	});
}

function ShowNewUserDlg() {
	$.get("ajax/users/new_user_dlg.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html, 300);
	});
}

function ShowEditUserDlg(uid) {
	$.post("ajax/users/edit_user_dlg.php", {uid: uid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html, 600);
	});
}

function UpdateUser(form) {
	$.post("ajax/users/update_user.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadUsersMain();
		}
	});

}

function DeleteUser(uid) {
	$.post("ajax/users/delete_user.php", {uid: uid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		DialogHide();

		if (json.status == 0) {
			LoadUsersMain();
		}
	});

}

function AskDeleteUser(uid) {
	AskDialogSet("Are you sure?", "Really delete user?", "DeleteUser("+uid+")");
}

function ShowNewGroupDlg() {
	$.get("ajax/users/new_group_dlg.php", function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html, 300);
	});
}

function InsertNewGroup(form) {
	$.post("ajax/users/insert_group.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0) { // Success
			DialogHide();
			LoadUsersMain();
			eval(json.html); // ShowEditGroupDlg(gid);
		}
	});
}

function ShowEditGroupDlg(gid) {
	$.post("ajax/users/edit_group_dlg.php", {gid: gid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html, 600);
	});
}

function InsertGroupPagePermission(form) {
	$.post("ajax/users/insert_group_page_permission.php", form.serialize(), function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		if (json.status == 0)
			DialogSet(json.html, 600);
	});
}

function DeleteGroup(gid) {
	$.post("ajax/users/delete_group.php", {gid: gid}, function(data) {
		if (!IsJsonValid(data))
			return;

		json = jQuery.parseJSON(data);

		DialogHide();

		if (json.status == 0) {
			LoadUsersMain();
		}
	});

}

function AskDeleteGroup(gid) {
	AskDialogSet("Are you sure?", "Really delete group?", "DeleteGroup("+gid+")");
}


$(document).ready(function() {
	LoadUsersMain();
});

