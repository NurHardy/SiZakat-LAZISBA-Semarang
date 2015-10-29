<?php
/*
 * load_core.php
 * ==> Prosedur utama dalam proses load dari stage masuk ke operational database
 * 
 * Output: JSON
 */
	
	$doProcess = $_POST['stage'];
	$doProcess = strtolower($doProcess);
	
	// Load library untuk query
	require COMPONENT_PATH.'/libraries/querybuilder.php';
	
	$affectedRows = 0;
	if ($doProcess == "penerimaan") {
		// Query source:
		// http://stackoverflow.com/questions/5253302/insert-into-select-for-all-mysql-columns
		
		$validRowQuery = "((id_donatur <> 0) AND (id_teller <> 0) AND (kode_akun <> '0') AND (id_bank <> -1))";
		// Begin transaction...
		mysqli_autocommit($mysqli, false);
		
		$processQuery =
			"INSERT INTO penerimaan (no_nota, tanggal, id_donatur, id_teller, jumlah, keterangan, ".
				"id_akun, is_ramadhan, thn_ramadhan, th_kubah, id_bank) ".
			"SELECT no_nota, tanggal, id_donatur, id_teller, jumlah, keterangan, ".
				"kode_akun, 0, th_ramadhan, th_kubah, id_bank ".
			"FROM stage_penerimaan ".
			"WHERE ".$validRowQuery;
		
		$processResult = mysqli_query($mysqli, $processQuery);
		if ($processResult != false) {
			$clearQuery = "DELETE FROM stage_penerimaan WHERE ".$validRowQuery;
			$clearResult = mysqli_query($mysqli, $clearQuery);
			if ($clearResult != false) {
				$affectedRows = mysqli_affected_rows($mysqli);
				mysqli_commit($mysqli);
				echo json_encode(array(
					'status' => 'ok',
					'message' => 'Load berhasil! '.$affectedRows." record berhasil diproses."
				));
			} else {
				mysqli_rollback($mysqli);
				echo json_encode(array(
					'status' => 'error',
					'error'	=> 'Terjadi kesalahan internal. Query error: '.mysqli_error($mysqli)
				));
			}
			
		} else {
			echo json_encode(array(
				'status' => 'error',
				'error'	=> 'Terjadi kesalahan internal. Query error: '.mysqli_error($mysqli)
			));
		}
	} else if ($doProcess == "pengeluaran") {
		$validRowQuery = "((id_penerima <> -1) AND (id_pj <> -1) AND (kode_akun <> '0') AND (id_bank <> -1))";
		// Begin transaction...
		mysqli_autocommit($mysqli, false);
		
		$processQuery =
			"INSERT INTO penyaluran (no_nota, tanggal, id_penerima, id_teller, jumlah, keterangan, ".
				"id_akun, is_ramadhan, thn_ramadhan, th_kubah, id_bank) ".
			"SELECT no_nota, tanggal, id_penerima, id_pj, jumlah, keterangan, ".
				"kode_akun, 0, th_ramadhan, th_kubah, id_bank ".
			"FROM stage_pengeluaran ".
			"WHERE ".$validRowQuery;
		
		$processResult = mysqli_query($mysqli, $processQuery);
		if ($processResult != false) {
			$clearQuery = "DELETE FROM stage_pengeluaran WHERE ".$validRowQuery;
			$clearResult = mysqli_query($mysqli, $clearQuery);
			if ($clearResult != false) {
				$affectedRows = mysqli_affected_rows($mysqli);
				mysqli_commit($mysqli);
				$msgProses = 'Load berhasil! '.$affectedRows." record berhasil diproses.";
				echo json_encode(array(
					'status' => 'ok',
					'message' => $msgProses
				));
			} else {
				mysqli_rollback($mysqli);
				echo json_encode(array(
					'status' => 'error',
					'error'	=> 'Terjadi kesalahan internal. Query error: '.mysqli_error($mysqli)
				));
			}
			
		} else {
			echo json_encode(array(
				'status' => 'error',
				'error'	=> 'Terjadi kesalahan internal. Query error: '.mysqli_error($mysqli)
			));
		}
	} else {
		echo json_encode(array(
				'status' => 'error',
				'error'	=> 'Unrecognized stage.'
		));
		return;
	}