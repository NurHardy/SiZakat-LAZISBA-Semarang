<?php
// Output: JSON
//---------------------------
// Proses menyimpan sumber dana akun penyaluran

	$idPersamaan = (isset($_POST['siz_id_pers'])?intval($_POST['siz_id_pers']):-1);
	$isCreateNew = ($idPersamaan == -1);
	
	$errorDesc = null;
	
	if ($isCreateNew) {
		$kodePenerimaan		= $_POST['siz_select_akun'];
		$idPengeluaran		= $_POST['siz_id_pengeluaran'];
	}
	$kodePengeluaran	= "";
	$htmlListBaru		= "";
	
	$persentase			= trim($_POST['siz_persentase']);
	$prioritasSumber	= trim($_POST['siz_prioritas']);
	
	//=========== VALIDASI
	if (!preg_match("/^([0-9]|[0-9][0-9]|100)(.([0-9]|[0-9][0-9]))?$/", $persentase)) {
		$errorDesc = "Nilai persentase tidak valid.";
	} else {
		$persentase = floatval($persentase);
		if (($persentase <= 0.0) || ($persentase > 100.00)) {
			$errorDesc = "Persentase harus diantara 0.1% hingga 100%.";
		}
	}
	if (!preg_match("/^[0-9]$/", $prioritasSumber)) {
		$errorDesc = "Nilai prioritas tidak valid.";
	} else {
		$prioritasSumber = intval($prioritasSumber);
		if (($prioritasSumber < 0) || ($prioritasSumber > 9)) {
			$errorDesc = "Prioritas harus diantara 0 hingga 9.";
		}
	}
	
	if (($errorDesc == null) && $isCreateNew) {
		//=========== CEK AKUN PENGELUARAN [KHUSUS TAMBAH SUMBERDANA]
		$queryCekAkun = sprintf("SELECT * FROM akun WHERE idakun=%d", $idPengeluaran);
		$resultCekAkun = mysqli_query($mysqli, $queryCekAkun);
		if ($resultCekAkun != null) {
			if (mysqli_num_rows($resultCekAkun) > 0) {
				// Jika ditemukan, ambil kode akunnya...
				$dataAkun = mysqli_fetch_assoc($resultCekAkun);
				$kodePengeluaran = $dataAkun['kode'];
			} else {
				$errorDesc = "Akun pengeluaran tidak ditemukan!";
			}
		} else {
			$errorDesc = "Terjadi kesalahan internal.";
		}
	}
	if ($errorDesc == null) {
		//=========== CEK SUMBER DANA
		$queryCekSumberDana = "SELECT * FROM persamaan_akun WHERE ";
		if ($isCreateNew) { // Query dikondisikan
			$queryCekSumberDana .= sprintf("id_penerimaan='%s' AND id_penyaluran='%s'",
					$kodePenerimaan, $kodePengeluaran);
		} else {
			$queryCekSumberDana .= sprintf("id_persamaan=%d",
					$idPersamaan);
		}
		
		$resultCek = mysqli_query($mysqli, $queryCekSumberDana);
		if ($resultCek != null) {
			if (mysqli_num_rows($resultCek) == 0) { // Persamaan akun tidak ditemukan
				if ($isCreateNew) {
					$queryTambahSumberDana = sprintf("INSERT INTO persamaan_akun ".
							"SET id_penerimaan='%s', id_penyaluran='%s', persentase=%.2f, prioritas=%d",
							$kodePenerimaan,
							$kodePengeluaran,
							$persentase,
							$prioritasSumber
					);
					$resultSimpan = mysqli_query($mysqli, $queryTambahSumberDana);
					// Query berhasil
					if ($resultSimpan != null) {
						$idPersamaan = mysqli_insert_id($mysqli);
						require_once COMPONENT_PATH."/file/akun/helper_akun.php";
						$htmlListBaru = getHTMLSumberDana($kodePengeluaran);
					} else {
						$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
					}
				} else { // Jika edit
					$errorDesc = "Data persamaan akun tidak ditemukan.";
				}
				
			} else { // Persamaan akun ditemukan
				if ($isCreateNew) {
					$errorDesc = "Sumber dana telah ditambahkan.";
				} else {
					$rowPersamaan = mysqli_fetch_assoc($resultCek);
					$kodePengeluaran = $rowPersamaan['id_penyaluran'];
					$queryEditSumberDana = sprintf("UPDATE persamaan_akun ".
							"SET persentase=%.2f, prioritas=%d ".
							"WHERE id_persamaan=%d",
							$persentase,
							$prioritasSumber,
							$idPersamaan
					);
					$resultSimpan = mysqli_query($mysqli, $queryEditSumberDana);
					// Query berhasil
					if ($resultSimpan != null) {
						require_once COMPONENT_PATH."/file/akun/helper_akun.php";
						$htmlListBaru = getHTMLSumberDana($kodePengeluaran);
					} else {
						$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
					}
				}
			} // End if persamaan akun ditemukan
		} // End if query cek berhasil
	} // End if valid
	
	// Jika masih terdapat error
	if (!empty($errorDesc)) {
		echo json_encode ( array (
			'status' => 'error',
			'error' => $errorDesc
		) );
	} else {
		echo json_encode ( array (
			'status'	=> 'ok',
			'id_pers'	=> $idPersamaan,
			'html'		=> $htmlListBaru
		) );
	}
	
