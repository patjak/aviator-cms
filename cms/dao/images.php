<?php

class DaoImage {
	public	$id, 
		$name,
		$description,
		$category_id,
		$width,
		$height,
		$format;
}

class DaoImageCache {
	public	$id,
		$image_id,
		$image_ref_id,
		$width,
		$height,
		$effects,
		$crop_horizontal,
		$crop_vertical;
}

class DaoImageRef {
	public	$id,
		$image_id,
		$plugin_id,
		$content_id,
		$internal_id,
		$sort,
		$link_id,
		$crop_horizontal,
		$crop_vertical;
}
?>
