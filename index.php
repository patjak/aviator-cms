<?php
$SECURE = true; // Mark this a valid entry point
require_once("common.php");

$base_path = substr(SITE_BASE, (strpos(SITE_BASE, $_SERVER['HTTP_HOST']) +
			        strlen($_SERVER['HTTP_HOST'])));
$perma_path = substr($_SERVER['REQUEST_URI'], strlen($base_path));

require_once("themes/".THEME_DIR."/config.php");

$theme_path = Theme::GetPath();
$theme_base = Theme::GetBase();

if (!isset($_GET['page_id']) && $perma_path != "") {
	$path = explode("/", $perma_path);

	// Start searching at the top pages
	$parent_id = NULL;

	// Search for page
	foreach ($path as $permalink) {
		if ($permalink == "")
			continue;

		$page_id = Permalink::Search($permalink, $parent_id);
		if ($page_id == NULL)
			break;

		// We found a part so set parent and continue searching
		$parent_id = $page_id;
	}

	// Check if we found a page or not
	if ($page_id == NULL) {
		require_once("themes/".THEME_DIR."/404.php");
		exit();
	} else {
		$_GET['page_id'] = $page_id;
	}
}

require_once($theme_path."theme.php");

?>
