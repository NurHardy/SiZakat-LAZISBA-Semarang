<?php

$actionWord = $_GET['action'];

if ($actionWord=="edit-out") { 
	
} else if ($actionWord=="edit-in") { // Edit transaksi penerimaan
	$isEditing = true;
	include COMPONENT_PATH."/file/transaksi_harian/form_penerimaan.php";
} else { // Default: Lihat mutasi transaksi untuk bulan dan tahun tertentu
	include COMPONENT_PATH."/file/transaksi_harian/mutasi_transaksi.php";
}
