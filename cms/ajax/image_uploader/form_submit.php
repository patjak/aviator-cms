<?php
require_once("../include.php");

$image_ref_id = (int)$_POST['image_ref_id'];
$image_name = $_POST['image_name'];
$image_description = $_POST['image_description'];
$image_category_id = (int)$_POST['image_category_id'];
$image_category_name = $_POST['image_category_name'];
$max_width = (int)$_POST['max_width'];
$max_height = (int)$_POST['max_height'];
$min_width = (int)$_POST['min_width'];
$min_height = (int)$_POST['min_height'];

/* This file returns javascript that will be evaluated by the parent window
   We count on the browser to create DOM around this. A bit hackisch IMHO. */

$error_msg = "";

if (!isset($_FILES['file'])) {
	$error_msg = "No file was specified";
} else {
	if ($image_name == "")
		$image_name = $_FILES['file']['name'];

	$image_id = ImageUploader::HandleUpload($_FILES['file'], $image_name, $image_description,
						$image_category_id, $image_category_name, $error_msg);
	if ($image_id > 0) {
		$update_refs = "UpdateImageRefs(".$image_ref_id.", ".$image_id.", ".$max_width.", ".$max_height.", ".$min_width.", ".$min_height.")";
	} else {
		$update_refs = "";
	}
}

if ($error_msg != "") {
	Ajax::ClearOutput(); 
	Ajax::ShowErrors(false);
	// If warnings or errors are shown, we need to remove them here
	// The image handler should be able to cope with whatever comes up
	echo "WarningSet('Image upload failed', '<p>".$error_msg."</p>');";
}

if ($update_refs != "")
	echo $update_refs;
?>
