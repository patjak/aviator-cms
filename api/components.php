<?php

class Blob extends ComponentAPI {
	public	$data;

	public function Create()
	{
		$data = mysql_real_escape_string($this->data);
		$plugin_id = $this->plugin_id == NULL ? "NULL": $this->plugin_id;
		$module_id = $this->module_id == NULL ? "NULL": $this->module_id;
		$content_id = $this->content_id == NULL ? "NULL": $this->content_id;
		DB::Query("INSERT INTO ".DB_PREFIX."blobs (plugin_id, content_id, internal_id, data) ".
		"values(".$plugin_id.", ".$content_id.", ".$this->internal_id.", '".$data."')");

		$insert_id = DB::InsertId();
		DB::Query("UPDATE ".DB_PREFIX."blobs SET sort=id WHERE id=".$insert_id);
		$this->id = $insert_id;
	}

	public function Delete()
	{
		DB::Query("DELETE FROM ".DB_PREFIX."blobs WHERE id=".$this->id);
	}

	public function Update()
	{
		$str = "";

		if ($this->content_id != NULL)
			$str .= "content_id=".$this->content_id;
		else if ($this->module_id != NULL) // FIXME: This is wrong! There must be plugin and module for module_ids, content_id can live on it's own though
			$str .= "module_id=".$this->module_id;
		else if ($this->plugin_id != NULL)
			$str .= "plugin_id=".$this->plugin_id;

		if ($str == "")
			return false;

		$data = mysql_real_escape_string($this->data);

		$str .= " AND internal_id=".$this->internal_id;
		$res = DB::Query("UPDATE ".DB_PREFIX."blobs SET data='".$data."' WHERE ".$str);
	}

	public function Get()
	{
		$str = "";

		if ($this->content_id != NULL)
			$str .= "content_id=".$this->content_id;
		else if ($this->module_id != NULL) // FIXME: This is wrong! There must be plugin and module for module_ids, content_id can live on it's own though
			$str .= "module_id=".$this->module_id;
		else if ($this->plugin_id != NULL)
			$str .= "plugin_id=".$this->plugin_id;

		if ($str == "")
			return false;

		$str .= " AND internal_id=".$this->internal_id;

		$res = DB::Query("SELECT * FROM ".DB_PREFIX."blobs WHERE ".$str);
		$num_rows = DB::NumRows($res);
		if ($num_rows > 1 || $num_rows == 0)
			return false;

		$obj = DB::Obj($res, "ContentOppettiderWeek");

		$this->data = $obj->data;
		$this->sort = $obj->sort;
		$this->id = $obj->id;

		return true;
	}
}

class ComponentAPI {
	protected	$id = NULL,
			$plugin_id = NULL,
			$module_id = NULL,
			$content_id = NULL,
			$internal_id = 0;
			// FIXME: Sort?

	public function SetPlugin($plugin)
	{
		$this->plugin_id = $plugin->GetId();
	}

	public function SetModule($module)
	{
		$this->module_id = $module->GetId();
	}

	public function SetContent($content)
	{
		$this->content_id = $content->GetId();
	}

	public function SetInternalId($id)
	{
		$this->internal_id = $id;
	}
}

?>
