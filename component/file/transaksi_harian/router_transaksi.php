<?php

$actionWord = $_GET['action'];
require_once COMPONENT_PATH."\\libraries\\helper_saldo.php";

//================ EDIT TRANSAKSI ===========
if ($actionWord=="edit-out") { 
	
} else if ($actionWord=="edit-in") { // Edit transaksi penerimaan
	$isEditing = true;
	include COMPONENT_PATH."\\file\\transaksi_harian\\form_penerimaan.php";
	
//================ Import transaksi
} else if ($actionWord=="import") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\import_transaksi.php";
	
} else if ($actionWord=="import-penerimaan") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\import\\stage_penerimaan.php";

} else if ($actionWord=="mapping-penerimaan") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\import\\mapping_penerimaan.php";
	
//================ Mengelola bank ===========
} else if ($actionWord=="bank") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\bank_list.php";
	
} else if ($actionWord=="new-bank") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\bank_form_simpan.php";
	
} else if ($actionWord=="edit-bank") {
	include COMPONENT_PATH."\\file\\transaksi_harian\\bank_form_simpan.php";

} else { // Default: Lihat mutasi transaksi untuk bulan dan tahun tertentu
	//update_cache(2013, 9);
	include COMPONENT_PATH."\\file\\transaksi_harian\\mutasi_transaksi.php";
}
