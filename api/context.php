<?php

// Context keeps track of where in the directory structure a plugin are
// and which plugin / module / content are being processed

class Context {
	static	$directory,
		$plugin,
		$module,
		$content;

	static function GetBase()
	{
		return SITE_BASE.Context::$directory;
	}

	static function SetDirectory($directory)
	{
		Context::$directory = $directory;
	}
}



?>
