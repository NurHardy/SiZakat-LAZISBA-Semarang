<?php
/*
 * dokumen_hapus.php
 * ==> AJAX server untuk menghapus dokumen perencanaan
 *
 * Digunakan pada :
 * o AM_SIZ_RA_CONFHPSDOKUMEN | Konfirmasi Hapus Dokumen
 */

	// Cek privilege
	if (!ra_check_privilege(RA_ID_ADMIN, false)) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Akses tidak diperbolehkan. Silakan hubungi administrator."
		));
		return;
	}
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	$querySuccess	= true;
	$itemDeleted	= 0;
	
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	
	// Dapatkan tahun dokumen yang akan dihapus
	$tahunDokumen = $_POST['th'];
	
	if (empty($tahunDokumen)) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Argumen tidak lengkap."
		));
		return;
	}
	
	// Cek Dokumen
	$rowDocument = ra_cek_dokumen($tahunDokumen);
	if (!$rowDocument) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Dokumen perencanaan tidak ditemukan."
		));
		return;
	}
	
	// Pengecekan jumlah agenda dokumen
	$queryCek = sprintf("SELECT COUNT(*) AS j FROM ra_agenda WHERE YEAR(tgl_mulai)=%d",
			$tahunDokumen);
	$jumlahAgenda = querybuilder_getscalar($queryCek);
	
	if ($jumlahAgenda == null) {
		$querySuccess = false;
		$jumlahAgenda = 0;
	}
	
	if ($jumlahAgenda > 0) {
		// Proses penghapusan agenda...
		mysqli_autocommit($mysqli, false);
		$querySuccess = true;
		$itemDeleted = 0;
		
		$queryGetAgenda = sprintf("SELECT * FROM ra_agenda WHERE YEAR(tgl_mulai)=%d", $tahunDokumen);
		$resultGetAgenda = mysqli_query($mysqli, $queryGetAgenda);
		
		while ($itemAgenda = mysqli_fetch_assoc($resultGetAgenda)) {
			if (!IS_DEBUGGING) {
				$queryHapusRincian = sprintf(
						"DELETE FROM ra_rincian_agenda WHERE id_agenda=%d", $itemAgenda['id_agenda']);
				$resultHapusRincian = mysqli_query($mysqli, $queryHapusRincian);
				if ($resultHapusRincian == null) {
					$querySuccess = false; break;
				}
				$queryHapusAgenda = sprintf(
						"DELETE FROM ra_agenda WHERE id_agenda=%d", $itemAgenda['id_agenda']);
				$resultHapusAgenda = mysqli_query($mysqli, $queryHapusAgenda);
				if ($resultHapusAgenda == null) {
					$querySuccess = false; break;
				}
			}
			$itemDeleted++;
		}
		
		if (!IS_DEBUGGING) {
			// Proses penghapusan catatan kegiatan...
			$queryHapusCat = sprintf("DELETE FROM ra_catatan_kegiatan WHERE tahun=%d", $tahunDokumen);
			$resultHapusCat = mysqli_query($mysqli, $queryHapusCat);
			if (!$resultHapusCat) {
				$querySuccess = false;
			}
		}
	}
	
	if ($querySuccess && !IS_DEBUGGING && ($jumlahAgenda == 0)) {
		$queryHapusDok = sprintf("DELETE FROM ra_dokumen WHERE tahun_dokumen=%d", $tahunDokumen);
		$resultHapusDok = mysqli_query($mysqli, $queryHapusDok);
		if (!$resultHapusDok) {
			$querySuccess = false;
		}
	}
	
	if ($querySuccess) {
		mysqli_commit($mysqli);
		echo json_encode(array(
				'status' => 'ok',
				'tahun' => $tahunDokumen,
				'agenda_deleted' => $itemDeleted
		));
	} else {
		mysqli_rollback($mysqli);
		echo json_encode(array(
				'status' => 'error',
				'error' => "Terjadi kesalahan internal. Error: ".mysqli_error($mysqli)
		));
	}