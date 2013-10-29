<?php

class PagesAPI {
	static function GetPagesAsArray($parent_id = 0)
	{
		$pages = array();

		if ($parent_id == 0) {
			$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id IS NULL ORDER BY sort");
		} else {
			$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id=".$parent_id." ORDER BY sort");
		}

		foreach ($res as $row) {
			$page_vo = DB::RowToObj("DaoPage", $row);
			$pages[] = $page_vo;
			$sub_pages = PagesAPI::GetPagesAsArray($page_vo->id);
			if (count($sub_pages) > 0)
				$pages[] = $sub_pages;
		}

		return $pages;
	}

	static function GetPagesAsOptions($selected_page = 0, $pages = false, $depth = 0)
	{
		if ($pages == false)
			$pages = PagesAPI::GetPagesAsArray();

		foreach ($pages as $page) {
			if (is_array($page)) {
				PagesAPI::GetPagesAsOptions($selected_page, $page, $depth + 1);
			} else {
				if ($selected_page == $page->id)
					$sel_str = "selected";
				else
					$sel_str = "";
				echo "<option value=\"".$page->id."\" ".$sel_str.">";
				for ($i = 0; $i < $depth; $i++)
					echo "-";
				echo " ".$page->title."</option>";
			}
		}
	}
}
