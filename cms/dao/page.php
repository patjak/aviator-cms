<?php

class DaoPage {
	public	$id,
		$title,
		$description,
		$image_ref_id,
		$parent_id,
		$layout_id,
		$type_id,
		$style_id,
		$sort,
		$published,
		$in_menu,
		$allow_edit,
		$allow_move,
		$allow_delete,
		$allow_subpage,
		$allow_change_style;
}

class DaoPageType {
	public	$id,
		$name,
		$module_id;
}

class DaoPageStyle {
	public	$id,
		$name,
		$theme_id;
}

?>
