<?php

require_once(SITE_PATH."secure.php");

class DB {
	private static $con = 0;

	function DB($host, $user, $pass)
	{
		self::$con = mysql_connect($host, $user, $pass);
		if (!self::$con) {
			echo "Couldn't connect to database<br/>";
			exit();
		}
		mysql_select_db(DB_NAME, self::$con);
	}

	function __destruct()
	{
		mysql_close(self::$con);
	}

	static function Query($str)
	{
		$result = mysql_query($str, self::$con);
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

	static function ObjById($class, $table, $id)
	{
		$res = DB::Query("SELECT * FROM ".$table." WHERE id=".$id);
		if (DB::NumRows($res) > 0)
			return DB::Obj($res, $class);
		else
			return false;
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
				$value = mysql_real_escape_string($value, self::$con);
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
				$value = mysql_real_escape_string($value, self::$con);
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
		return mysql_insert_id(self::$con);
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
$db = new DB(DB_HOST, DB_USER, DB_PASS);
?>
