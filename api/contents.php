<?php

// Width types are described in percent but doesn't necessarily need to
// correspond to that in the theme. See it as a style guideline.
$i = 0;
define("CONTENT_WIDTH_100", 1 << $i++);
define("CONTENT_WIDTH_75", 1 << $i++);
define("CONTENT_WIDTH_66", 1 << $i++);
define("CONTENT_WIDTH_50", 1 << $i++);
define("CONTENT_WIDTH_33", 1 << $i++);
define("CONTENT_WIDTH_25", 1 << $i++);
define("CONTENT_WIDTH_ALL", CONTENT_WIDTH_100 | CONTENT_WIDTH_75 | CONTENT_WIDTH_66 |
			    CONTENT_WIDTH_50 | CONTENT_WIDTH_33 | CONTENT_WIDTH_25);

class ContentAPI extends ContentCore {
	/* Called when content is added to a page. */
	public function Create()
	{
	}

	/* Generate the HTML shown when editing the content here. */
	public function Edit()
	{
	}

	/* Called when user hits the "Save" button. Update database here. */
	public function Save()
	{
	}

	/* Called when user deletes a "Content". Clean up database here. */
	public function Delete()
	{
	}

	/* This function is called when the whole page has been requested
	   for deletion. Take care of database consistencies here.
	   E.g. if other templates of this type needs updating because this
	   page is going to be removed, you can fix those here. */
	public function PageDelete()
	{
	}

	/* Called by the theme to render the contents onto screen */
	public function Render($section_id, $width, $height)
	{
		/* FIXME: We need pre and post rendering hooks */
	}
}

define("CONTENT_BACKEND", 1 << 0);
define("CONTENT_FRONTEND", 1 << 1);

class ContentCore {
	private static	$entries = array(); // List of all created content objects

	protected 	$internal_id;	// A plugin internally unique number for
					// plugins with more than 1 content type

	private		$title,
			$widths = CONTENT_WIDTH_ALL,
			$sections = SECTION_ALL,
			$icon_32_filename;

	private static 	$css_backend = array(),
			$css_frontend = array(),
			$js_backend = array(),
			$js_frontend = array();

	public		$id,		// The ID of the DAO in Database
			$plugin;

	public function ContentCore(&$plugin, $internal_id = 1)
	{
		$this->internal_id = $internal_id;
		$this->plugin = $plugin;
		ContentCore::$entries[] = &$this;
	}

	public function SetTitle($title)
	{
		$this->title = $title;
	}

	public function GetTitle()
	{
		return $this->title;
	}

	public function SetIcon32($filename)
	{
		$this->icon_32_filename = $filename;
	}

	public function GetIcon32()
	{
		return "../plugins/".$this->plugin->GetDirectory()."/".$this->icon_32_filename;
	}

	static function AddCss($filename, $flags = CONTENT_BACKEND)
	{
		if ($flags & CONTENT_BACKEND)
			ContentCore::$css_backend[] = $filename;

		if ($flags & CONTENT_FRONTEND)
			ContentCore::$css_frontend[] = $filename;
	}

	static function AddJs($filename, $flags = CONTENT_BACKEND)
	{
		if ($flags & CONTENT_BACKEND)
			ContentCore::$js_backend[] = $filename;

		if ($flags & CONTENT_FRONTEND)
			ContentCore::$js_frontend[] = $filename;
	}

	static function GetCssBackendList()
	{
		return ContentCore::$css_backend;
	}

	static function GetCssFrontendList()
	{
		return ContentCore::$css_frontend;
	}

	static public function GetJsBackendList()
	{
		return ContentCore::$js_backend;
	}

	static public function GetJsFrontendList()
	{
		return ContentCore::$js_frontend;
	}

	public function CreateString($internal_id, $string = "")
	{
		DB::Query("INSERT INTO ".DB_PREFIX."strings (content_id, internal_id, string) VALUES(:content_id, :internal_id, :string)",
			  array("content_id" => $this->id, "internal_id" => $internal_id, "string" => $string));

		$insert_id = DB::InsertId();
		DB::Query("UPDATE ".DB_PREFIX."strings SET sort=id WHERE id=".$insert_id);

		return $insert_id;
	}

	public function GetString($internal_id)
	{
		$res = DB::Query("SELECT string FROM ".DB_PREFIX."strings WHERE content_id=:content_id AND internal_id=:internal_id",
				 array("content_id" => $this->id, "internal_id" => $internal_id));
		
		if (count($res) > 0) {
			return stripslashes($res[0]['string']);
		} else {
			return false;
		}
	}

