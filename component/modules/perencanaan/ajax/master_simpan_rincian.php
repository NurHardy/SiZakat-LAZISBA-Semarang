<?php
/*
 * master_simpan_rincian.php
 * ==> AJAX server untuk menyimpan rincian master
 *
 * Digunakan pada :
 * o AM_SIZ_RA_DTLMASTKGT | Detil master kegiatan
 */

	$errorDesc = null;
	$dataRincian = null;
	$oldRincName = null;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	$paramComplete	= true;
	$idRincian	= intval($_POST['idr']);
	$namaRinc	= trim($_POST['namaRinc']);
	$nilaiRinc	= trim($_POST['nilaiRinc']);
	$idKegiatan	= -1; // Khusus tambah rincian
	
	$isEditing	= ($idRincian > 0);
	
	if (!$isEditing) {
		$idKegiatan	= intval($_POST['idk']);
	}
	// Cek input
	$paramComplete = $paramComplete && !empty($namaRinc) && !empty($nilaiRinc);
	if ($isEditing) {
		$paramComplete &= (!empty($idRincian));
	} else {
		$paramComplete &= ($idKegiatan != -1);
	}
	
	if (!$paramComplete) {
		$errorDesc = "Parameter tidak lengkap.";
	} else {
		if (!is_numeric($nilaiRinc)) {
			$errorDesc = "Nilai rincian harus berupa angka numerik.";
		} else {
			$nilaiRinc = intval($nilaiRinc);
			if ($nilaiRinc <= 0) {
				$errorDesc = "Nilai rincian tidak boleh negatif.";
			}
		}
	}
	
	// Cek rincian agenda merupakan proses edit
	if (($errorDesc == null) && ($isEditing)) {
		
		$queryCekRincian = sprintf(
				"SELECT * FROM ra_rincian_awal WHERE id_rincian=%d LIMIT 1",
				$idRincian);
		$resultCekRincian = mysqli_query($mysqli, $queryCekRincian);
		
		if ($resultCekRincian != null) {
			$dataRincian	= mysqli_fetch_array($resultCekRincian);
			$idKegiatan		= $dataRincian['id_kegiatan'];
			$oldRincName	= $dataRincian['nama_rincian'];
		} else {
			$errorDesc		= "Data rincian master tidak ditemukan dalam database.";
		}
	}
	
	// Cek agenda dan kegiatan
	if ($idKegiatan != -1) {
		$queryCekKegiatan = sprintf(
				"SELECT * FROM ra_kegiatan AS k WHERE k.id_kegiatan=%d",
				$idKegiatan);
		$resultCekKegiatan = mysqli_query($mysqli, $queryCekKegiatan);
			
		if ($resultCekKegiatan != null) {
			if (mysqli_num_rows($resultCekKegiatan) == 0) {
				$errorDesc = "Data kegiatan tidak ditemukan.";
			} else {
				$dataKegiatan	= mysqli_fetch_array($resultCekKegiatan);
				if (!$isAdmin && ($dataKegiatan['divisi']!=$divisiUser)) {
					$errorDesc = "Akses tidak diperbolehkan.";
				}
			}
		} else {
			$errorDesc = "Query tidak berhasil. Error: ".mysqli_error($mysqli);
		}
	}
	
	// Laksanakan query dan keluarkan output
	if ($errorDesc == null) {
		require_once COMPONENT_PATH."/libraries/querybuilder.php";
		$dataSimpan = array(
				'nama_rincian'		=> $namaRinc,
				'jumlah_anggaran'	=> $nilaiRinc
		);
		if ($isEditing) {
			
		} else {
			$dataSimpan['tgl_tambah'] = date('Y-m-d H:i:s');
			$dataSimpan['id_kegiatan'] = $idKegiatan;
			$dataSimpan['id_user'] = $_SESSION['iduser'];
		}
		$setDataSimpan = querybuilder_generate_set($dataSimpan);
		$querySimpan  = ($isEditing?"UPDATE ":"INSERT INTO ");
		$querySimpan .= "ra_rincian_awal SET ".$setDataSimpan." ";
		if ($isEditing) $querySimpan .= "WHERE id_rincian=".$idRincian;
		
		$resultSimpan = (IS_DEBUGGING?true:mysqli_query($mysqli, $querySimpan));
		if ($resultSimpan) {
			$newId = 0;
			if (!$isEditing) {
				$newId = mysqli_insert_id($mysqli);
			}
			
			$queryJumlahRinc = sprintf(
					"SELECT SUM(jumlah_anggaran) AS ja FROM ra_rincian_awal WHERE id_kegiatan=%d",
					$idKegiatan
			);
			$resultJumlah = mysqli_query($mysqli, $queryJumlahRinc);
			$dataJumlahRinc = mysqli_fetch_array($resultJumlah);
			$jumlahBaru = $dataJumlahRinc['ja'];
			
			echo json_encode(array(
					'status'	=> 'ok',
					'new_row'	=> array(
							'id'	=> ($isEditing?$idRincian:$newId),
							'n'		=> htmlspecialchars($namaRinc),
							'v'		=> to_rupiah($nilaiRinc),
							'vn'	=> $nilaiRinc
					),
					'old_name'	=> htmlspecialchars($oldRincName),
					'id_k'		=> $idKegiatan,
					't_kegiatan'=> to_rupiah($jumlahBaru), // Total anggaran Agenda
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