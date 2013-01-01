<?php

class ImageUploader {
	public	$image_ref_vo,
		$thumb_max_width = 0,
		$thumb_max_height = 0,
		$thumb_min_width = 0,
		$thumb_min_height = 0,
		$show_link = true;

	public function ImageUploader($image_ref_vo)
	{
		$this->image_ref_vo = $image_ref_vo;
	}

	public function ShowLink($val)
	{
		if ($val == false)
			$this->show_link = 0;
		else
			$this->show_link = 1;
	}

	public function SetThumbMaxWidth($width)
	{
		$this->thumb_max_width = $width;
	}

	public function SetThumbMaxHeight($height)
	{
		$this->thumb_max_height = $height;
	}

	public function SetThumbMinWidth($width)
	{
		$this->thumb_min_width = $width;
	}

	public function SetThumbMinHeight($height)
	{
		$this->thumb_min_height = $height;
	}

	public function Render()
	{
		$image = new Image($this->image_ref_vo->image_id/*, $this->image_ref_vo->id*/);
		$image->SetMaxWidth($this->thumb_max_width);
		$image->SetMaxHeight($this->thumb_max_height);
		$image->SetMinWidth($this->thumb_min_width);
		$image->SetMinHeight($this->thumb_min_height);

		if ($this->image_ref_vo->image_id == 0) {
			$no_image_str = "<img class=\"ImageRefNoImage_".$this->image_ref_vo->id."\" ".
			"src=\"pics/icons_64/image_white.png\" style=\"margin: 20px;\"/>";
		} else {
			$no_image_str = "";
		}

		if ($this->thumb_min_height == $this->thumb_max_height)
			$height_str = " height: ".$this->thumb_max_height.";";
		else
			$height_str = "";

		echo "<div class=\"ImageUpload Button\" ".
		"style=\"text-align: center; width: ".($this->thumb_max_width)."px; min-height: ".$this->thumb_min_height.";".$height_str."\"".
		"onclick=\"ShowImageUploader(".$this->image_ref_vo->id.", ".$this->thumb_max_width.", ".$this->thumb_max_height.", ".$this->thumb_min_width.", ".$this->thumb_min_height.", ".$this->show_link.")\">".
		$no_image_str.
		"<span class=\"ImageRef_".$this->image_ref_vo->id."\">".$image->GetImgTag()."</span>".
		"<input type=\"hidden\" name=\"image_id_".$this->image_ref_vo->id."\" id=\"input_image_ref_id_".$this->image_ref_vo->id."\" value=\"".$this->image_ref_vo->image_id."\"/>".
		"<input type=\"hidden\" name=\"crop_horizontal_".$this->image_ref_vo->id."\" id=\"input_crop_x_".$this->image_ref_vo->id."\" value=\"".$this->image_ref_vo->crop_horizontal."\"/> ".
		"<input type=\"hidden\" name=\"crop_vertical_".$this->image_ref_vo->id."\" id=\"input_crop_y_".$this->image_ref_vo->id."\" value=\"".$this->image_ref_vo->crop_vertical."\"/> ".
		"</div>";
	}

	static public function HandleUpload($file, $image_name, $image_description, $image_category_id, $image_category_name, &$error_msg)
	{
		if (!isset($file['tmp_name'])) {
			$error_msg = "No file was uploaded";
			return 0;
		}

		if ($file['error'] !== UPLOAD_ERR_OK) {
			switch($file['error']) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$error_msg = "File size exceeds web server limits";
				break;
			case UPLOAD_ERR_PARTIAL:
				$error_msg = "File was only partially uploaded";
				break;
			case UPLOAD_ERR_NO_FILE:
				$error_msg = "No file was uploaded";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$error_msg = "No temporary directory found. Check server configuration";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$error_msg = "Cannot write to disk. Check server configuration and available disk space";
				break;
			case UPLOAD_ERR_EXTENSION:
				$error_msg = "A PHP extension stopped the file upload";
				break;
			}
			return 0;
		}

		$info = getimagesize($file['tmp_name']);
		if ($info === false) {
			$error_msg = "Couldn't get image info";
			return 0;
		}

		$type = $info[2];

		$image_vo = new DaoImage();
		$image_vo->width = $info[0];
		$image_vo->height = $info[1];
		$image_vo->name = $image_name;
		$image_vo->description = $image_description;

		// Are we adding a new category
		if ($image_category_id == -1) {
			$image_category_name = mysql_real_escape_string($image_category_name);

			// Check if category already exists
			$res_cat = DB::Query("SELECT id FROM ".DB_PREFIX."image_categories WHERE LOWER(name)='".$image_category_name."'");
			if ($row_cat = DB::Row($res_cat)) {
				$image_vo->category_id = $row_cat[0];
			} else {
				// Create it
				DB::Query("INSERT INTO ".DB_PREFIX."image_categories (name) VALUES('".$image_category_name."')");
				$image_vo->category_id = DB::InsertID();
			}
		} else {
			$image_vo->category_id = $image_category_id;
		}

		if ($image_vo->category_id == 0)
			$image_vo->category_id = NULL;

		if ((($image_vo->width * $image_vo->height * 4) / (1024*1024)) > (Settings::Get("php_memory_limit") / 2)) {
			$error_msg = "<p>Image dimensions are too big (".
			round((($image_vo->width * $image_vo->height) / 1000000)).
			" mega pixels).</p><p>Server needs more memory (see PHP memory_limit)</p>";
			return 0;
		}

		DB::Insert(DB_PREFIX."images", $image_vo);

		$filename = MEDIA_PATH."images/".$image_vo->id.".";

		switch ($type) {
		case IMAGETYPE_PNG:
			$image_vo->format = IMAGE_FORMAT_PNG;
			$extension = "png";
			$ret = move_uploaded_file($file['tmp_name'], $filename.$extension);
			break;
		case IMAGETYPE_JPEG:
			$image_vo->format = IMAGE_FORMAT_JPG;
			$extension = "jpg";
			$ret = move_uploaded_file($file['tmp_name'], $filename.$extension);
			break;
		case IMAGETYPE_GIF: // GIFs are converted to PNG
			$image_vo->format = IMAGE_FORMAT_PNG;
			$extension = "png";
			$ret = imagecreatefromgif($file['tmp_name']);
			if ($ret === false)
				break;

			$ret = imagepng($ret, $filename.$extension, 9, PNG_ALL_FILTERS); // We go for optimal compression
			break;
		default:
			$ret = false;
		}

		if ($ret !== false) {
			DB::Update(DB_PREFIX."images", $image_vo);
			return $image_vo->id;
		} else {
			DB::Query("DELETE FROM ".DB_PREFIX."images WHERE id=".$image_vo->id);
			$error_msg = "Failed to move or save image";
			return 0;
		}
	}
}

?>
