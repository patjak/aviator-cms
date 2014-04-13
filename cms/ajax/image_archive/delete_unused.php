<?php
require_once("../include.php");

$res_images = DB::Query("SELECT * FROM images WHERE id NOT IN ".
			"(SELECT image_id FROM image_refs WHERE
			  image_id IS NOT NULL)");

foreach ($res_images as $row_image) {
	$image = new Image($row_image['id']);
	$image->Delete();
}

echo "<p>Unused images deleted</p>";

Ajax::SetStatus(AJAX_STATUS_SUCCESS);

?>
