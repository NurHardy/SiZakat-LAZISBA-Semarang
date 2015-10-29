<?php
/*
 * hapus_record_stg.php
 * ==> AJAX server untuk menghapus satu atau banyak record stage penerimaan/pengeluaran
 *
 * Digunakan pada :
 * o AM_SIZ_RA_CONFHPSAGENDA | Konfirmasi Hapus Agenda
 */

// TODO: Selesaikan code hapus record!!!

	require_once COMPONENT_PATH."\libraries\querybuilder.php";
	
	// Dapatkan stage dan list id-stage yang akan dihapus
	$doProcess = $_POST['do'];
	$listIdStage = $_POST['id'];
	$tblName = "";
	
	if ($doProcess == "penerimaan") {
		$tblName = "stage_penerimaan";
	} else if ($doProcess == "pengeluaran") {
		$tblName = "stage_pengeluaran";
	} else {
		echo json_encode(array(
			'status' => 'error',
			'error' => "Stage type expected!"
		));
		return;
	}
	if (!is_array($listIdStage)) {
		if ($listIdStage($listIdStage)) {
			$listIdStage = array($listIdStage);
		} else {
			$listIdStage = array();
		}
	}
	if (!check_array_id($listIdStage)) {
		echo json_encode(array(
			'status' => 'error',
			'error' => "Format data tidak valid!"
		));
		return;
	}
	if (empty($listIdStage)) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Argumen tidak lengkap."
		));
		return;
	}
	$listIdAgendaQuery = implode(',', $listIdStage);
	$listIdAgendaQuery = trim($listIdAgendaQuery, ','); // Hapus koma terakhir
	
	$queryGetAgenda = sprintf(
			"SELECT a.*, k.nama_kegiatan, k.divisi FROM ra_agenda AS a, ra_kegiatan AS k ".
			"WHERE k.id_kegiatan=a.id_kegiatan AND (a.id_agenda IN (%s))",
			$listIdAgendaQuery);
	$resultGetAgenda = mysqli_query($mysqli, $queryGetAgenda);
	if ($resultGetAgenda == null) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Terjadi kesalahan internal: ".mysqli_error($mysqli)
		));
		return;
	}
	
	// Pengecekan tiap item agenda
	$listAgenda = array();
	$idx = 0;
	$isAuthorized = true;
	while ($rowAgenda = mysqli_fetch_array($resultGetAgenda)) {
		if (!$isAdmin) {
			if ($rowAgenda['divisi'] != $divisiUser) {
				$isAuthorized = false;
				break;
			}
		}
		$listAgenda[$idx] = $rowAgenda;
		$idx++;
	}
	if (!$isAuthorized) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Terdapat item agenda yang tidak berhak Anda hapus."
		));
		return;
	}
	
	// Proses penghapusan...
	mysqli_autocommit($mysqli, false);
	$querySuccess = true;
	$itemDeleted = 0;
	foreach ($listAgenda as $itemAgenda) {
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
	if ($querySuccess) {
		mysqli_commit($mysqli);
		echo json_encode(array(
				'status' => 'ok',
				'item_deleted' => $itemDeleted
		));
	} else {
		mysqli_rollback($mysqli);
		echo json_encode(array(
				'status' => 'error',
				'error' => "Terjadi kesalahan internal. Error: ".mysqli_error($mysqli)
		));
	}