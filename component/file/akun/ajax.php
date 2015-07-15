<?php
$ajaxAct = $_POST ['act'];
$errorDesc = null;

// ============ FORM TAMBAH SUMBERDANA
if ($ajaxAct == "get.form.tambahsumber") {
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