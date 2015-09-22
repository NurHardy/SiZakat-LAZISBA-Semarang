<?php
// Output: JSON
//---------------------------
// Proses mapping sebuah kolom pada stage penerimaan
// Note: field input sesuai dengan AM_SIZ_MAP_PENERIMAAN
	
	// Begin process
	$col = $_POST['col'];
	$mapSource = $_POST['map_source'];
	$mapDest = $_POST['map_dest'];
	
	if (empty($mapSource)) {
		echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Map source label expected.'
		));
		return;
	}
	
	// Output initialization
	$resultMessage	= "";
	$isError		= false;
	
	if ($col == "akun") {
		$updateQuery = sprintf(
			"UPDATE stage_penerimaan SET kode_akun='%s' WHERE ket_akun='%s' AND kode_akun='0'",
			mysqli_real_escape_string($mysqli, $mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
		
	} else if ($col == "donatur") {
		$updateQuery = sprintf(
			"UPDATE stage_penerimaan SET id_donatur=%d WHERE nama_donatur='%s' AND id_donatur=0",
			intval($mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
	} else if ($col == "amilin") {
		$updateQuery = sprintf(
			"UPDATE stage_penerimaan SET id_teller=%d WHERE nama_amilin='%s' AND id_teller=0",
			intval($mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
	} else if ($col == "bank") {
		$updateQuery = sprintf(
				"UPDATE stage_penerimaan SET id_bank=%d WHERE nama_bank='%s' AND id_bank=-1",
				intval($mapDest),
				mysqli_real_escape_string($mysqli, $mapSource)
		);
	} else {
		echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized column tag.'
		));
		return;
	}

	// For debugging purpose only!!
	//if (IS_DEBUGGING) {
	//	$resultUpdate = true;
	//	$rowAffected = 0;
	//} else {
		$resultUpdate = mysqli_query($mysqli, $updateQuery);
		$rowAffected = mysqli_affected_rows($mysqli);
	//}
	
	
	if ($resultUpdate != false) {
		$resultMessage = "Update OK, ";
		if ($rowAffected > 0) {
			$resultMessage .= sprintf("%d rows affected.", $rowAffected);
		} else {
			$resultMessage .= "but no rows affected.";
		}
	} else {
		$isError = true;
		$resultMessage = 'Internal error. Query failed: '.mysqli_error($mysqli);
	}
	
	$jsonResult = array();
	
	if ($isError) {
		echo json_encode(array(
			'status' => 'error',
			'error' => $resultMessage
		));
	} else {
		echo json_encode(array(
			'status' => 'ok',
			'message' => $resultMessage
		));
	}
	