	public function GetOrCreateString($internal_id)
	{
		$string = $this->GetString($internal_id);

		if ($string === FALSE) {
			$this->CreateString($internal_id);
			$string = $this->GetString($internal_id);
		}
		return $string;
	}

	public function UpdateString($internal_id, $string)
	{
		DB::Query("UPDATE ".DB_PREFIX."strings SET string=:string WHERE content_id=:content_id AND internal_id=:internal_id",
			  array("string" => $string, "content_id" => $this->id, "internal_id" => $internal_id));
	} 

	public function DeleteString($internal_id)
	{
		DB::Query("DELETE FROM ".DB_PREFIX."strings WHERE content_id=".$this->id." AND internal_id=".$internal_id);
	}

	public function CreateInt($internal_id, $number = NULL)
	{
		DB::Query("INSERT INTO ".DB_PREFIX."integers (content_id, internal_id, number) ".
		"VALUES(".$this->id.", ".$internal_id.", ".$number.")");

		$insert_id = DB::InsertId();
		DB::Query("UPDATE ".DB_PREFIX."integers SET sort=id WHERE id=".$insert_id);
	}

	public function GetInt($internal_id)
	{
		$res = DB::Query("SELECT number FROM ".DB_PREFIX."integers WHERE content_id=:content_id AND internal_id=:internal_id",
				 array("content_id" => $this->id, "internal_id" => $internal_id));

		if (count($res) > 0) {
			return (int)$res[0]['number'];
		} else {
			return false;
		}
	}

	public function GetOrCreateInt($internal_id)
	{
		$number = $this->GetInt($internal_id);

		if ($number === FALSE) {
			$this->CreateInt($internal_id);
			$number = $this->GetInt($internal_id);
		}
		return $number;
	}

	public function UpdateInt($internal_id, $number)
	{
		$number = (int)$number;
		DB::Query("UPDATE ".DB_PREFIX."integers SET number=:number WHERE content_id=:content_id AND internal_id=:internal_id",
			  array("number" => $number, "content_id" => $this->id, "internal_id" => $internal_id));
	}

	public function DeleteInt($internal_id)
	{
		DB::Query("DELETE FROM ".DB_PREFIX."integers WHERE content_id=:content_id AND internal_id=:internal_id",
			  array("content_id" => $this->id, "internal_id" => $internal_id));
	}

	public function CreateBlob($internal_id, $data = NULL)
	{
		$blob = new DaoBlob();
		$blob->content_id = $this->id;
		$blob->internal_id = $internal_id;
		$blob->data = $data;
		DB::Insert($blob);
		$blob->sort = $blob->id;
		DB::Update($blob);

		return $blob->id;
	}

	public function GetBlob($internal_id)
	{
		$res = DB::Query("SELECT data FROM ".DB_PREFIX."blobs WHERE content_id=:content_id AND internal_id=:internal_id",
				 array("content_id" => $this->id, "internal_id" => $internal_id));
		if (count($res) > 0)
			return $res[0]['data'];
		else
			return false;
	}

	public function GetOrCreateBlob($internal_id)
	{
		$blob = $this->GetBlob($internal_id);

		if ($blob === FALSE) {
			$this->CreateBlob($internal_id);
			$blob = $this->GetBlob($internal_id);
		}
		return $blob;
	}

	public function UpdateBlob($internal_id, $data)
	{
		DB::Query("UPDATE ".DB_PREFIX."blobs SET data=:data WHERE content_id=:content_id AND internal_id=:internal_id",
			  array("data" => $data, "content_id" => $this->id, "internal_id" => $internal_id));
	}

	public function DeleteBlob($internal_id)
	{
		DB::Query("DELETE FROM ".DB_PREFIX."blobs WHERE content_id=:content_id AND internal_id=:internal_id",
			  array("content_id" => $this->id, "internal_id" => $internal_id));
	}

	public function GetAllBlobs()
	{
		$blobs = array();

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."blobs WHERE content_id=:content_id ORDER BY sort ASC",
				 array("content_id" => $this->id));

		foreach ($res as $row) {
			$vo = DB::RowToObj("DaoBlob", $row);
			$blobs[] = $vo;
		}

