<?php

/* Section definition */
define('SECTION_HEADER', 1 << 0);
define('SECTION_COLUMN_1', 1 << 1);
define('SECTION_COLUMN_2', 1 << 2);
define('SECTION_COLUMN_3', 1 << 3);
define('SECTION_COLUMN_4', 1 << 4);
define('SECTION_FOOTER', 1 << 6);

define('SECTION_ALL', SECTION_HEADER | SECTION_COLUMN_1 | SECTION_COLUMN_2 |
		      SECTION_COLUMN_3 | SECTION_COLUMN_4 | SECTION_FOOTER);

/* The layout widths decides what kind of content you can put in it */

// FIXME: This can be removed since we no longer store layouts in the database
class DaoLayout {
	public	$name,
		$header,
		$footer,
		$column_1,
		$column_2,
		$column_3,
		$column_4;
}

?>
