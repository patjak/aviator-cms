<?php

function HtmlLayout($pid, $layout_id, $width, $height, $spacing, $with_links = false, $selected_section = 0)
{
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."layouts WHERE id=".$layout_id);
	$layout = DB::Obj($res, "DaoLayout");

	// Count number of columns
	$columns = 0;
	if ($layout->column_1 != 0)
		$columns++;
	if ($layout->column_2 != 0)
		$columns++;
	if ($layout->column_3 != 0)
		$columns++;
	if ($layout->column_4 != 0)
		$columns++;

	// Count number of rows
	$rows = 0;
	if ($layout->header != 0)
		$rows++;
	if ($columns > 0)
		$rows++;
	if ($layout->footer != 0)
		$rows++;

	$row_height = $height / $rows;

	if ($with_links && $pid > 0) {
		$header_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_HEADER."';\"";

		$column_1_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_COLUMN_1."';\"";

		$column_2_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_COLUMN_2."';\"";

		$column_3_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_COLUMN_3."';\"";

		$column_4_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_COLUMN_4."';\"";

		$footer_link = "style=\"cursor: pointer;\" ".
		"onclick=\"location.href='?page=".PAGE_CONTENTS_EDIT."&pid=".$pid."&section_id=".SECTION_FOOTER."';\"";
	} else {
		$header_link = "";
		$column_1_link = "";
		$column_2_link = "";
		$column_3_link = "";
		$column_4_link = "";
		$footer_link = "";
	}

	if ($with_links) {
		$header_class = "";
		$column_1_class = "";
		$column_2_class = "";
		$column_3_class = "";
		$column_4_class = "";
		$footer_class = "";

		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_HEADER);
		if (DB::NumRows($res) > 0)
			$header_class = "NotEmpty";
		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_COLUMN_1);
		if (DB::NumRows($res) > 0)
			$column_1_class = "NotEmpty";
		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_COLUMN_2);
		if (DB::NumRows($res) > 0)
			$column_2_class = "NotEmpty";
		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_COLUMN_3);
		if (DB::NumRows($res) > 0)
			$column_3_class = "NotEmpty";
		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_COLUMN_4);
		if (DB::NumRows($res) > 0)
			$column_4_class = "NotEmpty";
		$res = DB::Query("SELECT id FROM contents WHERE page_id=".$pid." AND section_id=".SECTION_HEADER);
		if (DB::NumRows($res) > 0)
			$header_class = "NotEmpty";
	} else {
		$header_class = "NotEmpty";
		$column_1_class = "NotEmpty";
		$column_2_class = "NotEmpty";
		$column_3_class = "NotEmpty";
		$column_4_class = "NotEmpty";
		$footer_class = "NotEmpty";
	}

	if ($selected_section == SECTION_HEADER)
		$header_class = "Selected";
	else if ($selected_section == SECTION_COLUMN_1)
		$column_1_class = "Selected";
	else if ($selected_section == SECTION_COLUMN_2)
		$column_2_class = "Selected";
	else if ($selected_section == SECTION_COLUMN_3)
		$column_3_class = "Selected";
	else if ($selected_section == SECTION_COLUMN_4)
		$column_4_class = "Selected";
	else if ($selected_section == SECTION_FOOTER)
		$footer_class = "Selected";

	echo "<table border=\"1\" class=\"Layout\" style=\"width: ".$width."px; height: ".$height."px;\" cellspacing=\"".$spacing."\">";
	if ($layout->header > 0)
		echo "<tr height=\"25%\"><td class=\"".$header_class."\" colspan=\"".$columns."\" ".$header_link."></td></tr>";
	if ($columns > 0)
		echo "<tr>";
	if ($layout->column_1 > 0)
		echo "<td class=\"".$column_1_class."\" width=\"".$layout->column_1."%\" ".$column_1_link."></td>";
	if ($layout->column_2 > 0)
		echo "<td class=\"".$column_2_class."\" width=\"".$layout->column_2."%\" ".$column_2_link."></td>";
	if ($layout->column_3 > 0)
		echo "<td class=\"".$column_3_class."\" width=\"".$layout->column_3."%\" ".$column_3_link."></td>";
	if ($layout->column_4 > 0)
		echo "<td class=\"".$column_4_class."\" width=\"".$layout->column_4."%\" ".$column_4_link."></td>";
	if ($columns > 0)
		echo "</tr>";
	if ($layout->footer > 0)
		echo "<tr height=\"25%;\"><td class=\"".$footer_class."\" colspan=\"".$columns."\" ".$footer_link."></td></tr>";
		
	echo "</table>";
}

?>
