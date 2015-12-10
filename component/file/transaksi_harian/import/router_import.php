<?php

define('IMPORT_MODULE_VER', 'v0.5 (19 September 2015)');
$breadCrumbPath[] = array("Impor","main.php?s=transaksi&action=import",false);

function import_submodule_printinfo() {
	echo "<div class='well well-sm'>";
	echo "<b>Transaction import module ".IMPORT_MODULE_VER."</b> | \n";
	echo "<a href=\"main.php?s=transaksi&amp;action=import&amp;proc=doc\">
			<span class='glyphicon glyphicon-book'></span>&nbsp;Dokumentasi</a>";
	echo "</div>\n";
}

$actionWord = $_GET['proc'];

//================ DOKUMENTASI ===========
if ($actionWord=="doc") {
	include COMPONENT_PATH."/file/transaksi_harian/import/documentation.php";

//================ IMPORT ===========
} else if ($actionWord=="load") {
	include COMPONENT_PATH."/file/transaksi_harian/import/load_loader.php";
} else if ($actionWord=="penerimaan") {
	include COMPONENT_PATH."/file/transaksi_harian/import/stage_penerimaan.php";
} else if ($actionWord=="pengeluaran") {
	include COMPONENT_PATH."/file/transaksi_harian/import/stage_pengeluaran.php";
} else if ($actionWord=="mapping-penerimaan") {
	include COMPONENT_PATH."/file/transaksi_harian/import/mapping_penerimaan.php";
} else if ($actionWord=="mapping-pengeluaran") {
	include COMPONENT_PATH."/file/transaksi_harian/import/mapping_pengeluaran.php";
//================ HALAMAN DEFAULT ===========
} else {
	include COMPONENT_PATH."/file/transaksi_harian/import/import_transaksi.php";
}