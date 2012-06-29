<?php

$i = 1;
define("DASHBOARD_TYPE_DIALOG", $i++);
define("DASHBOARD_TYPE_MODULE", $i++);
define("DASHBOARD_TYPE_LINK", $i++);

class DashboardAPI extends DashboardCore {
}

class DashboardCore {
	private	$plugin,		// Reference to the plugin owning the entry
		$internal_id,
		$module,		// If entry is linked to a module, this is it
		$external_link,
		$title,
		$icon_64_filename,
		$icon_32_filename,
		$type = 0,		// Defaults to no type
		$type_internal_id,	// Sent to the dispatcher (or whatever handles this type)
		$content_file;

	static private $entries = array(); // List of all created dashboard entries

	public function DashboardCore(&$plugin, $internal_id)
	{
		$this->internal_id = $internal_id;
		$this->plugin = $plugin;
		DashboardCore::$entries[] = &$this;
	}

	public function SetType($type, $type_int_id = 0)
	{
		$this->type = $type;
		$this->type_internal_id = $type_int_id;
	}

	public function GetType()
	{
		return $this->type;
	}

	public function GetTypeInternalId()
	{
		return $this->type_internal_id;
	}

	public function SetExternalLink($url)
	{
		$this->external_link = $url;
	}

	public function GetLink()
	{
		return $this->external_link;
	}

	public function SetModule(&$module)
	{
		$this->module = $module;
	}

	public function GetModule()
	{
		return $this->module;
	}

	public function SetTitle($title)
	{
		$this->title = $title;
	}

	public function GetTitle()
	{
		return $this->title;
	}

	public function SetIcon64($filename)
	{
		$this->icon_64_filename = $filename;
	}

	public function GetIcon64()
	{
		return SITE_BASE."plugins/".$this->plugin->GetDirectory()."/".$this->icon_64_filename;
	}

	public function SetIcon32($filename)
	{
		$this->icon_32_filename = $filename;
	}

	public function GetIcon32()
	{
		return "plugins/".$this->plugin->GetDirectory()."/".$this->icon_32_filename;
	}

	public static function GetEntries()
	{
		return DashboardCore::$entries;
	}

	public function GetPlugin()
	{
		return $this->plugin;
	}
}
?>
