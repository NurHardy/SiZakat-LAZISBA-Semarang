<?php
/*
 * hapus_master_kegiatan.php
 * ==> AJAX server untuk menghapus master kegiatan
 *
 * Digunakan pada :
 * o AM_SIZ_RA_CONFHPSMSTKGT | Konfirmasi Hapus Master Kegiatan
 * o AM_SIZ_RA_LISTKGT | List master Kegiatan
 */

	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	
	// Dapatkan id master kegiatan yang akan dihapus
	$idKegiatan = $_POST['id'];
	if (!isset($needConfirm)) $needConfirm = true; // Perlu konfirmasi?
	
	// Pengecekan record master kegiatan
	$queryCek = sprintf("SELECT * FROM ra_kegiatan WHERE id_kegiatan=%d", $idKegiatan);
	$resultGetKegiatan = mysqli_query($mysqli, $queryCek);
	$isAuthorized = true;
	$rowKegiatan = mysqli_fetch_array($resultGetKegiatan);
	if (!$isAdmin) {
		if ($rowKegiatan['divisi'] != $divisiUser) {
			$isAuthorized = false;
		}
	}
		
	if (!$isAuthorized) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Anda tidak mempunyai otorisasi untuk menghapus master kegiatan."
		));
		return;
	}
	
	// Pengecekan data agenda
	$queryCekAgenda = sprintf("SELECT COUNT(*) FROM ra_agenda WHERE id_kegiatan=%d", $idKegiatan);
	$jumlahAgenda = querybuilder_getscalar($queryCekAgenda);
	if ($jumlahAgenda === null) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Query error: ".mysqli_error($mysqli)
		));
		return;
	}
	
	// Proses penghapusan...
	mysqli_autocommit($mysqli, false);
	$querySuccess = true;
	$agendaDeleted = 0;
	
	if ($jumlahAgenda > 0) {
		if ($needConfirm) {
			echo json_encode(array(
					'status' => 'need-confirm',
					'url' => "main.php?s=perencanaan&action=hapus-master-kegiatan&id=".$idKegiatan
			));
			return;
		}
		
		// Hapus agenda dan rincian agenda...
		$queryCekAgenda = sprintf("SELECT * FROM ra_agenda WHERE id_kegiatan=%d", $idKegiatan);
		$resultCekAgenda = mysqli_query($mysqli, $queryCekAgenda);
		if ($resultCekAgenda && !IS_DEBUGGING) {
			while ($itemAgenda = mysqli_fetch_assoc($resultCekAgenda)) {
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
				$agendaDeleted++;
			} // End While
		} else if (!IS_DEBUGGING) $querySuccess = false;
	} // End If jumlahAgenda > 0
	
	if (!IS_DEBUGGING) {
		// Hapus rincian awal
		$queryHapusRincian = sprintf(
				"DELETE FROM ra_rincian_awal WHERE id_kegiatan=%d", $rowKegiatan['id_kegiatan']);
		$resultHapusRincian = mysqli_query($mysqli, $queryHapusRincian);
		if ($resultHapusRincian == null) {
			$querySuccess = false; break;
		}
		// Hapus catatan kegiatan
		$queryHapusCatatan = sprintf(
				"DELETE FROM ra_catatan_kegiatan WHERE id_kegiatan=%d", $rowKegiatan['id_kegiatan']);
		$resultHapusCatatan = mysqli_query($mysqli, $queryHapusCatatan);
		if ($resultHapusCatatan == null) {
			$querySuccess = false; break;
		}
		// Hapus record utama
		$queryHapusKegiatan = sprintf(
				"DELETE FROM ra_kegiatan WHERE id_kegiatan=%d", $rowKegiatan['id_kegiatan']);
		$resultHapusKegiatan = mysqli_query($mysqli, $queryHapusKegiatan);
		if ($resultHapusKegiatan == null) {
			$querySuccess = false; break;
		}
	}
	
	if ($querySuccess) {
		mysqli_commit($mysqli);
		echo json_encode(array(
				'status' => 'ok',
				'agenda_deleted' => $agendaDeleted
		));
	} else {
		mysqli_rollback($mysqli);
		echo json_encode(array(
				'status' => 'error',
				'error' => "Terjadi kesalahan internal. Error: ".mysqli_error($mysqli)
		));
	}