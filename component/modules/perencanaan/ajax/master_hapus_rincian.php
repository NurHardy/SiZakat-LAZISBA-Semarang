<?php
/*
 * agenda_hapus_rincian.php
 * ==> AJAX server untuk menghapus rincian awal kegiatan
 *
 * Digunakan pada :
 * o AM_SIZ_RA_DTLMASTKGT | Detil Master Kegiatan
 */

	// Var inits
	$errorDesc		= null;
	$dataRincian	= null;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	// POST inputs
	$idRincian		= intval($_POST['idr']);
	$idKegiatan		= -1;
	
	// Cek input
	if (empty($idRincian)) {
		$errorDesc = "Parameter tidak lengkap.";
	}
	
	if ($errorDesc == null) {
		// Cek rincian agenda
		$queryCekRincian = sprintf(
				"SELECT * FROM ra_rincian_awal WHERE id_rincian=%d LIMIT 1",
				$idRincian);
		$resultCekRincian = mysqli_query($mysqli, $queryCekRincian);
		
		if ($resultCekRincian != null) {
			$dataRincian	= mysqli_fetch_array($resultCekRincian);
		} else {
			$errorDesc = "Query tidak berhasil: ".mysqli_error($mysqli);
		}
		
		if ($dataRincian != null) {
			$idKegiatan = $dataRincian['id_kegiatan'];
			// Cek kegiatan rincian
			$queryCekKegiatan = sprintf(
					"SELECT * FROM ra_kegiatan AS k WHERE k.id_kegiatan=%d",
					$idKegiatan);
			$resultCekKegiatan = mysqli_query($mysqli, $queryCekKegiatan);
				
			if ($resultCekKegiatan != null) {
				if (mysqli_num_rows($resultCekKegiatan) == 0) {
					$errorDesc = "Data kegiatan tidak ditemukan.";
				} else {
					$dataKegiatan		= mysqli_fetch_array($resultCekKegiatan);
					if (!$isAdmin && ($dataKegiatan['divisi']!=$divisiUser)) {
						$errorDesc = "Akses tidak diperbolehkan.";
					}
				}
			} else {
				$errorDesc = "Query tidak berhasil: ".mysqli_error($mysqli);
			}
		} else {
			$errorDesc = "Rincian tidak ditemukan dalam database.";
		}
	}
	
	// Laksanakan query dan keluarkan output
	if ($errorDesc == null) {
		$queryHapus  = sprintf(
			"DELETE FROM ra_rincian_agenda WHERE id_rincian=%d", $idRincian
		);
		
		$resultSimpan = (IS_DEBUGGING?true:mysqli_query($mysqli, $queryHapus));
		if ($resultSimpan) {
			$queryJumlahRinc = sprintf(
					"SELECT SUM(jumlah_anggaran) AS ja FROM ra_rincian_awal WHERE id_kegiatan=%d",
					$idKegiatan
			);
			$resultJumlah = mysqli_query($mysqli, $queryJumlahRinc);
			$dataJumlahRinc = mysqli_fetch_array($resultJumlah);
			$jumlahBaru = $dataJumlahRinc['ja'];
			
			echo json_encode(array(
					'status'		=> 'ok',
					'id_k'			=> $idKegiatan,
					'old_row_id'	=> $idRincian,
					't_kegiatan'		=> to_rupiah($jumlahBaru)
			));
		} else {
			echo json_encode(array(
					'status' => 'error',
					'error' => "Query error: ".mysqli_error($mysqli)
			));
		}
		
	} else {
		echo json_encode(array(
				'status' => 'error',
				'error' => $errorDesc
		));
	}