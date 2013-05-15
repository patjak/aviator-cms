<?php
define("IMAGE_FORMAT_JPG", 1);
define("IMAGE_FORMAT_PNG", 2);
define("IMAGE_FORMAT_GIF", 3);

define("IMAGE_EFFECT_GRAYSCALE",	1 << 0);
define("IMAGE_EFFECT_BRIGHTNESS", 	1 << 1);
define("IMAGE_EFFECT_CONTRAST",		1 << 2);
define("IMAGE_EFFECT_COLORIZE",		1 << 3);
define("IMAGE_EFFECT_NEGATE",		1 << 4);
define("IMAGE_EFFECT_PIXELATE",		1 << 5);
define("IMAGE_EFFECT_SMOOTH",		1 << 6);
define("IMAGE_EFFECT_GAUSSIAN_BLUR",	1 << 7);
define("IMAGE_EFFECT_SELECTIVE_BLUR",	1 << 8);

class Image {

	private	
		$image_vo,
		$image_ref_vo,

		$original_width,
		$original_height,

		$min_width = 0,
		$min_height = 0,

		$max_width = 0,
		$max_height = 0,

		// Calculated dimensions based on restrictions
		$width,
		$height,

		$crop_horizontal = 50,
		$crop_vertical = 50,
		$needs_cropping = false,

		$effects = 0,
		$brightness,		// Unknown range
		$contrast,		// -100 to +100
		$colorize_red,		// 0 to 255
		$colorize_green,	// 0 to 255
		$colorize_blue,		// 0 to 255
		$smooth,
		$pixelate;		// Size in pixels

	public function GetName()
	{
		if ($this->image_vo != false)
			return $this->image_vo->name;
		else
			return "";
	}

	// Dimensions related methods

	public function GetWidth()
	{
		$this->CalculateDimensions();
		return $this->width;
	}

	public function GetHeight()
	{
		$this->CalculateDimensions();
		return $this->height;
	}

	public function SetWidth($width)
	{
		$this->min_width = $this->max_width = $width;
	}

	public function SetHeight($height)
	{
		$this->min_height = $this->max_height = $height;
	}

	public function SetCropHorizontal($crop)
	{
		$this->crop_horizontal = $crop;
	}

	public function SetCropVertical($crop)
	{
		$this->crop_vertical = $crop;
	}

	public function SetMinWidth($min_width)
	{
		$this->min_width = $min_width;

		if ($this->max_width < $min_width)
			$this->max_width = $min_width;
	}

	public function SetMinHeight($min_height)
	{
		$this->min_height = $min_height;

		if ($this->max_height < $min_height)
			$this->max_height = $min_height;
	}

	public function SetMaxWidth($max_width)
	{
		$this->max_width = $max_width;

		if ($this->min_width > $max_width)
			$this->min_width = $max_width;
	}

	public function SetMaxHeight($max_height)
	{
		$this->max_height = $max_height;

		if ($this->min_height > $max_height)
			$this->min_height = $max_height;
	}

	// Effects related methods

	public function SetGrayscale()
	{
		$this->effects |= IMAGE_EFFECT_GRAYSCALE;
	}

	public function SetBrightness($value)
	{
		$this->effects |= IMAGE_EFFECT_BRIGHTNESS;
		$this->brightness = $value;
	}

	public function SetContrast($value)
	{
		$this->effects |= IMAGE_EFFECT_CONTRAST;
		$this->contrast = $value;
	}

	public function SetNegate()
	{
		$this->effects |= IMAGE_EFFECT_NEGATE;
	}

	public function SetColorize($red, $green, $blue)
	{
		$this->effects |= IMAGE_EFFECT_COLORIZE;
		$this->colorize_red = $red;
		$this->colorize_green = $green;
		$this->colorize_blue = $blue;
	}

	public function SetSmooth($value)
	{
		$this->effects |= IMAGE_EFFECT_SMOOTH;
		$this->smooth = $value;
	}

	public function SetGaussianBlur()
	{
		$this->effects |= IMAGE_EFFECT_GAUSSIAN_BLUR;
	}

	public function SetSelectiveBlur()
	{
		$this->effects |= IMAGE_EFFECT_SELECTIVE_BLUR;
	}

	public function SetPixelate($value)
	{
		$this->effects |= IMAGE_EFFECT_PIXELATE;
		$this->pixelate = $value;
	}

	public function GetExtension()
	{
		switch ($this->image_vo->format) {
		case IMAGE_FORMAT_PNG:
			return "png";
		case IMAGE_FORMAT_JPG:
			return "jpg";
		default:
			return "png";
		}
	}

	public function GetOriginalUrl()
	{
		return MEDIA_BASE."images/".$this->image_vo->id.".".$this->GetExtension();
	}

	public function GetImgTag()
	{
		$url = $this->GetUrl();

		if ($this->image_vo == false)
			return;

		return "<img class=\"image_id_".$this->image_vo->id."\" ".
		"alt=\"".$this->GetName()."\" title=\"".$this->GetName()."\" src=\"".$url."\" ".
		"style=\"width: ".$this->width."px; height: ".$this->height."px;\"/>";
	}

