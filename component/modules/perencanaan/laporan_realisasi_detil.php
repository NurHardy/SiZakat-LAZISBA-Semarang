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
	
	$queryGetAkun	= sprintf(
			"SELECT k.*, a.* FROM ra_kegiatan AS k, akun AS a ".
			"WHERE k.akun_pengeluaran=a.kode GROUP BY k.akun_pengeluaran"
	);
	$resultQueryGetAkun = mysqli_query($mysqli, $queryGetAkun);
	// Generate layout
	ra_print_status($namaDivisiUser);
?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">
			<span class="glyphicon glyphicon-stats"></span>
				Laporan Realisasi bulan <?php echo $tahunDokumen; ?></h3></div>
		<div class="panel-body">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>No.</th><th>Akun</th><th>Jumlah</th>
					</tr>
				</thead>
				<tbody>
<?php
	$rowCounter = 0;
	while ($rowAkun = mysqli_fetch_assoc($resultQueryGetAkun)) {
		$rowCounter++;
		echo "<tr><td>{$rowCounter}</td><td><b>{$rowAkun['kode']}</b> {$rowAkun['namaakun']}</td><td>0</td></tr>\n";
	}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>