<?php

// Content components are strings, images, numbers, links, etc... that
// usually are used within content or other plugins.

class Component {
	static function MoveUp($obj, $table)
	{
		$id = $obj->id;
		$sort = $obj->sort;

		$res = DB::Query("SELECT id, sort FROM ".$table." WHERE sort < ".$sort." ORDER BY sort DESC");
		if ($row = DB::Row($res)) {
			// Swap the sort values
			DB::Query("UPDATE ".$table." SET sort=".$row[1]." WHERE id=".$id);
			DB::Query("UPDATE ".$table." SET sort=".$sort." WHERE id=".$row[0]);
			
			return true;
		} else {
			return false;
		}
	}

	static function MoveDown($obj, $table)
	{
		$id = $obj->id;
		$sort = $obj->sort;

		$res = DB::Query("SELECT id, sort FROM ".$table." WHERE sort > ".$sort." ORDER BY sort ASC");
		if ($row = DB::Row($res)) {
			// Swap the sort values
			DB::Query("UPDATE ".$table." SET sort=".$row[1]." WHERE id=".$id);
			DB::Query("UPDATE ".$table." SET sort=".$sort." WHERE id=".$row[0]);
			
			return true;
		} else {
			return false;
		}
	}

	static function MoveTop($obj, $table)
	{
		while (Component::MoveUp($obj, $table)) {
			continue;
		}
	}

	static function MoveBottom($obj, $table)
	{
		while (Component::MoveDown($obj, $table)) {
			continue;
		}
	}
}

?>
