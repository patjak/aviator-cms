<?php

require_once("secure.php");

$con = 0;

class DB {
	function DB()
	{
		global $con;
		$con = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if (!$con) {
			echo "Couldn't connect to database<br/>";
			return;
		}
		mysql_select_db(DB_NAME, $con);
	}

	function __DB()
	{
		global $con;
		mysql_close($con);
	}

	static function Query($str)
	{
		global $con;
		$result = mysql_query($str, $con);
		if (!$result) {
			echo "<p>SQL Query failed: ".mysql_error()."</p>".
			"<p>Query: {$str}</p>";
			debug_print_backtrace();

			// If we're in ajax mode, we mark status as error
			Ajax::SetStatus(AJAX_STATUS_ERROR);
		}
		return $result;
	}

	static function Row($result)
	{
		return mysql_fetch_row($result);
	}

	// Get row as a php object
	static function Obj($result, $class = NULL)
	{
		if ($class == NULL)
			$obj = mysql_fetch_object($result);
		else
			$obj = mysql_fetch_object($result, $class);

		if (!$obj)
			return $obj;

		// Strip slashes from strings
		if (is_array($obj)) {
			foreach ($obj as $var => $val) {
				if (is_string($var))
					$obj[$var] = stripslashes($val);
			}
		} else {
			$vars = get_object_vars($obj);
			foreach ($vars as $var => $val) {
				if (is_string($val))
					$obj->$var = stripslashes($val);
			}
		}

		return $obj;
	}

	// Insert object into table
	// This function assumes there is a primary key called id
	static function Insert($table, &$obj)
	{
		global $con;
		$vars = get_object_vars($obj);
		$keys = array_keys($vars);
		$columns = "";
		$values = "";

		$count = 0;
		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $vars[$key];

			// Don't insert primary key
			if ($key == "id")
				continue;

			if ($count > 0) {
				$columns .= ", ";
				$values .= ", ";
			}

			$columns .= $key;

			if (is_string($value) && ($value != 'null' || $value != 'NULL')) {
				$value = mysql_real_escape_string($value, $con);
				$values .= "'".$value."'";
			} else {
				if ($value === NULL)
					$value = 'null';
				$values .= $value;
			}
			$count++;
		}
		$query = "INSERT INTO ".$table. " (".$columns.") VALUES (".$values.")";

		DB::Query($query);
		$obj->id = DB::InsertID();
	}

	// Update object in table
	static function Update($table, $obj)
	{
		global $con;
		$vars = get_object_vars($obj);
		$keys = array_keys($vars);
		$updates = "";

		$count = 0;
		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $vars[$key];

			$key = strtolower($key);

			// Don't insert primary key
			if ($key == "id")
				continue;

			if ($count > 0) {
				$updates .= ", ";
			}

			$updates .= $key."=";

			if (is_string($value) && $value != 'null' && $value != 'NULL') {
				$value = mysql_real_escape_string($value, $con);
				$updates .= "'".$value."'";
			} else {
				if ($value === NULL)
					$value = 'null';
				$updates .= $value;
			}
			$count++;
		}
		$query = "UPDATE ".$table. " SET ".$updates." WHERE id=".$obj->id;

		DB::Query($query);
	}

	static function NumRows($result)
	{
		$number_rows = mysql_num_rows($result);

		if ($number_rows === false) {
			$str_bt = GetBackTraceStr();
			echo "<p>SQL num rows failed</p>". $str_bt;
		}

		return $number_rows;
	}

	static function AffectedRows()
	{
		return mysql_affected_rows();
	}

	static function InsertID()
	{
		global $con;
		return mysql_insert_id($con);
	}

	// Returns true if row exists, otherwise false
	static function RowExists($id, $table)
	{
		$res = DB::Query("SELECT id FROM {$table} WHERE id={$id}");
		if (DB::NumRows($res) == 1)
			return true;
		else
			return false;
	}

	// Consistency functions
	static function Begin()
	{
		DB::Query("BEGIN");
	}

	static function Rollback()
	{
		DB::Query("ROLLBACK");
	}

	static function Commit()
	{
		DB::Query("COMMIT");
	}
}

// Connect to database
$db = new DB();
?>
