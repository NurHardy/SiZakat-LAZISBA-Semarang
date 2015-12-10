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
	
	$queryRekapPerencanaan = sprintf(
		"SELECT MONTH(tgl_mulai) AS bulan, SUM(jumlah_anggaran) AS jumlah_anggaran ".
		"FROM ra_agenda ".
		"WHERE YEAR(tgl_mulai)=%d ".
		"GROUP BY MONTH(tgl_mulai)",
		$tahunDokumen
	);
	$resultQueryRekap = mysqli_query($mysqli, $queryRekapPerencanaan);
	$queryCount++;
	if ($resultQueryRekap == null) {
		show_error_page( "Terjadi kesalahan query: ".mysqli_error($mysqli) );
		return;
	}
	
	// Generate layout
	ra_print_status($namaDivisiUser);
?>
<div class="col-md-8">
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
		  			</tr>
		  		</thead>
		  		<tbody>
<?php
	$jmlPerencanaan = 0;
	$jmlRealisasi = 0;
	foreach ($resultQueryRekap as $itemRekap) {
		$bulanRekap = intval($itemRekap['bulan']);
		$queryRealisasi = "SELECT SUM(jumlah) AS jumlah FROM penyaluran
			WHERE id_akun IN (
				SELECT DISTINCT k.akun_pengeluaran FROM ra_agenda AS a, ra_kegiatan AS k
			        WHERE MONTH(a.tgl_mulai)=".$bulanRekap." AND YEAR(a.tgl_mulai)=".$tahunDokumen."
 					AND a.id_kegiatan=k.id_kegiatan
			) AND MONTH(tanggal)=".$bulanRekap." AND YEAR(tanggal)=".$tahunDokumen;
		
		$resultQueryRealisasi = mysqli_query($mysqli, $queryRealisasi);
		$queryCount++;
		$rowBulanRealisasi = mysqli_fetch_array($resultQueryRealisasi);
		
		$detailUrl = ra_gen_url("realisasi-bulan", $tahunDokumen, "bln=".$bulanRekap);
		echo "<tr><td><a href=\"".htmlspecialchars($detailUrl)."\">";
		echo "<span class='glyphicon glyphicon-calendar'></span>&nbsp;";
		echo "<b>".$monthName[$bulanRekap]."</b></a></td>";
		echo "<td>".to_rupiah($itemRekap['jumlah_anggaran'])."</td>";
		$jmlPerencanaan += $itemRekap['jumlah_anggaran'];
		if ($resultQueryRealisasi != null) {
			echo "<td>".to_rupiah($rowBulanRealisasi['jumlah'])."</td>";
			$jmlRealisasi += $rowBulanRealisasi['jumlah'];
		} else {
			echo "<td>!! Query error !!</td>";
		}
		echo "</tr>\n";
	}
?>
		  		</tbody>
		  		<tfoot>
		  			<tr>
		  			<td><b>Jumlah</b></td>
		  			<td><b><?php echo to_rupiah($jmlPerencanaan); ?></b></td>
					<td><b><?php echo to_rupiah($jmlRealisasi); ?></b></td>
				</tr>
		  		</tfoot>
		  	</table>
		  </div>
		 </div>
		</div>
	</div>
</div>