<?php

$ajaxAct = $_POST['act'];
$errorDesc = null;

require_once COMPONENT_PATH."\\libraries\\helper_saldo.php";

if ($ajaxAct == "get.dashboard.html") {
	require COMPONENT_PATH."\\file\\transaksi_harian\\dashboard.php";
	
} else if ($ajaxAct == "get.tabel.penerimaan") {
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

//============ IMPOR TRANSAKSI ==========
} else if ($ajaxAct == "get.stagepenerimaan.form") {
	require COMPONENT_PATH."\\file\\transaksi_harian\\import\\form_stg_penerimaan.php";
} else if ($ajaxAct == "get.stagepenerimaan.simpan") {
	require COMPONENT_PATH."\\file\\transaksi_harian\\import\\simpan_stg_penerimaan.php";
	
} else {
	echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized action.'
	));
}