<?php
ob_start();
session_start();

set_time_limit(7200);

/*
	Includes
*/
# Settings
require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
# Library
require_once($_SERVER['DOCUMENT_ROOT'].'/admin/lib/db/db_mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/admin/lib/exceptions/exceptions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/admin/lib/Settings.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/admin/lib/cmsPhotoalbum.php');

try {
	/*
		Databaseconnectie
	*/
	$db = new db($settings['db']['host'], $settings['db']['user'], $settings['db']['password'], $settings['db']['db']);
	$db->connect();
	
	$_settings = new settings($db);
	$settings = array_merge($_settings->getSettings(),$settings);
	require_once($_SERVER['DOCUMENT_ROOT'].'/settings.php');
	
	$params = $_POST;
	
	$_pages = new photoalbum($db, $settings, $params, $file);
	print_r($_pages->processPictures());
	

}
catch ( NiceException $n ){
	echo $n->getError();
}
catch ( MyException $e ) {
	echo $e->getError();
}
?>