<?php
require_once("../include.php");

$image_ref_id = (int)$_POST['image_ref_id'];
$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE id=".$image_ref_id);
$image_ref = DB::RowToObj("DaoImageRef", $res[0]);

$image_id = (int)$_POST['image_id_'.$image_ref_id];
$crop_x = (int)$_POST['crop_horizontal_'.$image_ref_id];
$crop_y = (int)$_POST['crop_vertical_'.$image_ref_id];

if ($image_id == 0)
	$image_id = NULL;

$image_ref->crop_horizontal = $crop_x;
$image_ref->crop_vertical = $crop_y;
$image_ref->image_id = $image_id;

DB::Update(DB_PREFIX."image_refs", $image_ref);

?>
