<?php
// Output: JSON
//---------------------------
// Proses mapping sebuah kolom pada stage pengeluaran
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
			"UPDATE stage_pengeluaran SET kode_akun='%s' WHERE ket_akun='%s' AND kode_akun='0'",
			mysqli_real_escape_string($mysqli, $mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
		
	} else if ($col == "penerima") {
		$updateQuery = sprintf(
			"UPDATE stage_pengeluaran SET id_penerima=%d WHERE nama_penerima='%s' AND id_penerima=-1",
			intval($mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
	} else if ($col == "pj") {
		$updateQuery = sprintf(
			"UPDATE stage_pengeluaran SET id_pj=%d WHERE nama_pj='%s' AND id_pj=-1",
			intval($mapDest),
			mysqli_real_escape_string($mysqli, $mapSource)
		);
	} else if ($col == "bank") {
		$updateQuery = sprintf(
				"UPDATE stage_pengeluaran SET id_bank=%d WHERE nama_bank='%s' AND id_bank=-1",
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
	
