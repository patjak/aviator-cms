<?php

class Pages {
	static private	$page_id = 0,
			$page_cache = array(),
			$top_page_cache = array();

	/**
	 * Find the start page and return it
	 */
	static public function GetStartPage()
	{
		$start_page_id = Settings::Get("site_start_page");

		// If no start page is specified, then select the first top page
		if ($start_page_id == 0) {
			$pages = Pages::GetTopPages();

			// We might not have any pages
			if (count($pages) == 0)
				return false;

			$start_page_id = $pages[0]->id;
		}

		return Pages::Get($start_page_id);
	}

	/**
	 * Initialize the Pages API
	 */
	static public function Init()
	{
		if (!isset($_GET['page_id'])) {
			$start_page = self::GetStartPage();
			if ($start_page === false)
				self::$page_id = 0;
			else
				self::$page_id = $start_page->id;
		} else {
			self::$page_id = (int)$_GET['page_id'];
		}
	}

	/**
	 * Returns an array with the top most pages in the site tree
	 */
	static public function GetTopPages()
	{
		// Return the cache if it exists
		if (count(self::$top_page_cache) == 0) {

			// Only get published pages if used from frontend
			$pub_str = "";
			if (Context::IsFrontend())
				$pub_str = "AND published=1";

			$res = DB::Query("SELECT * FROM pages ".
					 "WHERE parent_id IS NULL ".$pub_str." ".
					 "ORDER BY sort");
			foreach ($res as $row) {
				$page = DB::RowToObj("DaoPage", $row);
				self::$top_page_cache[] = $page;

				/* Since we must fetch this page we might as
				 * well store it in the page cache */
				self::$page_cache[$page->id] = $page;
			}
		}

		return self::$top_page_cache;
	}

	/**
	 * Overwrite the current page
	 * Returns the newly set page object or false on failure
	 */
	static public function SetID($id)
	{
		$page = self::Get($id);
		if ($page === false)
			return false;

		self::$page_id = $id;
		return $page;
	}

	/**
	 * Get the currently set page id
	 */
	static public function GetID()
	{
		return self::$page_id;
	}

	/**
	 * Get page object with the specified ID
	 * Returns false if no page was found
	 */
	static public function Get($id = 0)
	{
		if ($id == 0)
			$id = self::$page_id;

		if (isset(self::$page_cache[$id]))
			return self::$page_cache[$id];

		$page = DB::ObjByID("DaoPage", $id);
		if ($page === false)
			return false;

		self::$page_cache[$id] = $page;
		return $page;
	}

	static public function GetPermalink($page_id)
	{
		$page = self::Get($page_id);

		if ($page->permalink_hide_in_tree == 1) {
			if ($page->parent_id > 0)
				return self::GetPermalink($page->parent_id);
			else
				return "";
		}

		$permalink = ($page->permalink != "") ? $page->permalink :
							$page->permalink_assigned;

		// Fixup empty permalinks
		if ($permalink == "") {
			$page->permalink_assigned = Permalink::TitleToLink($page->title);
			DB::Update($page);
		}

		if ($page->parent_id > 0) {
			$parent_link = self::GetPermalink($page->parent_id);
			if ($parent_link != "")
				$permalink = $parent_link."/".$permalink;
		}

		return $permalink;
	}

	static public function GetDescription($page_id = 0)
	{
		$page = self::Get($page_id);

		if ($page->description == "" && $page->parent_id != NULL)
			return self::GetDescription($page->parent_id);
		else
			return $page->description;
	}

	static public function GetURL($page_id = 0)
	{
		if ($page_id == 0)
			$page_id == self::Get()->id;

		$start_page_id = self::GetStartPage()->id;
		if ($start_page_id == $page_id)
			return SITE_BASE;

		// If this page is hidden in permalink tree we link to the first child
		$page = self::Get($page_id);
		if ($page->permalink_hide_in_tree) {
			$children = self::GetChildren($page_id);

			// If no child exists, we link to this page but without permalink
			if (count($children) > 0)
				$page_id = $children[0]->id;
			else
				return SITE_BASE."?page_id=".$page_id;
		}

		$permalink = self::GetPermalink($page_id);
		if ($permalink === FALSE)
			return SITE_BASE."?page_id=".$page_id;
		else
			return SITE_BASE.$permalink;
	}

	/**
	 * Returns an array of pages matching the specified name
	 */
	static public function GetByName($name)
	{
		$pages = array();
		$res = DB::Query("SELECT * FROM pages WHERE title=:name",
				 array("name" => $name));

		foreach ($res as $row) {
			$page = DB::RowToObj("DaoPage", $row);
			$pages[] = $page;

			// Store in cache
			self::$page_cache[$page->id] = $page;
		}

		return $pages;
	}

	/* Private function for recursion */
	static private function FindDepth($page, $depth = 0)
	{
		if ($page->parent_id == NULL)
			return $depth;
		else
			return self::FindDepth(self::Get($page->parent_id), ++$depth);
	}

	static public function GetDepth($id = 0)
	{
		return self::FindDepth(self::Get($id));
	}

	static private function FindParent($parent_id, $depth)
	{
		if (self::GetDepth($parent_id) == $depth)
			return self::Get($parent_id);
		else
			return self::FindParent(self::Get($parent_id)->parent_id, $depth);
	}

	/**
	 *  Get a parent at a specific page depth
	 */
	static public function GetParentAtDepth($depth)
	{
		$page = self::Get();

		if (self::GetDepth($page->id) <= $depth)
			return false;

		return self::FindParent($page->parent_id, $depth);
	}

	/**
	 *  Check if page is an ancestor to the specified page
	 */
	static public function IsAncestor($parent_id, $page_id = 0)
	{
		if ($page_id === NULL)
			return false;

		$page = self::Get($page_id);

		if ($page->parent_id != NULL) {
			if ($parent_id == $page->parent_id)
				return true;
			else
				return self::IsAncestor($parent_id, $page->parent_id);
		}

		return false;
	}

	static public function NumChildren($id)
	{
		$res = DB::Query("SELECT id FROM pages WHERE parent_id=:id",
				 array("id" => $id));
		return count($res);
	}

	static public function GetChildren($id = 0)
	{
		$parent = self::Get($id);
		$array = array();

		$res = DB::Query("SELECT * FROM pages WHERE parent_id=:parent_id ".
				 "ORDER BY sort ASC", array("parent_id" => $parent->id));
		foreach ($res as $row) {
			$page = DB::RowToObj("DaoPage", $row);

			// Store in cache
			self::$page_cache[$page->id] = $page;

			if (Context::IsFrontend() && $page->published == 0)
				continue;

			$array[] = $page;
		}

		return $array;
	}

	static public function GetTitle()
	{
		$page = self::Get();
		return $page->title;
	}

	static public function GetLanguageID($page_id = 0)
	{
		$page = self::Get($page_id);

		if ($page->language_id != NULL) {
			return $page->language_id;
		} else if ($page->language_id == NULL && $page->parent_id == NULL) {
			return false;
		} else {
			return self::GetLanguageID($page->parent_id);
		}
	}

	static public function GetLanguage($page_id = 0)
	{
		$lang_id = self::GetLanguageID($page_id);

		if ($lang_id === false)
			return false;

		$res = DB::Query("SELECT name FROM languages WHERE id=:id",
				 array("id" => $lang_id));
		$row = DB::Row($res);

		return $row[0];
	}

}

?>
