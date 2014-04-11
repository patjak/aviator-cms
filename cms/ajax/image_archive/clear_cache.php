<?php
require_once("../include.php");

$res_cache = DB::Query("SELECT * FROM image_cache");
foreach ($res_cache as $row_cache) {
	$cache = DB::RowToObj("DaoImageCache", $row_cache);
	$image = new Image($cache->image_id);
	$ext = $image->GetExtension();

	unlink(MEDIA_PATH."images/".$cache->image_id."-".$cache->id."-".
	       $cache->crop_horizontal."x".$cache->crop_vertical."-".
	       $cache->effects.".".$ext);

	DB::Delete($cache);
}


echo "<p>Image cache is cleared</p>";

Ajax::SetStatus(AJAX_STATUS_SUCCESS);
?>
