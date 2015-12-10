<?php
/*
 * agenda_simpan_rincian.php
 * ==> AJAX server untuk menyimpan rincian agenda
 *
 * Digunakan pada :
 * o AM_SIZ_RA_DTLKGT | Detil Kegiatan
 */

	$errorDesc = null;
	$dataRincian = null;
	$oldRincName = null;
	
	$paramComplete	= true;
	$idRincian	= intval($_POST['idr']);
	$namaRinc	= trim($_POST['namaRinc']);
	$nilaiRinc	= trim($_POST['nilaiRinc']);
	$idAgenda	= -1; // Khusus tambah rincian
	
	$isEditing	= ($idRincian > 0);
	
	if (!$isEditing) {
		$idAgenda	= intval($_POST['ida']);
	}
	// Cek input
	$paramComplete = $paramComplete && !empty($namaRinc) && !empty($nilaiRinc);
	if ($isEditing) {
		$paramComplete &= (!empty($idRincian));
	} else {
		$paramComplete &= ($idAgenda != -1);
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
				"SELECT * FROM ra_rincian_agenda WHERE id_rincian=%d LIMIT 1",
				$idRincian);
		$resultCekRincian = mysqli_query($mysqli, $queryCekRincian);
		
		if ($resultCekRincian != null) {
			$dataRincian	= mysqli_fetch_array($resultCekRincian);
			$idAgenda		= $dataRincian['id_agenda'];
			$oldRincName	= $dataRincian['nama_rincian'];
		} else {
			$errorDesc		= "Data rincian tidak ditemukan dalam database.";
		}
	}
	
	// Cek agenda dan kegiatan
	if ($idAgenda != -1) {
		$queryCekKegiatan = sprintf(
				"SELECT * FROM ra_agenda AS a, ra_kegiatan AS k ".
				"WHERE a.id_kegiatan=k.id_kegiatan AND a.id_agenda=%d",
				$idAgenda);
		$resultCekKegiatan = mysqli_query($mysqli, $queryCekKegiatan);
			
		if ($resultCekKegiatan != null) {
			if (mysqli_num_rows($resultCekKegiatan) == 0) {
				$errorDesc = "Data kegiatan atau agenda tidak ditemukan.";
			} else {
				$divisiUser		= $_SESSION['siz_divisi'];
				$isAdmin		= ($divisiUser == 99);
	
				$dataAgenda		= mysqli_fetch_array($resultCekKegiatan);
				if (!$isAdmin && ($dataAgenda['divisi']!=$divisiUser)) {
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
			$dataSimpan['id_agenda'] = $idAgenda;
			$dataSimpan['id_user'] = $_SESSION['iduser'];
		}
		$setDataSimpan = querybuilder_generate_set($dataSimpan);
		$querySimpan  = ($isEditing?"UPDATE ":"INSERT INTO ");
		$querySimpan .= "ra_rincian_agenda SET ".$setDataSimpan." ";
		if ($isEditing) $querySimpan .= "WHERE id_rincian=".$idRincian;
		
		$resultSimpan = (IS_DEBUGGING?true:mysqli_query($mysqli, $querySimpan));
		if ($resultSimpan) {
			$newId = 0;
			if (!$isEditing) {
				$newId = mysqli_insert_id($mysqli);
			}
			
			$jumlahBaru = update_anggaran_agenda($idAgenda);
			
			echo json_encode(array(
					'status'	=> 'ok',
					'new_row'	=> array(
							'id'	=> ($isEditing?$idRincian:$newId),
							'n'		=> htmlspecialchars($namaRinc),
							'v'		=> to_rupiah($nilaiRinc),
							'vn'	=> $nilaiRinc
					),
					'old_name'	=> htmlspecialchars($oldRincName),
					'id_a'		=> $idAgenda,
					't_agenda'	=> to_rupiah($jumlahBaru), // Total anggaran Agenda
					't_bulan'	=> 'Rp. 0', // TODO: Total anggaran bulan
					't_total'	=> 'Rp. 0', // Total anggaran kegiatan
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