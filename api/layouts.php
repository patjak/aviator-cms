<?php

/* Describe the layout by specifying the width of the sections
   Note that header and footer are always 100 or 0 percent */
class Layout {
	public static	$entries = array(); // List of all layouts added by running theme

	public		$id, // Specified by the theme. This is stored in database and must never change.
			$name,
			$header = 0,
			$column_1 = 0,
			$column_2 = 0,
			$column_3 = 0,
			$column_4 = 0,
			$footer = 0;

	public static function Add($layout)
	{
		self::$entries[$layout->id] = $layout;
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