	private function CalculateDimensions()
	{
		// If there are no restrictions or effects we just return the original
		if ($this->min_width == 0 && $this->min_width == 0 &&
		    $this->max_width == 0 && $this->max_height == 0) {
			$this->width = $this->original_width;
			$this->height = $this->original_height;
			return;
		}

		$new_width = $this->original_width;
		$new_height = $this->original_height;

		// Scale up if needed
		if ($new_width < $this->min_width && $this->min_width != 0) {
			$new_height *= $this->min_width / $new_width;
			$new_width = $this->min_width;
		}
		if ($new_height < $this->min_height && $this->min_height != 0) {
			$new_width *= $this->min_height / $new_height;
			$new_height = $this->min_height;
		}

		// Scale down if needed
		if ($new_width > $this->max_width && $this->max_width != 0) {
			$new_height *= $this->max_width / $new_width;
			$new_width = $this->max_width;
		}
		if ($new_height > $this->max_height && $this->max_height != 0) {
			$new_width *= $this->max_height / $new_height;
			$new_height = $this->max_height;
		}

		// Adjust for cropping
		$this->needs_cropping = false;

		if ($new_width < $this->min_width && $this->min_width != 0) {
			$new_width = $this->min_width;
			$this->needs_cropping = true;
		}

		if ($new_height < $this->min_height && $this->min_height != 0) {
			$new_height = $this->min_height;
			$this->needs_cropping = true;
		}

		$this->width = round($new_width);
		$this->height = round($new_height);
	}

	public function GetUrl()
	{
		if ($this->image_vo == false)
			return;

		// If no resizing or effects are needed we return the original
		if ($this->width == $this->original_width && $this->height == $this->original_height && $this->effects == 0)
			return $this->GetOriginalUrl();

		// Load dimensions based on restrictions provided
		$this->CalculateDimensions();

		// Check cache if this image already exists in this dimension, cropping and requested effects
		if ($this->image_ref_vo == false)
			$image_ref_str = " AND image_ref_id IS NULL ";
		else
			$image_ref_str = " AND image_ref_id=".$this->image_ref_vo->id." ";

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_cache WHERE image_id=".$this->image_vo->id.$image_ref_str.
		" AND width=".$this->width." AND height=".$this->height." AND effects=".$this->effects.
		" AND crop_horizontal=".$this->crop_horizontal." AND crop_vertical=".$this->crop_vertical);

		if (DB::NumRows($res) == 0)
			$cache_vo = $this->CreateCache();
		else
			$cache_vo = DB::Obj($res, "DaoImageCache");

