<?php

$ajaxAct = $_POST['act'];
$errorDesc = null;

require_once COMPONENT_PATH."/libraries/helper_saldo.php";

if ($ajaxAct == "get.dashboard.html") {
	require COMPONENT_PATH."/file/transaksi_harian/dashboard.php";
} else if ($ajaxAct == "penerimaan.add") {
	require COMPONENT_PATH."/file/transaksi_harian/functions/simpan_penerimaan.php";
	
	if (!empty($processError)) {
		echo json_encode(array(
				'status'	=> 'error',
				'error'		=> $processError
		));
	} else {
		require_once COMPONENT_PATH."/file/transaksi_harian/helper_transaksi.php";
		//$tableHtml = generate_latest_trx_penerimaan();
		echo json_encode(array(
				'status'	=> 'ok',
				'id'		=> $idTrx,
				//'html'		=> $tableHtml
		));
	}
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
	
//============ BANK LIST SUGGESTION
} else if ($ajaxAct == "get.bank.suggest") {
	$jsonResult = array();
	if (isset($_POST['q'])) {
		$queryTerm = $_POST['q'];
		$queryTerm = mysqli_real_escape_string($mysqli, $queryTerm);
		$getQuery = ("SELECT * FROM bank WHERE (bank LIKE '%".$queryTerm."%')");
		$resultQuery = mysqli_query($mysqli, $getQuery);
	
		while ($rowBank = mysqli_fetch_assoc($resultQuery)) {
			$jsonResult[] = array(
					'id' => $rowBank['id_bank'],
					'text' => $rowBank['bank'],
					'rek' => $rowBank['no_rekening'],
					'logo' => $rowBank['logo']
			);
		}
	}
	echo json_encode($jsonResult);
//============ IMPOR TRANSAKSI ==========
} else if ($ajaxAct == "get.stagepenerimaan.form") {
	require COMPONENT_PATH."/file/transaksi_harian/import/form_stg_penerimaan.php";
} else if ($ajaxAct == "get.stagepengeluaran.form") {
	require COMPONENT_PATH."/file/transaksi_harian/import/form_stg_pengeluaran.php";
} else if ($ajaxAct == "set.stagepenerimaan") {
	require COMPONENT_PATH."/file/transaksi_harian/import/simpan_stg_penerimaan.php";
} else if ($ajaxAct == "set.stagepengeluaran") {
	require COMPONENT_PATH."/file/transaksi_harian/import/simpan_stg_pengeluaran.php";
} else if ($ajaxAct == "map.stagepenerimaan") {
	require COMPONENT_PATH."/file/transaksi_harian/import/map_stg_penerimaan.php";
} else if ($ajaxAct == "map.stagepengeluaran") {
	require COMPONENT_PATH."/file/transaksi_harian/import/map_stg_pengeluaran.php";
} else if ($ajaxAct == "flush.stage") {
	require COMPONENT_PATH."/file/transaksi_harian/import/load_core.php";
} else {
	echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized action.'
	));
}