		return $blobs;
	}

	public function CreateLink($internal_id, $link = NULL)
	{
		if ($link == NULL) {
			DB::Query("INSERT INTO ".DB_PREFIX."links (content_id, internal_id) ".
			"values(".$this->id.", ".$internal_id.")");
			$id = DB::InsertID();

			$link = $this->GetLink($internal_id);
			$link->sort = $link->id;
			DB::Update($link);
		} else {
			$link->content_id = $this->id;
			$link->internal_id = $internal_id;
			DB::Insert($link);

			$link->sort = $link->id;
			DB::Update($link);
		}

		return $link->id;
	}

	public function GetLink($internal_id)
	{
		$res = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE content_id=".$this->id." AND internal_id=".$internal_id);
		if (count($res) == 1)
			return DB::RowToObj("DaoLink", $res[0]);
		else
			return false;
	}

	public function GetOrCreateLink($internal_id)
	{
		$link = $this->GetLink($internal_id);

		if ($link === FALSE) {
			$this->CreateLink($internal_id);
			$link = $this->GetLink($internal_id);
		}
		return $link;
	}

	public function GetAllLinks()
	{
		$links = array();

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE content_id=".$this->id." ORDER BY sort ASC");
		foreach ($res as $row) {
			$vo = DB::RowToObj("DaoLink", $row);
			$links[] = $vo;
		}

		return $links;
	}

	public function UpdateLinkPicker($internal_id, $link)
	{
		$id = $link->id;
		$link->name = $_POST['link_picker_'.$id.'_name'];
		$link->in_new_window = (int)$_POST['link_picker_'.$id.'_in_new_window'];
		$link->is_internal = (int)$_POST['link_picker_'.$id.'_is_internal'];
		$link->internal_page_id = (int)$_POST['link_picker_'.$id.'_internal_page_id'];
		$link->external_url = $_POST['link_picker_'.$id.'_external_url'];
		$link->enabled = (int)$_POST['link_picker_'.$id.'_enabled'];

		if ($link->internal_page_id == 0)
			$link->internal_page_id = NULL;

		DB::Update($link);
	}

	public function DeleteLink($internal_id)
	{
		DB::Query("DELETE FROM ".DB_PREFIX."links WHERE content_id=".$this->id." AND internal_id=".$internal_id);
	}

	public function GetLinkUrl($link)
	{
		if ($link->is_internal == 1) {
			$url = Theme::GetPageUrl($link->internal_page_id);
		} else {
			$url = $link->external_url;

			if (strncmp($url, "http://", 7) != 0 && strncmp($url, "https://", 8) != 0)
				$url = "http://".$url;
		}
		return $url;
	}

	public function GetLinkOpenTag($link)
	{
		if ($link->enabled != 1)
			return "";

		if ($link->in_new_window == 1)
			$in_new_window_str = " target=\"_blank\"";
		else
			$in_new_window_str = "";

		$url = self::GetLinkUrl($link);

		return "<a href=\"".$url."\"".$in_new_window_str.">";
	}

	public function GetLinkCloseTag($link)
	{
		if ($link->enabled != 1)
			return "";
		else
			return "</a>";
	}

	public function GetLinkFullTag($link)
	{
		return self::GetLinkOpenTag($link).($link->name).self::GetLinkCloseTag($link);
	}

	public function CreateImageRef($internal_id)
	{
		DB::Query("INSERT INTO ".DB_PREFIX."links (name, enabled, sort) VALUES('', 0, 0)");
		$link_id = DB::InsertId();

		DB::Query("INSERT INTO ".DB_PREFIX."image_refs (link_id, content_id, internal_id) ".
		"VALUES(".$link_id.", ".$this->id.", ".$internal_id.")");

		// Set sort to id as default
		$last_id = DB::InsertID();
		DB::Query("UPDATE ".DB_PREFIX."image_refs SET sort=id WHERE id=".$last_id);

		return $this->GetImageRef($internal_id);
	}

	public function GetImageRef($internal_id)
	{
		$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE ".
		"content_id=".$this->id." AND internal_id=".$internal_id);
		
		if (count($res) > 0) {
			return DB::RowToObj("DaoImageRef", $res[0]);
		} else {
			return false;
		}
	}

	public function GetOrCreateImageRef($internal_id)
	{
		$ref = $this->GetImageRef($internal_id);

		if ($ref === FALSE) {
			$this->CreateImageRef($internal_id);
			$ref = $this->GetImageRef($internal_id);
		}
		return $ref;
	}

	// Returns an array with all the image refs assigned to this content
	public function GetAllImageRefs()
	{
		$refs = array();

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE ".
		"content_id=".$this->id." ORDER BY sort");
		
		foreach ($res as $row) {
			$ref_vo = DB::RowToObj("DaoImageRef", $row);
			$refs[] = $ref_vo;
		}

		return $refs;
	}

	protected function UpdateImageRef($internal_id)
	{
		$image_ref_vo = $this->GetImageRef($internal_id);

		// Delete all cached versions connected to this image_ref
		$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_cache WHERE image_ref_id=".$image_ref_vo->id);
		foreach ($res as $row) {
			$cache_vo = DB::RowToObj("DaoImageCache", $row);
			$image = new Image($image_ref_vo->image_id);
			$ext = $image->GetExtension();
			unlink(MEDIA_PATH."images/".$image_ref_vo->image_id."-".$cache_vo->id."-".
			$cache_vo->crop_horizontal."x".$cache_vo->crop_vertical."-".$cache_vo->effects.".".$ext);
			DB::Delete($cache_vo);
		}

		if (isset($_POST['image_id_'.$image_ref_vo->id]))
			$image_id = (int)$_POST['image_id_'.$image_ref_vo->id];
		else
			$image_id = 0;

		if (isset($_POST['crop_horizontal_'.$image_ref_vo->id]))
			$crop_x = (int)$_POST['crop_horizontal_'.$image_ref_vo->id];
		else
			$crop_x = 50;

		if (isset($_POST['crop_vertical_'.$image_ref_vo->id]))
			$crop_y = (int)$_POST['crop_vertical_'.$image_ref_vo->id];
		else
			$crop_y = 50;

		if ($image_id == 0)
			$image_id = NULL;

		$old_image_id = $image_ref_vo->image_id;

		$image_ref_vo->image_id = $image_id;
		$image_ref_vo->crop_horizontal = $crop_x;
		$image_ref_vo->crop_vertical = $crop_y;
		DB::Update($image_ref_vo);

		// Try to delete old image if it's not in use by other image_refs
		$image_old = new Image($old_image_id);
		$image_old->Delete();
	} 

	public function DeleteImageRef($internal_id)
	{
		$image_ref_vo = $this->GetImageRef($internal_id);

		$image_id = $image_ref_vo->image_id;
		$image = new Image($image_id);

		// Remove our reference to the image
		$image_ref_vo->image_id = null;
		DB::Update($image_ref_vo);

		// Delete our part of the cache
		if ($image_id > 0) {
			$res_cache = DB::Query("SELECT * FROM ".DB_PREFIX."image_cache WHERE image_id=".$image_id." ".
			"AND image_ref_id=".$image_ref_vo->id);

			foreach ($res_cache as $row_cache) {
				$cache_vo = DB::RowToObj("DaoImageCache", $row_cache);
				$ext = $image->GetExtension();
				unlink(MEDIA_PATH."images/".$image_id."-".$cache_vo->id."-".
				$cache_vo->crop_horizontal."x".$cache_vo->crop_vertical."-".$cache_vo->effects.".".$ext);

				DB::Query("DELETE FROM ".DB_PREFIX."image_cache WHERE id=".$cache_vo->id);
			}
		}

		// Delete the image itself (if needed)
		$image->Delete();

		$link_id = $image_ref_vo->link_id;
		DB::Query("DELETE FROM ".DB_PREFIX."image_refs WHERE id=".$image_ref_vo->id);
		DB::Query("DELETE FROM ".DB_PREFIX."links WHERE id=".$image_ref_vo->link_id);
	}

	public function SetAllowedWidths($widths)
	{
		$this->widths = $widths;
	}

	public function SetAllowedSections($sections)
	{
		$this->sections = $sections;
	}

	public function GetAllowedSections()
	{
		return $this->sections;
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

	// Returns an array with contents registered for the provided section content width
	static public function GetRegistered($content_width)
	{
		$list = Array();

		foreach (ContentCore::$entries as $entry) {
			if ($content_width & $entry->widths)
				$list[] = $entry;
		}

		// Sort the entries by name
		usort($list, "ContentCore::CompareEntryTitles");

		return $list;
	}

	static public function GetByPluginAndInternal($plugin_id, $internal_id)
	{
		foreach (ContentCore::$entries as $entry) {
			if ($plugin_id == $entry->plugin->GetId() && $internal_id == $entry->GetId())
				return $entry;
		}
		return false;
	}

	public static function GetEntries()
	{
		return ContentCore::$entries;
	}
}

?>
