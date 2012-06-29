<?php

require_once("../include.php");

if (isset($_POST['start_page']))
	$start_page = (int)$_POST['start_page'];
else
	$start_page = 0;

Settings::Set("site_start_page", $start_page);
?>