		$ext = $this->GetExtension();
		return MEDIA_BASE."images/".$this->image_vo->id."-".$cache_vo->id.
		       "-".$this->crop_horizontal."x".$this->crop_vertical."-".$this->effects.".".$ext;
	}

	public function Image($image_id, $image_ref_id = 0)
	{
		if ($image_id == 0 || $image_id == NULL) {
			$this->image_vo = false;
		} else {
			$res = DB::Query("SELECT * FROM ".DB_PREFIX."images WHERE id=".$image_id);
			if (DB::NumRows($res) != 1) {
				$this->image_vo = false;
			} else {
				$this->image_vo = DB::Obj($res, "DaoImage");
				$this->original_width = $this->image_vo->width;
				$this->original_height = $this->image_vo->height;
			}
		}

		if ($image_ref_id == 0 || $image_ref_id == NULL) {
			$this->image_ref_vo = false;
		} else {
			$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE id=".$image_ref_id);
			$this->image_ref_vo = DB::Obj($res, "DaoImageRef");
			$this->crop_horizontal = $this->image_ref_vo->crop_horizontal;
			$this->crop_vertical = $this->image_ref_vo->crop_vertical;
		}
	}

	public function Delete()
	{
		// Make sure we have a real image
		if ($this->image_vo == false)
			return;

		// Don't delete if other references exists to this image
		$res_refs = DB::Query("SELECT id FROM ".DB_PREFIX."image_refs WHERE image_id=".$this->image_vo->id);
		if (DB::NumRows($res_refs) > 0)
			return;

		$ext = $this->GetExtension();
		$res_cache = DB::Query("SELECT * FROM ".DB_PREFIX."image_cache WHERE image_id=".$this->image_vo->id);

		// Delete any remaining cache (Shouldn't be any, but better play it safe)
		while ($cache_vo = DB::Obj($res_cache)) {
			unlink(MEDIA_PATH."images/".$this->image_vo->id."-".$cache_vo->id."-".
			$cache_vo->crop_horizontal."x".$cache_vo->crop_vertical."-".$cache_vo->effects.".".$ext);

			DB::Query("DELETE FROM ".DB_PREFIX."image_cache WHERE id=".$cache_vo->id);
		}

		// Delete original
		unlink(MEDIA_PATH."images/".$this->image_vo->id.".".$ext);

		DB::Query("DELETE FROM ".DB_PREFIX."images WHERE id=".$this->image_vo->id);
		$this->image_vo = false;
	}

	// Private methods

	private function ApplyEffects($image)
	{
		// The order matters! Maybe add functionality to specify it?
		// That would add complexity to the cache filename, but might still be possible.

		if ($this->effects & IMAGE_EFFECT_PIXELATE)
			imagefilter($image, IMG_FILTER_PIXELATE, $this->pixelate);

		if ($this->effects & IMAGE_EFFECT_GAUSSIAN_BLUR)
			imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);

		if ($this->effects & IMAGE_EFFECT_SELECTIVE_BLUR)
			imagefilter($image, IMG_FILTER_SELECTIVE_BLUR);

		if ($this->effects & IMAGE_EFFECT_SMOOTH)
			imagefilter($image, IMG_FILTER_SMOOTH, $this->smooth);

		if ($this->effects & IMAGE_EFFECT_NEGATE)
			imagefilter($image, IMG_FILTER_NEGATE);

		if ($this->effects & IMAGE_EFFECT_CONTRAST)
			imagefilter($image, IMG_FILTER_CONTRAST, $this->contrast);

		if ($this->effects & IMAGE_EFFECT_BRIGHTNESS)
			imagefilter($image, IMG_FILTER_BRIGHTNESS, $this->brightness);

		if ($this->effects & IMAGE_EFFECT_GRAYSCALE)
			imagefilter($image, IMG_FILTER_GRAYSCALE);

		if ($this->effects & IMAGE_EFFECT_COLORIZE)
			imagefilter($image, IMG_FILTER_COLORIZE, $this->colorize_red, $this->colorize_green, $this->colorize_blue);
	}

	private function CreateCache()
	{
		$width = $this->width;
		$height = $this->height;

		if ($width == 0 || $height == 0)
			return;

		// Load original
		$ext = $this->GetExtension();
		$filename = MEDIA_BASE."images/".$this->image_vo->id;

		switch ($this->image_vo->format) {
		case IMAGE_FORMAT_JPG:
			$image = imagecreatefromjpeg($filename.".".$ext);
			break;
		case IMAGE_FORMAT_PNG:
			$image = imagecreatefrompng($filename.".".$ext);
			break;
		}

		// FIXME: We should scale it down as much as possible first
		// That will minimize the memory footprint and reduce risk of
		// memory exhaustion

		if ($this->needs_cropping)
			$image = $this->Crop($image, $width / $height);

		$image = $this->Scale($image, $width, $height);

		$cache_vo = new DaoImageCache();
		$cache_vo->image_id = $this->image_vo->id;

		if ($this->image_ref_vo == false)
			$cache_vo->image_ref_id = NULL;
		else
			$cache_vo->image_ref_id = $this->image_ref_vo->id;

		$cache_vo->width = $width;
		$cache_vo->height = $height;
		$cache_vo->effects = $this->effects;
		$cache_vo->crop_horizontal = $this->crop_horizontal;
		$cache_vo->crop_vertical = $this->crop_vertical;
		DB::Insert(DB_PREFIX."image_cache", $cache_vo);

		// Handle effects
		$this->ApplyEffects($image);

		$filename = MEDIA_PATH."images/".$this->image_vo->id."-".$cache_vo->id.
			    "-".$this->crop_horizontal."x".$this->crop_vertical."-".$cache_vo->effects;
		$ext = $this->GetExtension();

		switch ($this->image_vo->format) {
		case IMAGE_FORMAT_JPG:
			imagejpeg($image, $filename.".".$ext);
			break;
		case IMAGE_FORMAT_PNG:
			imagepng($image, $filename.".".$ext);
			break;
		}

		return $cache_vo;
	}

	private function Scale($image, $width, $height)
	{
		$image_new = imagecreatetruecolor($width, $height);
		imagealphablending($image_new, false);
		imagesavealpha($image_new, true);
		imagecopyresampled($image_new, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
		imagedestroy($image);

		return $image_new;
	}

	private function Crop($image, $ratio)
	{
		$old_ratio = $this->original_width / $this->original_height;

		$old_width = $new_width = imagesx($image);
		$old_height = $new_height = imagesy($image);

		if ($ratio > $old_ratio) {
			// Crop vertically
			$new_height = $new_width / $ratio;
			$offset_x = 0;

			$offset_y = (($old_height / 100) * $this->crop_vertical) - ($new_height / 2);
			if ($offset_y < 0)
				$offset_y = 0;
			if ($offset_y > ($old_height - $new_height))
				$offset_y = $old_height - $new_height;
			
		} else {
			// Crop horizontally
			$new_width = $new_height * $ratio;
			$offset_y = 0;

			$offset_x = (($old_width / 100) * $this->crop_horizontal) - ($new_width / 2);
			if ($offset_x < 0)
				$offset_x = 0;
			if ($offset_x > ($old_width - $new_width))
				$offset_x = $old_width - $new_width;
		}

		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		imagecopy($new_image, $image, 0, 0, $offset_x, $offset_y, $new_width, $new_height);
		imagedestroy($image);
		return $new_image;
	}
}

?>
