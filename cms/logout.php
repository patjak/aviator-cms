<?php
session_start();
unset($_SESSION['user_id']);
session_destroy();

$site_base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
header("Location: ".$site_base."\n");
?>
