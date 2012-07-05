<?php

class PluginAPI extends PluginCore {

	// Called when the plugin is loaded into the system
	public function Initialize()
	{
	}

	// Called when plugin is loaded into the system for the first time
	public function Install()
	{
	}

	// Called when admin requests the plugin to be removed entirely
	public function Uninstall()
	{
	}
}

class PluginCore {
	private	static	$entries = array();
	private static	$registered_entries = 0;

	private		$id,	// Corresponds to database id
			$title,
			$version_major,
			$version_minor,
			$version_patch,
			$directory;

	public function SetTitle($title)
	{
		$this->title = $title;
	}

	public function GetTitle()
	{
		return $this->title;
	}

	public function SetVersion($major, $minor, $patch)
	{
		$this->version_major = $major;
		$this->version_minor = $minor;
		$this->version_patch = $patch;
	}

	public function GetVersion(&$major, &$minor, &$patch)
	{
		$major = $this->version_major;
		$minor = $this->version_minor;
		$patch = $this->version_patch;
	}

	public function GetBase()
	{
		return SITE_BASE."plugins/".$this->directory."/";
	}

	public function GetPath()
	{
		return SITE_PATH."plugins/".$this->directory."/";
	}

	public function GetDirectory()
	{
		return $this->directory;
	}

	// Internal methods. Don't touch!
	function PluginCore()
	{
		// Load plugin into system
		PluginCore::$entries[] = $this;
		$this->Initialize();
	}

	public function SetId($id)
	{
		$this->id = $id;
	}

	public function GetId()
	{
		return $this->id;
	}

	// Find all plugins and register them into the system
	public static function FindAndLoadAll()
	{
		$dir = getcwd() ."/";
		$dir .= "../plugins";
		$dir = SITE_PATH . "plugins";
		$dir_res = opendir($dir);
		while ($dir_name = readdir($dir_res)) {
			if (is_dir($dir."/".$dir_name) && $dir_name != "." && $dir_name != "..") {
				Context::SetDirectory("plugins/".$dir_name."/");
				require_once($dir."/".$dir_name."/plugin.php");

				// If the plugin registered we add the directory to it
				if (PluginCore::RegisterLastLoad($dir_name))
					echo "<p>No register</p";
			}
		}
		closedir($dir_res);
	}

	public static function RegisterLastLoad($directory)
	{
		if (count(PluginCore::$entries) == 0)
			return true;

		// Only register if not already registered
		if (PluginCore::$registered_entries < count(PluginCore::$entries)) {
			$last_loaded = count(PluginCore::$entries) - 1;

			$entry =& PluginCore::$entries[$last_loaded];
			$entry->directory = $directory;
			PluginCore::$registered_entries++;

			// Get id or install if neccessary
			$res_plugin = DB::Query("SELECT id FROM ".DB_PREFIX."plugins WHERE directory='".$directory."'");
			if (DB::NumRows($res_plugin) == 1) {
				$row_plugin = DB::Row($res_plugin);
				$entry->id = $row_plugin[0];
			} else {
				DB::Query("INSERT INTO plugins (directory) values('".$directory."')");
				$entry->id = DB::InsertID();
				$entry->Install();
			}
		}

		return false;
	}

	// Return the plugin with the specified title
	public static function GetByTitle($name)
	{
		$entries = PluginCore::GetEntries();

		foreach ($entries as $entry) {
			if ($entry->GetTitle() == $name)
				return $entry;
		}
		return false;
	}

	public static function GetById($id)
	{
		$entries = PluginCore::GetEntries();

		foreach ($entries as $entry) {
			if ($entry->GetId() == $id)
				return $entry;
		}

		return false;
	}

	public static function GetEntries()
	{
		return PluginCore::$entries;
	}
}

?>
