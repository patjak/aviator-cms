<?php

require_once("common.php");

$site_title = Settings::Get("site_title");

if (isset($_POST['__user']) && isset($_POST['__pass'])) {
	$username = $_POST['__user'];
	$error_msg = "<h2><img src=\"pics/icons_32/warning.png\"/> Log in failed</h2><p>Username and/or password is incorrect</p>";
} else {
	$username = "";
	$error_msg = "";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="style/style.css"/>
<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
<script type="text/javascript" src="jquery/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/dialogs.js"></script>
<script type="text/javascript" src="js/loading.js"></script>
<title><?php echo $site_title;?> - Log in</title>
</head>
<body style="background-color: #dddddd;">
<div style="text-align: center; font-size: 16pt; font-family: arial, sans-serif; margin-top: 100px;">

</div>
<div class="Box" style="width: 360px; margin-left: auto; margin-right: auto; text-align: center;">
<h3><img src="pics/icons_32/key.png" style="margin: 10px;"/> Enter your log in information</h3>
<form action="" method="POST" id="login_form" action="">
<table style="margin-left: auto; margin-right: auto; text-align: left;">
<tr><td>Username</td><td><input type="text" name="__user" value="<?php echo $username;?>"/></td></tr>
<tr><td>Password</td><td><input type="password" name="__pass"/></td></tr>
<tr><td></td><td><button style="width: 150px;" type="submit" onclick="LoadShow(); return true;"><img src="pics/icons_24/check.png"/> Log in</button></td></tr>
</table>
</div>
<div id="ErrorDimmer" class="Fullscreen"></div>
<div id="ErrorContents" class="Box"><?php echo $error_msg;?></div>
<div id="ErrorClose"><img src="pics/icons_32/cross_white.png"/></div>

<div id="LoadDimmer" class="Fullscreen"></div>
<div id="LoadContents" class="Fullscreen"><img src="pics/icons_64/clock_white.png" style="margin: 10px;"/><br/>Loading...</div>
</body>
</html>
<?php exit();?>
