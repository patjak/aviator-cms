<?php
class Theme {
	private	static	$page_id = 0,
			$page_vo = false,
			$style_vo,
			$theme_path,
			$theme_base,

			$content_width_100 = 0,
			$content_width_75 = 0,
			$content_width_66 = 0,
			$content_width_50 = 0,
			$content_width_33 = 0,
			$content_width_25 = 0,

			$section_header_height = 0,
			$section_column_1_height = 0,
			$section_column_2_height = 0,
			$section_column_3_height = 0,
			$section_column_4_height = 0,
			$section_footer_height = 0,

			$image_margin = 0;

	static public function RenderContent($section_id, $content_vo)
	{
		$width = Theme::GetSectionWidth($section_id, $content_vo->page_id);
		$height = Theme::GetSectionHeight($section_id); // FIXME: Need to add page_id

		$content = ContentCore::GetByPluginAndInternal($content_vo->plugin_id, $content_vo->internal_id);
		$content->id = $content_vo->id;
		Context::SetDirectory($content->plugin->GetDirectory());
		$content->Render($section_id, $width, $height);
	}

	static public function GetSectionContents($section_id, $page_id = 0)
	{
		$contents = array();

		$page_vo = Pages::Get($page_id);
		if ($page_vo === false)
			return array();

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."contents WHERE page_id=".$page_vo->id." AND section_id=".$section_id." ORDER BY sort ASC");
		foreach ($res as $row) {
			$content_vo = DB::RowToObj("DaoContent", $row);
			$contents[] = $content_vo;
		}

		return $contents;
	}

	static public function GetSectionContentCount($section_id, $page_id = 0)
	{
		$page_vo = Pages::Get($page_id);
		$res = DB::Query("SELECT id FROM ".DB_PREFIX."contents WHERE page_id=".$page_vo->id." AND section_id=".$section_id." ORDER BY sort ASC");
		return DB::NumRows($res);
	}

	static public function RenderSection($section_id, $page_id = 0)
	{
		$contents = Theme::GetSectionContents($section_id, $page_id);
		foreach ($contents as $content_vo)
			Theme::RenderContent($section_id, $content_vo);
	}

	static public function GetLayout($page_id = 0)
	{
		if ($page_id == 0) {
			$page_vo = Theme::GetPage();
			if ($page_vo !== false)
				$page_id = $page_vo->id;
			else
				return false;
		}

		$page_vo = Pages::Get($page_id);
		if ($page_vo === false)
			return false;

		return Layout::Get($page_vo->layout_id);
	}

	static public function GetStyleId()
	{
		if (Theme::$page_vo == false)
			Theme::$GetPage();

		return Theme::$page_vo->style_id;
	}

	static public function SetBase($base)
	{
		Theme::$theme_base = $base;
	}

	static public function GetBase()
	{
		return Theme::$theme_base;
	}

	static public function SetPath($path)
	{
		Theme::$theme_path = $path;
	}

	static public function GetPath()
	{
		return Theme::$theme_path;
	}

	static public function MapContentWidth($content, $width)
	{
		switch ($content) {
		case CONTENT_WIDTH_100:
			Theme::$content_width_100 = $width;
			break;
		case CONTENT_WIDTH_75:
			Theme::$content_width_75 = $width;
			break;
		case CONTENT_WIDTH_66:
			Theme::$content_width_66 = $width;
			break;
		case CONTENT_WIDTH_50:
			Theme::$content_width_50 = $width;
			break;
		case CONTENT_WIDTH_33:
			Theme::$content_width_33 = $width;
			break;
		case CONTENT_WIDTH_25:
			Theme::$content_width_25 = $width;
			break;
		}
	}

	static function GetSectionWidth($section_id, $page_id = 0)
	{
		// Get Layout for current page
		if ($page_id == 0)
			$layout = Theme::GetLayout();
		else
			$layout = Theme::GetLayout($page_id);

		if ($layout === false)
			return false;

		switch ($section_id) {
		case SECTION_HEADER:
			$width = $layout->header;
			break;
		case SECTION_COLUMN_1:
			$width = $layout->column_1;
			break;
		case SECTION_COLUMN_2:
			$width = $layout->column_2;
			break;
		case SECTION_COLUMN_3:
			$width = $layout->column_3;
			break;
		case SECTION_COLUMN_4:
			$width = $layout->column_4;
			break;
		case SECTION_FOOTER:
			$width = $layout->footer;
			break;
		}
		
		switch ($width) {
		case 100:
			return Theme::$content_width_100;

		case 75:
			return Theme::$content_width_75;

		case 66:
			return Theme::$content_width_66;

		case 50:
			return Theme::$content_width_50;

		case 33:
			return Theme::$content_width_33;

		case 25:
			return Theme::$content_width_25;

		default:
			return 0;
		}
	}

	static function SetSectionHeight($section, $height)
	{
		switch ($section) {
		case SECTION_HEADER:
			Theme::$section_header_height = $height;
			break;

		case SECTION_COLUMN_1:
			Theme::$section_column_1_height = $height;
			break;

		case SECTION_COLUMN_2:
			Theme::$section_column_2_height = $height;
			break;

		case SECTION_COLUMN_3:
			Theme::$section_column_3_height = $height;
			break;

		case SECTION_COLUMN_4:
			Theme::$section_column_4_height = $height;
			break;

		case SECTION_FOOTER:
			Theme::$section_footer_height = $height;
			break;
		}
	}

	static function GetSectionHeight($section)
	{
		switch ($section) {

		case SECTION_HEADER:
			return Theme::$section_header_height;

		case SECTION_COLUMN_1:
			return Theme::$section_column_1_height;

		case SECTION_COLUMN_2:
			return Theme::$section_column_2_height;

		case SECTION_COLUMN_3:
			return Theme::$section_column_3_height;

		case SECTION_COLUMN_4:
			return Theme::$section_column_4_height;

		case SECTION_FOOTER:
			return Theme::$section_footer_height;
		}
	}

	static public function SetImageMargin($width)
	{
		Theme::$image_margin = $width;
	}

	static public function  GetImageMargin()
	{
		return Theme::$image_margin;
	}
}
?>
