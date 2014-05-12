<?php

class Permalink {
	static function GetDefaultFromPage($page)
	{
		$permalink = self::TitleToLink($page->title);
		$permalink = self::FixNameCollisions($page, $permalink);

		return $permalink;
	}

	/**
	 * Suggest a new non-coliding permalink. If this permalink collides with
	 * an existing permalink we add the page id at the end
	 */
	static private function FixNameCollisions($page, $permalink)
	{
		$res = DB::Query("SELECT * FROM pages WHERE ".
				 "(permalink=:permalink OR permalink_assigned=:permalink) ".
				 "AND id != :id AND parent_id=:parent_id",
				 array("permalink" => $permalink, "id" => $page->id,
				       "parent_id" => $page->parent_id));

		if (count($res) > 0)
			$permalink .= "-".$page->id;

		return $permalink;
	}

	static function TitleToLink($title)
	{
		$title = strtolower(trim($title));
		$title = str_replace("å", "a", $title);
		$title = str_replace("ä", "a", $title);
		$title = str_replace("ö", "o", $title);
		$title = preg_replace('/[^a-z0-9-]/', '-', $title);
		$title = preg_replace('/-+/', "-", $title);

		return $title;
	}

	static function Search($permalink, $parent_id = NULL)
	{
		if ($parent_id == NULL)
			$pages = Theme::GetTopPages();
		else
			$pages = Theme::GetPageChildren($parent_id);

		foreach ($pages as $page) {
			// First try pages at this depth, so skip hidden
			if ($page->permalink_hide_in_tree == 1)
				continue;

			// Some pages might not have an assigned permalink
			if ($page->permalink_assigned == "") {
				$page->permalink_assigned = Permalink::TitleToLink($page->title);
				DB::Update($page);
			}

			if ($page->permalink != "")
				$link = $page->permalink;
			else
				$link = $page->permalink_assigned;

			if ($permalink == $link)
				return $page->id;
		}

		// Now try the sub-pages for the hidden ones
		foreach ($pages as $page) {
			if ($page->permalink_hide_in_tree != 1)
				continue;

			$page_id = self::Search($permalink, $page->id);
			if ($page_id != NULL)
				return $page_id;
		}

		return FALSE;
	}
}

?>
