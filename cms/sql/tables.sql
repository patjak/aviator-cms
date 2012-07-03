CREATE TABLE settings (
        id INT NOT NULL AUTO_INCREMENT,
        name TEXT,
        value TEXT,

        PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

# The sections contain the width in percent (excluding the padding)
CREATE TABLE layouts (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	header INT	DEFAULT 0,
	column_1 INT	DEFAULT 0,
	column_2 INT	DEFAULT 0,
	column_3 INT	DEFAULT 0,
	column_4 INT	DEFAULT 0,
	footer INT	DEFAULT 0,

	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE plugins (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	directory TEXT,

	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE plugin_settings (
	id INT NOT NULL AUTO_INCREMENT,
	plugin_id INT,
	name TEXT,
	value TEXT,

	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE themes (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,

	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE page_types (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	plugin_id INT,

	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE page_styles (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	theme_id INT,

	FOREIGN KEY (theme_id) REFERENCES themes(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE pages (
        id INT NOT NULL AUTO_INCREMENT,
        title TEXT,
        parent_id INT		DEFAULT NULL,
	layout_id INT		DEFAULT NULL,
	type_id INT		DEFAULT NULL,
	style_id INT		DEFAULT NULL,
        sort INT		DEFAULT 0,
        published INT		DEFAULT 0,
        in_menu INT		DEFAULT 1,
        allow_edit INT		DEFAULT 1,
        allow_move INT		DEFAULT 1,
        allow_delete INT	DEFAULT 1,
        allow_subpage INT	DEFAULT 1,
	allow_change_style INT	DEFAULT 1,

        FOREIGN KEY (parent_id) REFERENCES pages(id),
	FOREIGN KEY (layout_id) REFERENCES layouts(id),
	FOREIGN KEY (type_id) REFERENCES page_types(id),
	FOREIGN KEY (style_id) REFERENCES page_styles(id),
        PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE contents (
	id INT NOT NULL AUTO_INCREMENT,
	plugin_id INT,
	page_id INT,
	section_id INT,
	internal_id INT,
	name TEXT,
	sort INT,

	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (page_id) REFERENCES pages(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

# The internal id is used by the plugin author to identify it
CREATE TABLE strings (
	id INT NOT NULL AUTO_INCREMENT,
	plugin_id INT,
	content_id INT,
	internal_id INT,
	string TEXT,
	sort INT,

	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE integers (
	id INT NOT NULL AUTO_INCREMENT,
	plugin_id INT,
	content_id INT,
	internal_id INT,
	number INT,
	sort INT,

	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE links (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	is_internal INT DEFAULT 0, # 0 = internal, 1 = external
	internal_page_id INT, # If is_internal = 0 and this is null, the link is broken 
	external_url TEXT,
	in_new_window INT, # 0 = open in same window, 1 = open in new window
	sort INT,
	enabled INT, # Link can be disabled but we still like to keep its settings

	plugin_id INT,
	content_id INT,
	internal_id INT,

	FOREIGN KEY (internal_page_id) REFERENCES pages(id),
	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE images (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,
	width INT,
	height INT,
	format INT,		# 1 = JPEG, 2 = PNG, 3 = GIF

	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

# References to images since an image can be shared among contents
CREATE TABLE image_refs (
	id INT NOT NULL AUTO_INCREMENT,
	image_id INT,
	plugin_id INT,
	content_id INT,
	internal_id INT,
	sort INT,
	link_id INT,

	# Percentage of where on the image our cropping center is
	crop_horizontal INT DEFAULT 50, 
	crop_vertical INT DEFAULT 50,

	FOREIGN KEY (image_id) REFERENCES images(id),
	FOREIGN KEY (link_id) REFERENCES links(id),
	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

# Contains pre-scaled/resized images requested by the theme or CMS
CREATE TABLE image_cache (
	id INT NOT NULL AUTO_INCREMENT,
	image_id INT,
	image_ref_id INT,
	width INT,
	height INT,
	effects INT DEFAULT 0,
	crop_horizontal INT DEFAULT 50,
	crop_vertical INT DEFAULT 50,

	FOREIGN KEY (image_id) REFERENCES images(id),
	FOREIGN KEY (image_ref_id) REFERENCES image_refs(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE resources (
	id INT NOT NULL AUTO_INCREMENT,
	name TEXT,	# A description of what the resource protects
	page_id INT,
	plugin_id INT,
	content_id INT,
	internal_id INT,

	FOREIGN KEY (page_id) REFERENCES pages(id),
	FOREIGN KEY (plugin_id) REFERENCES plugins(id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
	username TEXT,
	password TEXT,	# Stored as MD5 sum
	fullname TEXT,
	email TEXT,
	full_access INT, # Gives access to everything if == 1

	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE permissions (
	id INT NOT NULL AUTO_INCREMENT,
	resource_id INT,
	user_id INT,

	# Describes what kind of actions a user can do on a given resource
	allow_create INT,
	allow_update INT,
	allow_delete INT,

	FOREIGN KEY (resource_id) REFERENCES resources(id),
	FOREIGN KEY (user_id) REFERENCES users(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;

CREATE TABLE access_logs (
	id INT NOT NULL AUTO_INCREMENT,
	timestamp DATETIME,
	user_id INT,
	permission_id INT,
	type INT,	# Bitmask: create = 001, update = 010, delete = 100 

	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (permission_id) REFERENCES permissions(id),
	PRIMARY KEY (id)
) ENGINE=INNODB CHARACTER SET utf8;
