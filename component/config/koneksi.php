<?php
/*
 * koneksi.php
 * =====================================
 * Konfigurasi koneksi database dan path sistem e-SIZ LAZISBA
 */
	define('IS_DEBUGGING', false);
	define('SIZ_VERSION', "SiZakat v.1.5.1 (20 Oktober 2015)");
	
	define('SITE_PATH', '/siz');
	define('SITE_DOMAIN', 'http://localhost');
	
	$host = "127.0.0.1";
	$username = "root";
	$password = "";
	$database = "lazisba1_siz";
	
	global $mysqli;
	$mysqli = new mysqli($host, $username, $password, $database);
	
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}

	$sizConfigEmailHost = "";
	$sizConfigEmailUser = "";
	$sizConfigEmailPass = "";
	$sizConfigEmailFrom = "";
	
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	
	date_default_timezone_set ( "Asia/Jakarta" );
