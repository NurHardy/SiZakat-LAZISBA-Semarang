<?php
// Proses menghapus sumberdana
//-------------------------------

	$errorDesc = null;
	
	// Input
	$idPersamaan = intval($_POST['id_pers']);
	
	// Cek persamaan akun
	$queryCek = sprintf("SELECT * FROM persamaan_akun WHERE id_persamaan=%d",
			$idPersamaan);
	$resultCek = mysqli_query($mysqli, $queryCek);
	if ($resultCek != null) {
		if (mysqli_num_rows($resultCek) > 0) {
			$dataPersamaan = mysqli_fetch_assoc($resultCek);
			
			$queryHapus = sprintf("DELETE FROM persamaan_akun WHERE id_persamaan=%d",
					$idPersamaan);
			$resultHapus = mysqli_query($mysqli, $queryHapus);
			if ($resultHapus != null) {
				// Query berhasil
			} else {
				$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
			}
		} else {
			$errorDesc = "Persamaan akun tidak ditemukan!";
		}
	} else {
		$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
	}
	
	// Jika masih terdapat error
	if (!empty($errorDesc)) {
		echo json_encode ( array (
				'status' => 'error',
				'error' => $errorDesc
		) );
	} else {
		echo json_encode ( array (
				'status'		=> 'ok',
				'old_id_pers'	=> $idPersamaan
		) );
	}