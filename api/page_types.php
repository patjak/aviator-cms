<?php

/* The styles added by the active theme */ 
class PageType {
	public static	$entries = array(); // List of all page types added by running theme

	public		$id, // Specified by the theme. This is stored in database and must never change.
			$name;

	public static function Add($type)
	{
		$t = new PageType();
		$t->id = $type->id;
		$t->name = $type->name;

		self::$entries[$t->id] = $t;
	}

	public static function Get($id)
	{
		return self::$entries[$id];
	}

	public static function GetAll()
	{
		return self::$entries;
	}
}

?>
