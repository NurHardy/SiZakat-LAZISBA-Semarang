<?php
/*
 *	Modul Perencanaan SiZakat LAZISBA
 *	By Muhammad Nur Hardyanto | Informatika UNDIP
 *	Contact: nurhardyanto@if.undip.ac.id
 */

	$breadCrumbPath[] = array("Perencanaan","main.php?s=perencanaan",false);

	// Inisialisasi modul
	define("MODULE_NAME"	, "perencanaan");
	define("RA_AJAX_URL"	, "main.php?s=ajax&m=".MODULE_NAME);
	define("RA_MODULE_URL"	, "main.php?s=".MODULE_NAME);
	
	define("RA_ID_ADMIN"	, 99);
	
	$monthName = array(
					"Bulan","Januari","Februari","Maret","April","Mei","Juni",
					"Juli","Agustus","September","Oktober","November","Desember"
				);
	$listValidDivisi = array(1,2,3,4,5);
	$listDivisi = array("Amilin",
					"Keuangan",
					"Kantor",
					"Marketing",
					"Campaign",
					"Program",
					RA_ID_ADMIN => "Administrator Perencanaan"
				);
	$listPrioritas = array(
		0 => "-",
		1 => "Fix",
		2 => "Penjelasan",
		3 => "Tentatif",
	);
	$listPrioritasHTML = array(
		0 => "-",
		1 => "<span class=\"label label-default\">Fix</span>",
		2 => "<span class=\"label label-warning\">Penjelasan</span>",
		3 => "<span class=\"label label-success\">Tentatif</span>",
	);
	$listJenisKegiatan = array(
		0 => "Biasa/Eksklusif",
		1 => "Rutin Tahunan"
	);
	
	require_once(MODULE_NAME."/helper_perencanaan.php");
	$namaDivisiUser = ( isset( $listDivisi[$_SESSION['siz_divisi']]) ?
		$listDivisi[$_SESSION['siz_divisi']]:
		"- UNKNOWN -"
	);
	
	// == Routing berdasarkan aksi
	$actionWord = @strtolower($_GET['action']);
	if ($actionWord == "ajax") {
		include MODULE_NAME."/ajax.php";
	
	/************* Dokumen Perencanaan ****************/
	} else if ($actionWord == "document") {
		include MODULE_NAME."/dokumen_perencanaan.php";
	} else if ($actionWord == "rekap") {
		include MODULE_NAME."/rekap_tahunan.php";
	} else if ($actionWord == "timeline") {
		include MODULE_NAME."/timeline_perencanaan.php";
	} else if ($actionWord == "tambah-dokumen") {
		include MODULE_NAME."/simpan_dokumen_form.php";
	} else if ($actionWord == "edit-catatan-dokumen") {
		include MODULE_NAME."/simpan_dokumen_form.php";
	} else if ($actionWord == "export") {
		$fType = $_GET['type'];
		if ($fType == 'xlsx') {
			include MODULE_NAME."/export_loader.php";
		} else {
			show_error_page("Format kurang/belum didukung.");
		}
	} else if ($actionWord == "hapus-dokumen") {
		include MODULE_NAME."/hapus_dokumen_confirm.php";
		
	//------------ Laporan Realisasi -------------------
	} else if ($actionWord == "realisasi") {
		include MODULE_NAME."/laporan_realisasi.php";
	} else if ($actionWord == "realisasi-bulan") {
		include MODULE_NAME."/laporan_realisasi_detil.php";
	
	/************* Kegiatan dan Agenda ****************/
	// CRUD kegiatan
	} else if ($actionWord == "kegiatan") {
		include MODULE_NAME."/detil_kegiatan.php";
	} else if ($actionWord == "tambah-kegiatan") {
		include MODULE_NAME."/simpan_kegiatan_form.php";
		
	// CRUD agenda
	} else if ($actionWord == "edit-agenda") {
		include MODULE_NAME."/simpan_agenda_form.php";
	} else if ($actionWord == "tambah-agenda") {
		include MODULE_NAME."/simpan_agenda_form.php";
	// Hapus agenda kegiatan dalam dokumen perencanaan
	} else if ($actionWord == "hapus-agenda-kegiatan") {
		include MODULE_NAME."/hapus_agenda_confirm.php";
	} else if ($actionWord == "hapus-agenda") {
		include MODULE_NAME."/hapus_agenda_confirm.php";
	// CRUD rincian agenda
	/*} else if ($actionWord == "edit-rincian-agenda") {
		$tahunDokumen	= $_GET['th'];
		$idAgenda		= intval($_GET['id']);
		if (empty($idAgenda)||empty($tahunDokumen)) {
			show_error_page("Argumen tidak lengkap.");
		} else {
			include MODULE_NAME."/simpan_rincian_agenda.php";
		}
	*/
	/************* Master Kegiatan *********************/
	} else if ($actionWord == "list") {
		include MODULE_NAME."/list_kegiatan.php";
	} else if ($actionWord == "tambah-kegiatan-master") {
		include MODULE_NAME."/simpan_kegiatan_master_form.php";
	} else if ($actionWord == "edit-kegiatan-master") {
		include MODULE_NAME."/simpan_kegiatan_master_form.php";
	} else if ($actionWord == "master-kegiatan") {
		include MODULE_NAME."/detil_kegiatan_master.php";
	} else if ($actionWord == "hapus-master-kegiatan") {
		include MODULE_NAME."/hapus_kegiatan_confirm.php";
	/****************** User *********************/
	} else if ($actionWord == "user") {
		include MODULE_NAME."/atur_user.php";
	
	/****************** Home *********************/
	} else {
		include MODULE_NAME."/home.php";
	}