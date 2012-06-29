<?php

class URL {
	public static function GetPage()
	{
		if (isset($_GET['page']))
			return (int)$_GET['page'];
		else
			return PAGE_HOME;
	}
}

?>
