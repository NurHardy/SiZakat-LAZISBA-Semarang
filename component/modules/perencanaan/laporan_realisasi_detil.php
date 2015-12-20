<?php
/*
 * laporan_realisasi_detil.php
 * ==> Menampilkan rekapitulasi rencana dan realisasi kegiatan per akun untuk satu bulan
 *
 * AM_SIZ_RA_DTLRPT | Laporan realisasi bulanan
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) return;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$tahunDokumen	= $_GET['th'];
	$bulanDokumen	= $_GET['bln'];
	
	if (($bulanDokumen < 1) || ($bulanDokumen > 12)) {
		show_error_page("Bulan rekap tidak valid.");
		return;
	}
	
	$SIZPageTitle	= "Realisasi ".$monthName[$bulanDokumen]." ".$tahunDokumen;
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Rekap Realisasi Tahun ".$tahunDokumen,
			ra_gen_url("realisasi", $tahunDokumen),false);
	$breadCrumbPath[] = array("Bulan ".$monthName[$bulanDokumen],
			ra_gen_url("realisasi-bulan",$tahunDokumen,"bln=".$bulanDokumen),true);

	$backUrl = ra_gen_url("realisasi", $tahunDokumen);
	// Query list akun yang kegiatannya masuk dalam bulan dan tahun rekap atau 
	$queryGetAkun	= sprintf(
			"SELECT lst.*, a.* FROM akun AS a, (".
				"SELECT k.akun_pengeluaran AS kode ".
					"FROM ra_agenda AS a, ra_kegiatan AS k ".
					"WHERE a.id_kegiatan=k.id_kegiatan AND MONTH(a.tgl_mulai)=%d AND YEAR(a.tgl_mulai)=%d ".
					"GROUP BY k.akun_pengeluaran ".
				"UNION SELECT DISTINCT p.id_akun AS kode FROM penyaluran AS p ".
					"WHERE MONTH(p.tanggal)=%d AND YEAR(p.tanggal)=%d ".
			") AS lst WHERE lst.kode=a.kode",
			$bulanDokumen, $tahunDokumen, $bulanDokumen, $tahunDokumen
	);
	$resultQueryGetAkun = mysqli_query($mysqli, $queryGetAkun);
	$queryCount++;
	
	if (!$resultQueryGetAkun) {
		show_error_page("Terjadi kesalahan internal: ".mysqli_error($mysqli));
		return;
	}
	// Generate layout
	ra_print_status($namaDivisiUser);
?>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
var currentMonth = <?php echo intval($bulanDokumen); ?>;
var currentYear = <?php echo intval($tahunDokumen); ?>;

function load_report_detail(elmt, idAccount) {
	_ajax_send({
		act: 'realisasi.getdata',
		bln: currentMonth,
		thn: currentYear,
		akun: idAccount
	}, function(response){
		if (response.status == 'ok') {
			var parentDiv = $(elmt).closest(".siz-akuncontainer");
			var contentDiv = $(parentDiv).find(".parent_content").html(response.html);
			$(contentDiv).addClass("siz-loaded").slideDown(250);
		} else {
			alert(response.error);
		}
	}, "Memuat", AJAX_URL);
	return false;
}
</script>
<div class="col-md-10">
	<!-- ====== Navigation ========= -->
	<div>
		<form class="form-inline" action="main.php#siz-content" method="get">
			<input type="hidden" name="s" value="perencanaan" />
			<input type="hidden" name="action" value="realisasi-bulan" />
			<a href="<?php echo htmlspecialchars($backUrl); ?>">
				<span class="glyphicon glyphicon-chevron-left"></span> Kembali</a> | 
			<label>Tahun:</label>
			<select name="th" class="form-control">
				<option value="">- Pilih Tahun -</option>
<?php 
	$queryGetDoc = "SELECT tahun_dokumen FROM ra_dokumen";
	$resultGetDoc = mysqli_query($mysqli, $queryGetDoc);
	while ($rowDoc = mysqli_fetch_array($resultGetDoc)) {
		$isSelected = ($rowDoc['tahun_dokumen'] == $tahunDokumen);
		echo "<option value=\"".$rowDoc['tahun_dokumen']."\" ";
		echo ($isSelected?"selected":"").">".$rowDoc['tahun_dokumen']."</option>";
	}
?>
			</select> <label>Bulan:</label> <select name="bln" class="form-control">
				<?php
				for ($monthCtr = 1; $monthCtr <= 12; $monthCtr++) {
					$isSelected = ($monthCtr == $bulanDokumen);
					echo "<option value='".$monthCtr."' ";
					echo ($isSelected?"selected":"").">".$monthName[$monthCtr]."</option>";
				}
				?>
			</select>
			<button type="submit" class="btn btn-default">Lihat</button>
		</form>
	</div>
	
	<!-- ======== BEGIN CONTENT ============= -->
	<div class="panel panel-default" id="siz-content">
		<div class="panel-heading"><h3 class="panel-title">
			<span class="glyphicon glyphicon-stats"></span>
				Laporan Realisasi Bulan <?php echo $monthName[$bulanDokumen]." ".$tahunDokumen; ?></h3></div>
		<div class="panel-body" style="min-height:400px;">
<?php
	$rowCounter = 0;
	if (mysqli_num_rows($resultQueryGetAkun) > 0) {
		while ($rowAkun = mysqli_fetch_assoc($resultQueryGetAkun)) {
			$rowCounter++;
			
			$currentKodeAkun = $rowAkun['kode'];
			$intDanaAnggaran = 0;
			$intDanaRealisasi = 0;
			$totalDanaAnggaran = "";
			$totalDanaRealisasi = "";
			
			// Hitung perencanaan
			$queryPerencanaan = sprintf(
					"SELECT k.akun_pengeluaran, lst.id_kegiatan, SUM(lst.jumlah_anggaran) AS jml_rencana ".
					"FROM (".
						"SELECT jumlah_anggaran, id_kegiatan FROM ra_agenda ".
						"WHERE MONTH(tgl_mulai)=%d AND YEAR(tgl_mulai)=%d ".
					") AS lst, ra_kegiatan AS k ".
					"WHERE lst.id_kegiatan=k.id_kegiatan AND k.akun_pengeluaran='%s'",
					$bulanDokumen, $tahunDokumen, $currentKodeAkun
			);
			$resultPerencanaan = mysqli_query($mysqli, $queryPerencanaan);
			$queryCount++;
			
			if (!$resultPerencanaan) {
				$totalDanaAnggaran = "!! Query Error !! ". mysqli_error($mysqli);
			} else {
				$rowSumAnggaran = mysqli_fetch_assoc($resultPerencanaan);
				$intDanaAnggaran = $rowSumAnggaran['jml_rencana'];
				$totalDanaAnggaran = to_rupiah($intDanaAnggaran);
			}
			
			// Hitung transaksi pengeluaran
			$queryRealisasiTrx = sprintf(
					"SELECT SUM(jumlah) AS jml_keluar FROM penyaluran ".
					"WHERE MONTH(tanggal)=%d AND YEAR(tanggal)=%d AND id_akun='%s'",
					$bulanDokumen, $tahunDokumen, $currentKodeAkun
	 		);
			$resultRealisasiTrx = mysqli_query($mysqli, $queryRealisasiTrx);
			$queryCount++;
			
			if (!$resultRealisasiTrx) {
				$totalDanaRealisasi = "!! Query Error !! ". mysqli_error($mysqli);
			} else {
				$rowSumTrx = mysqli_fetch_assoc($resultRealisasiTrx);
				$intDanaRealisasi = $rowSumTrx['jml_keluar'];
				$totalDanaRealisasi = to_rupiah($intDanaRealisasi);
			}
			
?>
	<div class="siz-akuncontainer" id="siz-idakun-<?php echo $rowAkun['idakun']; ?>">
		<div class="parent_title">
			<a href="#" onclick="return load_report_detail(this, '<?php echo $currentKodeAkun; ?>');"
				class="title_link"><span class="glyphicon glyphicon-triangle-right"></span>
				<?php echo "<b>{$currentKodeAkun}</b> {$rowAkun['namaakun']}"; ?></a>
			<div class="siz-akuninfo">
				Perencanaan: <b><?php echo ($totalDanaAnggaran); ?></b> | 
				Realisasi: <b><?php echo ($totalDanaRealisasi); ?></b> |
				Selisih: <b><?php
				$selisihDana = abs($intDanaAnggaran-$intDanaRealisasi);
				if ($intDanaAnggaran >= $intDanaRealisasi) {
					echo "<span style='color:#1F7044;'><span class='glyphicon glyphicon-plus-sign'></span> ".to_rupiah($selisihDana)."</span>";
				} else {
					echo "<span style='color:#f00;'><span class='glyphicon glyphicon-minus-sign'></span> ".to_rupiah($selisihDana)."</span>";
				}
				?></b>
			</div>
		</div>
		<div class="parent_content" style="display:none;">
			
		</div>
	</div>
<?php
		} //============== END WHILE =============
	} else {
		echo "<span class='glyphicon glyphicon-exclamation-sign'></span> ".
 			"Ooops, tidak ada yang bisa ditampilkan.";
	}
?>
		</div>
	</div>
</div>