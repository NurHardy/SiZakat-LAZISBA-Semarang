<?php
$ajaxAct = $_POST ['act'];
$errorDesc = null;

// ============ GET LIST AKUN =========
if ($ajaxAct == "get.akun.penerimaan") {
	$jsonResult = array();
	if (isset($_POST['q'])) {
		$queryTerm = $_POST['q'];
		$queryTerm = mysqli_real_escape_string($mysqli, $queryTerm);
		$getQuery = ("SELECT * FROM akun WHERE (jenis=1) AND (namaakun LIKE '%".$queryTerm."%')");
		$resultQuery = mysqli_query($mysqli, $getQuery);
	
		while ($rowAkun = mysqli_fetch_assoc($resultQuery)) {
			$jsonResult[] = array(
					'id' => $rowAkun['kode'],
					'text' => $rowAkun['namaakun'],
					'id_akun' => $rowAkun['idakun'],
					'desc' => $rowAkun['keterangan']
			);
		}
	}
	echo json_encode($jsonResult);
} else if ($ajaxAct == "get.akun.pengeluaran") {
	$jsonResult = array();
	if (isset($_POST['q'])) {
		$queryTerm = $_POST['q'];
		$queryTerm = mysqli_real_escape_string($mysqli, $queryTerm);
		$getQuery = ("SELECT * FROM akun WHERE (jenis=2) AND (namaakun LIKE '%".$queryTerm."%')");
		$resultQuery = mysqli_query($mysqli, $getQuery);
	
		while ($rowAkun = mysqli_fetch_assoc($resultQuery)) {
			$jsonResult[] = array(
					'id' => $rowAkun['idakun'],
					'text' => $rowAkun['namaakun'],
					'code' => $rowAkun['kode'],
					'desc' => $rowAkun['keterangan']
			);
		}
	}
	echo json_encode($jsonResult);
// ============ FORM TAMBAH SUMBERDANA
} else if ($ajaxAct == "get.form.tambahsumber") {
	require COMPONENT_PATH."\\file\\akun\\akun_tambahsumberdana_form.php";
	
// ============ HAPUS
} else if ($ajaxAct == "sumberdana.hapus") {
	require COMPONENT_PATH."\\file\\akun\\akun_hapussumberdana.php";
	
// ============ TAMBAH / EDIT
} else if ($ajaxAct == "sumberdana.simpan") {
	require COMPONENT_PATH."\\file\\akun\\akun_simpansumberdana.php";
		
} else {
	echo json_encode ( array (
			'status' => 'error',
			'error' => 'Unrecognized action.' 
	) );
}