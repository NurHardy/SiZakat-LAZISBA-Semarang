<?php

// Global consts
define("RA_MODULE_URL"	, "main.php?s=perencanaan");

// Action param
$ajaxAct = $_POST['act'];
$errorDesc = null;

require_once(COMPONENT_PATH."/modules/perencanaan/helper_perencanaan.php");

if ($ajaxAct == "get.rincian") {
	$idAgenda = intval($_POST['id']);
	$queryRincian = sprintf("SELECT * FROM ra_rincian_agenda WHERE id_agenda=%d",$idAgenda);
	$resultRincian = mysqli_query($mysqli, $queryRincian);
	$jsonResult = array();
	while ($rowRincian = mysqli_fetch_array($resultRincian)) {
		$jsonResult[] = array(
			'id'			=> $rowRincian['id_rincian'],
			'nama'			=> $rowRincian['nama_rincian'],
			'jumlah'		=> intval($rowRincian['jumlah_anggaran']),
			'txt_jumlah'	=> to_rupiah($rowRincian['jumlah_anggaran'])
		);
	}
	echo json_encode(array(
		'status' => 'ok',
		'length' => count($jsonResult),
		'data'	 => $jsonResult
	));

//============ HAPUS AGENDA
} else if ($ajaxAct == "agenda.hapus") {
	require("ajax/hapus_agenda.php");
	
//============ RINCIAN
} else if ($ajaxAct == "agenda.rincian.add") {
	require("ajax/agenda_simpan_rincian.php");
} else if ($ajaxAct == "agenda.rincian.edit") {
	require("ajax/agenda_simpan_rincian.php");
} else if ($ajaxAct == "agenda.rincian.delete") {
	require("ajax/agenda_hapus_rincian.php");
	
//============ MASTER KEGIATAN
} else if ($ajaxAct == "mastkegiatan.rincian.add") {
	require("ajax/master_simpan_rincian.php");
} else if ($ajaxAct == "mastkegiatan.rincian.edit") {
	require("ajax/master_simpan_rincian.php");
} else if ($ajaxAct == "mastkegiatan.rincian.delete") {
	require("ajax/master_hapus_rincian.php");
} else if ($ajaxAct == "masterkgt.quickdelete") {
	require("ajax/hapus_master_kegiatan.php");
} else if ($ajaxAct == "masterkgt.delete") {
	$needConfirm = false;
	require("ajax/hapus_master_kegiatan.php");
//=========== HAPUS DOKUMEN
} else if ($ajaxAct == "dokumen.delete") {
	require("ajax/dokumen_hapus.php");
	
//=========== EKSPOR DOKUMEN
} else if ($ajaxAct == "export.perencanaan.dokumen") {
	$fileType = $_POST['type'];
	$fileType = strtolower($fileType);
	if ($fileType == "xlsx") {
		require("ajax/export_dokumen_perencanaan_xlsx.php");
	} else {
		echo json_encode(array(
				'status' => 'error',
				'error'	=> 'Unrecognized export format.'
		));
	}

//=========== REALISASI
} else if ($ajaxAct == "realisasi.getdata") {
	// Get realisaasi
	require("ajax/realisasi_getdata.php");	
	
//=========== USER
} else if ($ajaxAct == "user.setpriv") {
	require("ajax/user_setprivilege.php");
	
} else {
	echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized action.'
	));
}