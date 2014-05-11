<?php

class DaoPage extends DAO {
	public	$title,
		$description,
		$permalink,
		$permalink_assigned,
		$permalink_absolute,
		$permalink_hide_in_tree,
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

	function __construct()
	{
		$this->table_name = "pages";
	}
}

/*
class DaoPageType extends DAO {
	public	$name,
		$module_id;
}

class DaoPageStyle extends DAO {
	public	$name,
		$theme_id;
}

*/
?>
