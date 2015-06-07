<?php

function querybuilder_generate_set($queryFields) {
	global $mysqli;

	$finalSet = "";
	if (empty($queryFields)) return $finalSet;
	foreach ($queryFields as $fieldName => $fieldValue) {
		$finalSet .= $fieldName . "='" . $mysqli->real_escape_string($fieldValue) . "',";
	}
	$finalSet = trim($finalSet, ",");
	return $finalSet;
}

function querybuilder_checkset($queryFields) {
	$checkResult = true;
	foreach ($queryFields as $fieldName => $fieldValue) {
		if (($fieldName == "") || ($fieldValue == "")) {
			$checkResult = false;
			break;
		}
	}
	return $checkResult;
}