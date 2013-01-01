<?php

/* The styles added by the active theme */ 
class Style {
	public static	$entries = array(); // List of all layouts added by running theme

	public		$id, // Specified by the theme. This is stored in database and must never change.
			$name;

	public static function Add($style)
	{
		self::$entries[$style->id] = $style;
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
