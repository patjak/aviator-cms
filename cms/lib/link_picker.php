<?php

class LinkPicker {
	public	$link_vo;

	public function LinkPicker($link_vo)
	{
		$this->link_vo = $link_vo;
	}

	public function Render()
	{
		$link = $this->link_vo;

		echo "<div id=\"link_picker_".$link->id."\" class=\"LinkPicker Button InputBox\" ".
		"onclick=\"LinkPickerShow(".$link->id.");\">";
		$this->RenderInner();
		echo "</div><!--LinkPicker-->\n";
	}

	public function RenderInner()
	{
		$link = $this->link_vo;

		echo "<input type=\"hidden\" id=\"link_picker_".$link->id."_name\" ".
		"name=\"link_picker_".$link->id."_name\" value=\"".htmlentities($link->name, ENT_QUOTES, "UTF-8")."\"/>".

		"<input type=\"hidden\" id=\"link_picker_".$link->id."_in_new_window\" ".
		"name=\"link_picker_".$link->id."_in_new_window\" value=\"".$link->in_new_window."\"/>".

		"<input type=\"hidden\" id=\"link_picker_".$link->id."_is_internal\" ".
		"name=\"link_picker_".$link->id."_is_internal\" value=\"".$link->is_internal."\"/>".

		"<input type=\"hidden\" id=\"link_picker_".$link->id."_internal_page_id\" ".
		"name=\"link_picker_".$link->id."_internal_page_id\" value=\"".$link->internal_page_id."\"/>".

		"<input type=\"hidden\" id=\"link_picker_".$link->id."_external_url\" ".
		"name=\"link_picker_".$link->id."_external_url\" value=\"".$link->external_url."\"/>".

		"<input type=\"hidden\" id=\"link_picker_".$link->id."_enabled\" ".
		"name=\"link_picker_".$link->id."_enabled\" value=\"".$link->enabled."\"/>";

		echo "<table><tr><td rowspan=\"4\" style=\"text-align: center;\">";

		if ($link->enabled == 1)
			echo "<img src=\"pics/icons_64/link_white.png\" alt=\"Enabled\" title=\"Enabled\"/>";
		else
			echo "<img src=\"pics/icons_64/link_white_disabled.png\" alt=\"Disabled\" title=\"Disabled\"/>";

		echo "</td><td>";

		if ($link->in_new_window == 1)
			$in_new_window_str = "Yes";
		else
			$in_new_window_str = "No";
		
		echo "<td>Name:</td><td>".$link->name."</td></tr>".
		"<tr><td></td><td>New window:</td><td>".$in_new_window_str."</td></tr>";

		if ($link->is_internal == 1) {
			echo "<tr><td></td><td>Internal link:</td><td>".Theme::GetPage($link->internal_page_id)->title."</td></tr>";
		} else {
			echo "<tr><td></td><td>External URL:</td><td>".$link->external_url."</td></tr>";
		}

		echo "</table>";
	}
}

?>
