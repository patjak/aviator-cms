<?php

/**
 * Database abstraction layer on top of PDO
 */  
class DB {
	static	$handle,
		$last_affected_rows = 0,
		$perf_queries = 0,
		$perf_inserts = 0,
		$perf_deletes = 0,
		$perf_updates = 0;

	/**
	 * Connect to SQL server with specified credentials
	 */
	static function Connect($host, $user, $pass, $dbname)
	{
		try {
			self::$handle = new PDO("mysql:host=".$host.";dbname=".$dbname."", $user, $pass);
			self::$handle->exec("SET CHARACTER SET utf8");
		} catch (PDOException $e) {
			echo "<p>PDO error: ".$e->getMessage()."<p/>";
			die();
		}
	}

	/**
	 * Disconnect from SQL server
	 */
	static function Disconnect()
	{
		self::$handle = NULL;
	}

	/**
	 * Get internal PDO handle
	 * FIXME: Make private?
	 */
	static function GetHandle()
	{
		return self::$handle;
	}

	/**
	 * Begin SQL transaction
	 */
	static function Begin()
	{
		self::$handle->beginTransaction();
	}

	/**
	 * Commit SQL transaction
	 */
	static function Commit()
	{
		self::$handle->commit();
	}

	/**
	 * Rollback SQL transaction
	 */
	static function Rollback()
	{
		self::$handle->rollback();
	}

	/**
	 * Returns the ID of last inserted row
	 */
	static function InsertID()
	{
		return self::$handle->lastInsertId();
	}

	static function AffectedRows()
	{
		return self::$last_affected_rows;
	}

	/**
	 * Sanity check DAO to see whether it fits the abstracion layer requirements
	 */
	static function CheckDao($dao)
	{
		if (!isset($dao->table_name) || $dao->table_name == "" || $dao->table_name === NULL)
			return false;
		else
			return true;
	}

	/**
	 * Insert DAO into database
	 */
	static function Insert(&$dao)
	{
		if (!self::CheckDao($dao)) {
			echo "<p>Invalid DAO</p>";
			debug_print_backtrace();
			return false;
		}

		$vars = get_object_vars($dao);
		$keys = array_keys($vars);
		$columns = "";
		$values = "";

		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $vars[$key];

			// Don't insert primary key or table_name
			if ($key == "id" || $key == "table_name")
				continue;

			if ($i > 0) {
				$columns .= ", ";
				$values .= ", ";
			}

			$columns .= $key;
			$values .= ":".$key;
		}

		$query = "INSERT INTO ".$dao->table_name." (".$columns.") VALUES(".$values.")";
		$stmt = self::$handle->prepare($query);

		if ($stmt === false) {
			echo "<p>Failed to prepare statement: ".$query."</p>";
			return false;
		}

		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $vars[$key];

			if ($key == "id" || $key == "table_name")
				continue;

			$stmt->bindValue(":".$key, $value);
		}

		if ($stmt->execute() === false) {
			echo "<p>Execute failed on query: ".$query."</p>";
			return false;
		}

		$dao->id = self::InsertID();

		// Increase the performance counter
		self::$perf_inserts++;

		return true;
	}

	/**
	 * Delete DAO from database
	 */
	static function Delete(&$dao)
	{
		DB::Query("DELETE FROM ".$dao->table_name." WHERE id=:id", array("id" => $dao->id));
		$dao->id = 0;

		// Increase the performance counter
		self::$perf_deletes++;
	}

	/**
	 * Update row in database with ID and attributes specified in DAO
	 */
	static function Update($dao)
	{
		if (!self::CheckDao($dao)) {
			echo "<p>Invalid DAO</p>";
			debug_print_backtrace();
			return false;
		}

		$vars = get_object_vars($dao);
		$keys = array_keys($vars);
		$params = array(); // For use by PrintErrors
		$columns = "";

		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];

			// Don't update primary key
			if ($key == "id" || $key == "table_name")
				continue;
			if ($i > 0)
				$columns .= ", ";
			$columns .= $key."=:".$key;
		}

		$sql = "UPDATE ".$dao->table_name." SET ".$columns." WHERE id=:id";
		$stmt = self::$handle->prepare($sql);

		$found_id = false;
		for ($i = 0; $i < count($keys); $i++) {
			$key = $keys[$i];
			$value = $vars[$key];
			// Safety check for an 'id' parameter
			if ($key == "id")
				$found_id = true;

			// Always skip "table_name"
			if ($key == "table_name")
				continue;

			$stmt->bindValue(":".$key, $value);
			$params[$key] = $value;
		}

		if (!$found_id)
			return false;

		if ($stmt->execute() === false) {
			self::PrintErrors($params, $stmt);
			return false;
		}

		// Increase the performance counter
		self::$perf_updates++;

		return $stmt->rowCount();
	}

	static function PrintErrors($params = NULL, $stmt = NULL)
	{
		if ($stmt == NULL)
			$errors = self::$handle->errorInfo();
		else
			$errors = $stmt->errorInfo();

		if ($stmt != NULL)
			$sql = $stmt->queryString."<br/>";
		else
			$sql = "";

		echo "<p><b>SQL Error:</b><br/>".$sql;
		if ($params != NULL) {
			$keys = array_keys($params);
			for ($i = 0; $i < count($keys); $i++)
				echo $keys[$i].": ".$params[$keys[$i]]."<br/>";
		}
		echo "</p><p>";
		foreach ($errors as $error)
			echo $error."<br/>";
		echo "</p>";
		debug_print_backtrace();
	}

	/**
	 * Execute parameterized query with provided parameters
	 */
	static function Query($query, $params = NULL)
	{
		$stmt = self::$handle->prepare($query);

		if (is_array($params)) {
			$keys = array_keys($params);
			foreach($keys as $key)
				$stmt->bindValue(":".$key, $params[$key]);
		}

		if ($stmt->execute() === false) {
			DB::PrintErrors($params);
			return false;
		}

		// Increase the performance counter
		self::$perf_queries++;

		self::$last_affected_rows = $stmt->rowCount();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Convert associative array row to object of specified class
	 */
	static function RowToObj($class, $row)
	{
		if (!is_array($row))
			return false;

		$obj = new $class;
		$keys = array_keys($row);
		
		foreach ($keys as $key)
			$obj->$key = $row[$key];

		return $obj;
	}

	/**
	 * Fetch an object with specified ID
	 */
	static function ObjByID($class, $id)
	{
		$obj = new $class; // Needed to figure out the table name
		$res = DB::Query("SELECT * FROM ".$obj->table_name." WHERE id=:id", array("id" => $id));
		if (count($res) == 1)
			return self::RowToObj($class, $res[0]);
		else
			return false;
	}

	/**
	 * Delete the row with the specified id in the table corresponding to the specified class
	 */
	static function DeleteByID($class, $id)
	{
		$obj = new $class; // Needed to figure out the table name

		// Increase the performance counter
		self::$perf_deletes++;

		return DB::Query("DELETE FROM ".$obj->table_name." WHERE id=:id", array("id" => $id));
	}

	static function ConvertToEntities(&$obj)
	{
		foreach ($obj as $var => $value) {
			$obj->$var = htmlentities($value, ENT_QUOTES, "UTF-8");
		}
	}
}

?>
