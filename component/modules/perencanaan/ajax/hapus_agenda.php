<?php
/*
 * hapus_agenda.php
 * ==> AJAX server untuk menghapus agenda
 *
 * Digunakan pada :
 * o AM_SIZ_RA_CONFHPSAGENDA | Konfirmasi Hapus Agenda
 */

	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	
	// Dapatkan list id-agenda yang akan dihapus
	$listIdAgenda = $_POST['id'];
	
	if (!is_array($listIdAgenda)) {
		if (is_numeric($listIdAgenda)) {
			$listIdAgenda = array($listIdAgenda);
		} else {
			$listIdAgenda = array();
		}
	}
	if (!check_array_id($listIdAgenda)) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Format data tidak valid!"
		));
		return;
	}
	if (empty($listIdAgenda)) {
		echo json_encode(array(
				'status' => 'error',
				'error' => "Argumen tidak lengkap."
		));
		return;
	}
	$listIdAgendaQuery = implode(',', $listIdAgenda);
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