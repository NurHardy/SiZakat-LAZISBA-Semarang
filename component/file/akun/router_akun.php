<?php

$actionWord = $_GET['action'];

if ($actionWord=="detail") {
	include COMPONENT_PATH."/file/akun/detail_akun.php";
} else {
	$errorDescription = "Argumen tidak lengkap.";
	include COMPONENT_PATH."/file/pages/error.php";
	return;
}