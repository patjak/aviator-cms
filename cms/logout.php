<?php
session_start();
unset($_SESSION['logged_in']);
session_destroy();

$site_base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
header("Location: ".$site_base."\n");
?>
