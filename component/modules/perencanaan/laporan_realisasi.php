<?php
/*
 * laporan_realisasi.php
 * ==> Menampilkan rekapitulasi rencana dan realisasi kegiatan pada satu tahun
 *
 * AM_SIZ_RA_REPORT | Laporan realisasi tahunan
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) return;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$tahunDokumen	= $_GET['th'];
	
	if (empty($tahunDokumen)) $tahunDokumen = date("Y");
	$SIZPageTitle	= "Realisasi tahun ".$tahunDokumen;
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Rekap Realisasi Tahun ".$tahunDokumen,
			ra_gen_url("realisasi", $tahunDokumen),true);
	
	// Generate layout
	ra_print_status($namaDivisiUser);
?>
<div class="col-md-10">
	<div class="panel panel-default">
	<div class="panel-heading"><h3 class="panel-title">
		<span class="glyphicon glyphicon-stats"></span>
			Laporan Realisasi tahun <?php echo $tahunDokumen; ?></h3></div>
	<div class="panel-body">
		<div class="row">
		  <div class="col-md-12">
		  	<form action="main.php" method="get" class="form-inline">
		  		<input type="hidden" name="s" value="perencanaan" />
		  		<input type="hidden" name="action" value="realisasi" />
		  		<label>Pilih tahun:</label>&nbsp;<select name='th' class="form-control" required>
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
		  		</select>
		  		
		  		<button type="submit" class="btn btn-default">Lihat</button>
		  	</form>
		  	
		  	<table class="table table-bordered table-hover">
		  		<thead>
		  			<tr>
		  				<th>Bulan</th><th>Perencanaan</th><th>Realisasi</th>
		  				<th>Selisih</th><th>Aksi</th>
		  			</tr>
		  		</thead>
		  		<tbody>
<?php
	$jmlPerencanaan = 0;
	$jmlRealisasi = 0;
	$bulanRekap = 0;
	for ($bulanRekap=1; $bulanRekap<=12; $bulanRekap++) {
		$totPerencanaan = 0;
		$totRealisasi = 0;
		
		$detailUrl = ra_gen_url("realisasi-bulan", $tahunDokumen, "bln=".$bulanRekap);
		echo "<tr><td><a href=\"".htmlspecialchars($detailUrl)."\">";
		echo "<span class='glyphicon glyphicon-calendar'></span>&nbsp;";
		echo "<b>".$monthName[$bulanRekap]."</b></a></td>";
		
		// Query rekap perencanaan
		$queryRekapPerencanaan = sprintf(
				"SELECT SUM(jumlah_anggaran) AS jumlah_anggaran FROM ra_agenda ".
				"WHERE MONTH(tgl_mulai)=%d AND YEAR(tgl_mulai)=%d",
				$bulanRekap, $tahunDokumen
		);
		$resultQueryRekap = mysqli_query($mysqli, $queryRekapPerencanaan);
		$queryCount++;
		
		if (!$resultQueryRekap) {
			echo "<td>!! Query error !!</td>";
		} else {
			$itemRekap = mysqli_fetch_assoc($resultQueryRekap);
			$totPerencanaan = $itemRekap['jumlah_anggaran'];
			echo "<td>".to_rupiah($totPerencanaan)."</td>";
			$jmlPerencanaan += $totPerencanaan;
		}
		
		// Query rekap realisasi
		/* $queryRealisasi = "SELECT SUM(jumlah) AS jumlah FROM penyaluran
			WHERE id_akun IN (
				SELECT DISTINCT k.akun_pengeluaran FROM ra_agenda AS a, ra_kegiatan AS k
			        WHERE MONTH(a.tgl_mulai)=".$bulanRekap." AND YEAR(a.tgl_mulai)=".$tahunDokumen."
 					AND a.id_kegiatan=k.id_kegiatan
			) AND MONTH(tanggal)=".$bulanRekap." AND YEAR(tanggal)=".$tahunDokumen; */
		$queryRealisasi = "SELECT SUM(jumlah) AS jumlah FROM penyaluran
			WHERE MONTH(tanggal)=".$bulanRekap." AND YEAR(tanggal)=".$tahunDokumen;
		
		$resultQueryRealisasi = mysqli_query($mysqli, $queryRealisasi);
		$queryCount++;

		if (!$resultQueryRealisasi) {
			echo "<td>!! Query error !!</td>";
		} else {
			$rowBulanRealisasi = mysqli_fetch_assoc($resultQueryRealisasi);
			$totRealisasi = $rowBulanRealisasi['jumlah'];
			echo "<td>".to_rupiah($totRealisasi)."</td>";
			$jmlRealisasi += $totRealisasi;
		}
		
		$selisihDana = abs($totPerencanaan-$totRealisasi);
		if ($totPerencanaan >= $totRealisasi) {
			$diffStatus = "<div style='color:#1F7044;'><span class='glyphicon glyphicon-plus-sign'></span> ".to_rupiah($selisihDana)."</div>";
		} else {
			$diffStatus = "<div style='color:#f00;'><span class='glyphicon glyphicon-minus-sign'></span> ".to_rupiah($selisihDana)."</div>";
		}
		echo "<td>{$diffStatus}</td>";
		echo "<td><a href=\"".htmlspecialchars($detailUrl)."\">";
		echo "	<span class='glyphicon glyphicon-zoom-in'></span> Detil</a></td>";
		echo "</tr>\n";
	}
?>
		  		</tbody>
		  		<tfoot>
		  			<tr>
		  			<td><b>Jumlah</b></td>
		  			<td><b><?php echo to_rupiah($jmlPerencanaan); ?></b></td>
					<td><b><?php echo to_rupiah($jmlRealisasi); ?></b></td>
					<td><b><?php
					$selisihDana = abs($jmlPerencanaan-$jmlRealisasi);
					if ($jmlPerencanaan >= $jmlRealisasi) {
						echo "<div style='color:#1F7044;'><span class='glyphicon glyphicon-plus-sign'></span> ".to_rupiah($selisihDana)."</div>";
					} else {
						echo "<div style='color:#f00;'><span class='glyphicon glyphicon-minus-sign'></span> ".to_rupiah($selisihDana)."</div>";
					}
					?></b></td>
					<td>&nbsp;</td>
				</tr>
		  		</tfoot>
		  	</table>
		  </div>
		 </div>
		</div>
	</div>
</div>