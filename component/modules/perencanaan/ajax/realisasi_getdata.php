<?php
/*
 * realisasi_getdata.php
 * ==> AJAX server untuk generate HTML data realisasi akun pengeluaran
 *
 * Digunakan pada :
 * o AM_SIZ_RA_DTLRPT | Laporan realisasi bulanan
 */

	// Cek privilege?
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	$htmlOutput = "";
	$errorDesc = null;
	
	// Baca parameter
	$bulanRekap	= intval($_POST['bln']);
	$tahunRekap	= intval($_POST['thn']);
	$akunRekap	= trim($_POST['akun']);
	
	if (empty($bulanRekap) || empty($tahunRekap) || empty($akunRekap)) {
		$errorDesc = "Parameter tidak lengkap.";
	}
	
	$resultPerencanaan = null;
	$resultRealisasi = null;
	if (empty($errorDesc)) {
		// Query list agenda perencanaan
		$queryPerencanaan = sprintf(
				"SELECT a.*, k.* FROM ra_kegiatan AS k, (".
					"SELECT * FROM ra_agenda ".
					"WHERE MONTH(tgl_mulai)=%d AND YEAR(tgl_mulai)=%d".
				") AS a WHERE a.id_kegiatan=k.id_kegiatan AND k.akun_pengeluaran='%s' ".
				"ORDER BY a.tgl_mulai",
				$bulanRekap, $tahunRekap, mysqli_escape_string($mysqli, $akunRekap)
		);
		
		$resultPerencanaan = mysqli_query($mysqli, $queryPerencanaan);
		if (!$resultPerencanaan) {
			$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
		} else {
			$queryRealisasi = sprintf(
						"SELECT p.*, a.*,p.keterangan FROM akun AS a, (".
							"SELECT * FROM penyaluran ".
							"WHERE MONTH(tanggal)=%d AND YEAR(tanggal)=%d AND id_akun='%s'".
						") AS p WHERE p.id_akun=a.kode ORDER BY p.tanggal",
					$bulanRekap, $tahunRekap, mysqli_escape_string($mysqli, $akunRekap)
					);
			$resultRealisasi = mysqli_query($mysqli, $queryRealisasi);
			if (!$resultRealisasi) {
				$errorDesc = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
			}
		}
	}
	
	if ($resultPerencanaan && $resultRealisasi && empty($errorDesc)) {
		$jmlPerencanaan = $jmlRealisasi = 0;
		
		$htmlOutput .= "<table class=\"table table-bordered\">\n";
		$htmlOutput .= "<thead><tr><th style=\"width:50%;\">List Agenda Perencanaan</th>".
			"<th>List Transaksi Pengeluaran</th></tr></thead>\n";
		$htmlOutput .= "<tbody><tr><td>";
		
		//-- List agenda perencanaan
		if (mysqli_num_rows($resultPerencanaan) > 0) {
			while ($rowAgenda = mysqli_fetch_assoc($resultPerencanaan)) {
				$titleLink = ra_gen_url("kegiatan",$tahunRekap,"id=".$rowAgenda['id_kegiatan']);
				$unixTimeTgl = strtotime($rowAgenda['tgl_mulai']);
				$formatIdn = date('j M Y', $unixTimeTgl);
				$tglEvent = date("j",$unixTimeTgl);
				$bulanEventLbl = date('M', $unixTimeTgl);
				$bulanEvent = date('n', $unixTimeTgl);
				$tahunEvent = date('Y', $unixTimeTgl);
				
// 				$htmlOutput .= "<div class=\"new-update clearfix\">\n";
// 				$htmlOutput .= "	<div class=\"update-done\">\n";
// 				$htmlOutput .= "		<a title=\"\" href=\"".htmlspecialchars($titleLink)."\"><strong>";
// 				$htmlOutput .= htmlspecialchars($rowAgenda['nama_kegiatan'])."</strong></a>\n";
// 				$htmlOutput .= "		<span><b>".$listDivisi[$rowAgenda['divisi']]."</b> | ";
// 				$htmlOutput .= to_rupiah($rowAgenda['jumlah_anggaran']);
// 				$htmlOutput .= "</span>\n";
// 				$htmlOutput .= "	</div>\n";
// 				$htmlOutput .= "	<div class=\"update-date\"><span class=\"update-day\">".$tglEvent."</span>".$bulanEventLbl."</div>\n";
// 				$htmlOutput .= "</div>\n";
				
				$htmlOutput .= "<div class=\"siz-rekap-item\">\n";
				$htmlOutput .= "<div><a href=\"".htmlspecialchars($titleLink).
					"\"><b>".$rowAgenda['nama_kegiatan']."</b></a>";
				$htmlOutput .= "<div class='pull-right'>".to_rupiah($rowAgenda['jumlah_anggaran'])."</div>";
				$htmlOutput .= "<div class=\"siz-divsmall\">".
					"<span class='glyphicon glyphicon-calendar'></span> ".
					tanggal_indonesia($formatIdn)."</div>";
				$htmlOutput .= "</div>\n";
				$jmlPerencanaan += intval($rowAgenda['jumlah_anggaran']);
			}
		} else {
			$htmlOutput .= "<div class=\"div-alert\"><span class='glyphicon glyphicon-alert'></span> ".
				"Tidak Ada Agenda Ditemukan.</div>";
		}
		
		//-- List transaksi pengeluaran (Realisasi)
		$htmlOutput .= "</td><td>\n";
		if (mysqli_num_rows($resultRealisasi) > 0) {
			while ($rowTransaksi = mysqli_fetch_assoc($resultRealisasi)) {
				$unixTimeTgl = strtotime($rowTransaksi['tanggal']);
				$formatIdn = date('j M Y', $unixTimeTgl);
				
				$htmlOutput .= "<div class=\"siz-rekap-item\">\n";
				$htmlOutput .= " <div><b>".$rowTransaksi['keterangan']."</b>";
				$htmlOutput .= " <div class='pull-right'>".to_rupiah($rowTransaksi['jumlah'])."</div></div>";
				$htmlOutput .= " <div class=\"siz-divsmall\">".
					"<span class='glyphicon glyphicon-calendar'></span> ".
					tanggal_indonesia($formatIdn)."</div>";
				$htmlOutput .= "</div>\n";
				$jmlRealisasi += intval($rowTransaksi['jumlah']);
			}
		} else {
			$htmlOutput .= "<div class=\"div-alert\"><span class='glyphicon glyphicon-alert'></span> ".
				"Transaksi Tidak Ditemukan.</div>";
		}
		
		$htmlOutput .= "</td></tr>\n";
		$htmlOutput .= "</tbody><tfoot>\n";
		$htmlOutput .= "<tr><td>Jumlah: <b>".to_rupiah($jmlPerencanaan)."</b></td>";
		$htmlOutput .= "<td>Jumlah: <b>".to_rupiah($jmlRealisasi)."</b></td></tr>\n";
		$htmlOutput .= "</tfoot></table>\n";
	}
	if (empty($errorDesc)) {
		echo json_encode(array(
				'status' => 'ok',
				'html'	=> $htmlOutput
		));
	} else {
		echo json_encode(array(
				'status' => 'error',
				'error'	=> $errorDesc
		));
	}
	