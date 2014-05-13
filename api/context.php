<?php

// Context keeps track of where in the directory structure a plugin are
// and which plugin / module / content are being processed

class Context {
	static	$directory,
		$plugin,
		$module,
		$content;

	private static	$is_frontend = FALSE;

	static function GetBase()
	{
		return SITE_BASE."plugins/".Context::$directory."/";
	}

	static function GetPath()
	{
		return SITE_PATH."plugins/".Context::$directory."/";
	}

	static function SetDirectory($directory)
	{
		Context::$directory = $directory;
	}

	static function SetPlugin(&$plugin)
	{
		self::$plugin = $plugin;
	}

	static function GetPlugin()
	{
		return self::$plugin;
	}

	static function GetModule()
	{
		return self::$module;
	}

	static function SetModule(&$module)
	{
		self::$module = $module;
	}

	static function GetContent()
	{
		return self::$content;
	}

	static function SetContent(&$content)
	{
		self::$content = $content;
	}

	static function SetFrontend()
	{
		self::$is_frontend = TRUE;
	}

	static function SetBackend()
	{
		self::$is_frontend = FALSE;
	}

	static function IsFrontend()
	{
		return self::$is_frontend;
	}

	static function IsBackend()
	{
		return !self::$is_frontend;
	}
}

?>
