<?php

include("database.php");

try {
	$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "CREATE DATABASE IF NOT EXISTS camagru_chadams";
	$db->exec($sql);
	$sql = "USE camagru_chadams;
			CREATE TABLE users (
			id int(11) NOT NULL,
			username varchar(255) NOT NULL,
			password varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			vHash varchar(255) NOT NULL,
			verified boolean NOT NULL default 0) ENGINE MyISAM DEFAULT CHARSET=utf8;
			CREATE TABLE images (
			id int(11) NOT NULL,
			user_id int(11) NOT NULL,
			im_path varchar(255) NOT NULL,
			pub_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			likes int(11) NOT NULL) ENGINE MyISAM DEFAULT CHARSET=utf8;
			CREATE TABLE comments (
			id int(11) NOT NULL,
			user_id int(11) NOT NULL,
			image_id int (11) NOT NULL,
			content longtext NOT NULL) ENGINE MyISAM DEFAULT CHARSET=utf8;
			CREATE TABLE liked (
			id int(11) NOT NULL,
			image_id int(11) NOT NULL,
			user_id int(11) NOT NULL) ENGINE MyISAM DEFAULT CHARSET=utf8;
			ALTER TABLE users ADD PRIMARY KEY (id);
			ALTER TABLE images ADD PRIMARY KEY (id);
			ALTER TABLE comments ADD PRIMARY KEY (id);
			ALTER TABLE liked ADD PRIMARY KEY (id);
			ALTER TABLE users MODIFY id int(11) NOT NULL AUTO_INCREMENT;
			ALTER TABLE images MODIFY id int(11) NOT NULL AUTO_INCREMENT;
			ALTER TABLE comments MODIFY id int(11) NOT NULL AUTO_INCREMENT;
			ALTER TABLE liked MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
	$db->exec($sql);
}

catch (PDOException $e) {
	echo $e->getMessage();
	die("Failed!");
}

?>