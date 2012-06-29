<?php

class Settings {
	public static function Get($name)
	{
		$res = DB::Query("SELECT value FROM ".DB_PREFIX."settings WHERE name='".$name."'");
		if ($row = DB::Row($res))
			return $row[0];
		else
			return false;
	}

	public static function Set($name, $value)
	{
		// We assume $name never comes from the user and thus doesn't need to be escaped
		$value = mysql_real_escape_string($value);

		$res = DB::Query("SELECT id FROM ".DB_PREFIX."settings WHERE name='".$name."'");
		if (DB::NumRows($res) > 0)
			DB::Query("UPDATE ".DB_PREFIX."settings SET value='".$value."' WHERE name='".$name."'");
		else
			DB::Query("INSERT INTO ".DB_PREFIX."settings (name, value) VALUES('".$name."', '".$value."')");
	}
}

class PluginSettings {
	public static function Get($name)
	{
		$res = DB::Query("SELECT value FROM ".DB_PREFIX."plugin_settings WHERE name='".$name."'");
		if ($row = DB::Row($res))
			return $row[0];
		else
			return false;
	}

	public static function Set($name, $value)
	{
		// We assume $name never comes from the user and thus doesn't need to be escaped
		$value = mysql_real_escape_string($value);

		$res = DB::Query("SELECT id FROM ".DB_PREFIX."plugin_settings WHERE name='".$name."'");
		if (DB::NumRows($res) > 0)
			DB::Query("UPDATE ".DB_PREFIX."plugin_settings SET value='".$value."' WHERE name='".$name."'");
		else
			DB::Query("INSERT INTO ".DB_PREFIX."plugin_settings (name, value) VALUES('".$name."', '".$value."')");
	}
}

?>
