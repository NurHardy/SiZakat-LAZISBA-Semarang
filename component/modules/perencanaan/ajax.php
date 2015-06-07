<?php

$ajaxAct = $_POST['act'];
$errorDesc = null;

require_once(COMPONENT_PATH."\modules\perencanaan\helper_perencanaan.php");

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
} else if ($ajaxAct == "agenda.rincian.add") {
	require("ajax/agenda_simpan_rincian.php");
} else if ($ajaxAct == "agenda.rincian.edit") {
	require("ajax/agenda_simpan_rincian.php");
} else if ($ajaxAct == "agenda.rincian.delete") {
	require("ajax/agenda_hapus_rincian.php");
} else if ($ajaxAct == "mastkegiatan.rincian.add") {
	require("ajax/master_simpan_rincian.php");
} else if ($ajaxAct == "mastkegiatan.rincian.edit") {
	require("ajax/master_simpan_rincian.php");
} else if ($ajaxAct == "mastkegiatan.rincian.delete") {
	require("ajax/master_hapus_rincian.php");
} else {
	echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized action!'
	));
}