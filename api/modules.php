<?php

define("MODULE_BACKEND", 1 << 0);
define("MODULE_FRONTEND", 1 << 1);

class ModuleAPI extends ModuleCore {
}

class ModuleCore {
	private static	$entries = array(); // List of all created content objects

	protected	$internal_id;	// A plugin internally unique number for
					// plugins with more than 1 module entry

	private		$title,
			$icon_32_filename,
			$icon_64_filename,

			// List of css includes for backend and frontend
			$css_backend = array(),
			$css_frontend = array(),

			// List of js include for backend and frontend
			$js_backend = array(),
			$js_frontend = array(),

			$views = array(),
			$action = array();

	public		$plugin;

	public function ModuleCore(&$plugin, $internal_id = 1)
	{
		$this->internal_id = $internal_id;
		$this->plugin = $plugin;
		ModuleCore::$entries[] = &$this;
	}

	public function SetTitle($title)
	{
		$this->title = $title;
	}

	public function GetTitle()
	{
		return $this->title;
	}

	public function AddView($filename, $id)
	{
		$this->views[$id] = $filename;
	}

	public function GetView($id)
	{
		return $this->views[$id];
	}

	public function GetCurrentView()
	{
		if (isset($_GET['view']))
			return (int)$_GET['view'];
		else
			return false;
	}

	public function GetViewUrl($view = 0)
	{
		$plugin_id = (int)$_GET['plugin'];
		$module_id = (int)$_GET['module'];
		if ($view == 0) {
			if (isset($_GET['view']))
				$view = (int)$_GET['view'];
			else
				$view = 0;
		}

		return CMS_BASE."?page=".PAGE_MODULES."&plugin=".$plugin_id."&module=".$module_id."&view=".$view;
	}

	public function AddAction($filename, $id)
	{
		$this->actions[$id] = $filename;
	}

	public function GetActionUrl($action)
	{
		$view_url = $this->GetViewUrl();

		return $view_url."&action=".$action;
	}

	public function AddCss($filename, $flags = MODULE_BACKEND)
	{
		if ($flags & MODULE_BACKEND)
			$this->css_backend[] = $filename;

		if ($flags & MODULE_FRONTEND)
			$this->css_frontend[] = $filename;
	}

	public function AddJs($filename, $flags = MODULE_BACKEND)
	{
		if ($flags & MODULE_BACKEND)
			$this->js_backend[] = $filename;

		if ($flags & MODULE_FRONTEND)
			$this->js_frontend[] = $filename;
	}

	public function GetCssBackendList()
	{
		return $this->css_backend;
	}

	public function GetCssFrontendList()
	{
		return $this->css_frontend;
	}

	public function GetJsBackendList()
	{
		return $this->js_backend;
	}

	public function GetJsFrontendList()
	{
		return $this->js_frontend;
	}

	public function SetIcon32($filename)
	{
		$this->icon_32_filename = $filename;
	}

	public function GetIcon32()
	{
		return "../plugins/".$this->plugin->GetDirectory()."/".$this->icon_32_filename;
	}

	public function SetIcon64($filename)
	{
		$this->icon_64_filename = $filename;
	}

	public function GetIcon64()
	{
		return "../plugins/".$this->plugin->GetDirectory()."/".$this->icon_64_filename;
	}

	// Used internally to set id when content container is created
	public function SetId($id)
	{
		$this->internal_id = $id;
	}

	public function GetId()
	{
		return $this->internal_id;
	}

	static private function CompareEntryTitles($a, $b)
	{
		return strcmp($a->GetTitle(), $b->GetTitle());
	}

	// Returns an array with contents registered for the provided section_id
	static public function GetRegistered($section_id)
	{
		$list = Array();

		foreach (ModuleCore::$entries as $entry) {
			if ($section_id & $entry->widths)
				$list[] = $entry;
		}

		// Sort the entries by name
		usort($list, "ModuleCore::CompareEntryTitles");

		return $list;
	}

	static public function GetByPluginAndInternal($plugin_id, $internal_id)
	{
		foreach (ModuleCore::$entries as $entry) {
			if ($plugin_id == $entry->plugin->GetId() && $internal_id == $entry->GetId())
				return $entry;
		}
		return false;
	}

	public static function GetEntries()
	{
		return ModuleCore::$entries;
	}
}

?>
