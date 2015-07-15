<?php

function querybuilder_generate_set($queryFields) {
	global $mysqli;

	$finalSet = "";
	if (empty($queryFields)) return $finalSet;
	foreach ($queryFields as $fieldName => $fieldValue) {
		if (is_null($fieldValue)) {
			$finalSet .= $fieldName . "=NULL,";
		} else {
			$finalSet .= $fieldName . "='" . $mysqli->real_escape_string($fieldValue) . "',";
		}
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

/**
 * Mengambil nilai scalar dari query (misal SELECT SUM(*))
 * @param string $scalarQuery Query
 * @return string|integer|array|NULL
 */
function querybuilder_getscalar($scalarQuery) {
	global $mysqli, $queryCount;
	$qResult = mysqli_query($mysqli, $scalarQuery);
	$queryCount++;
	if ($qResult != null) {
		$rowData = mysqli_fetch_row($qResult);
		if (is_array($rowData)) {
			if (count($rowData) == 1)
				return $rowData[0];
			else return $rowData;
		}
	}
	return null;
}
function check_array_id($arrayOfInt) {
	if (!is_array($arrayOfInt)) {
		return false;
	} else {
		foreach ($arrayOfInt as $itemInt) {
			if (!is_numeric($itemInt)) {
				return false;
			} else if ($itemInt <= 0) {
				return false;
			}
		}
	}
	return true;
}