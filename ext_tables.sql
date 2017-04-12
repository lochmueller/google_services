#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	node_priority varchar(255) DEFAULT '' NOT NULL,
	exclude_sitemap tinyint(1) DEFAULT '0'
);

#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	node_priority varchar(255) DEFAULT '' NOT NULL,
	exclude_sitemap tinyint(1) DEFAULT '0'
);

