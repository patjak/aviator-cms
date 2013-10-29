<?php

class DaoImage extends DAO {
	public	$name,
		$description,
		$category_id,
		$width,
		$height,
		$format,
		$user_id;

	function __construct() {
		$this->table_name = "images";
	}
}

class DaoImageCache extends DAO {
	public	$image_id,
		$image_ref_id,
		$width,
		$height,
		$effects,
		$crop_horizontal,
		$crop_vertical;

	function __construct() {
		$this->table_name = "image_cache";
	}
}

class DaoImageRef extends DAO {
	public	$image_id,
		$plugin_id,
		$content_id,
		$internal_id,
		$sort,
		$link_id,
		$crop_horizontal,
		$crop_vertical;

	function __construct() {
		$this->table_name = "image_refs";
	}
}
?>
