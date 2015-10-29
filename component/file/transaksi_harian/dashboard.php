<?php 
/*
 * dashboard.php
 * ==> AJAX Handler untuk dashboard
 *
 * part of AM_SIZ_USER_HOME | Tampilan home
 * ------------------------------------------------------------------------
 */

	require_once COMPONENT_PATH.'/libraries/querybuilder.php';
	
	//============ Proses mengambil rekap pemasukan dan pengeluaran tahunan
	$tahunSekarang = intval(date("Y"));
	$bulanSekarang = intval(date("m"));
	update_cache($tahunSekarang, 1); // Update cache saldo tahun ini dulu...
	
	$queryRekapTahun = sprintf(
			"SELECT SUM(penerimaan) AS masuk, SUM(pengeluaran) AS keluar ".
			"FROM cache_saldo WHERE tahun=%d", $tahunSekarang);
	$resultRekapTahun = mysqli_query($mysqli, $queryRekapTahun);
	$dataRekapTahun = mysqli_fetch_assoc($resultRekapTahun);
	
	$jumlahMasukTahun = $dataRekapTahun['masuk'];
	$jumlahKeluarTahun = $dataRekapTahun['keluar'];
 
	//============ Proses mendapatkan perencanaan anggaran
	$queryPerencanaanTahun = sprintf(
			"SELECT SUM(a.jumlah_anggaran) AS rencana, k.divisi ".
			"FROM ra_agenda AS a, ra_kegiatan AS k ".
			"WHERE a.id_kegiatan=k.id_kegiatan AND YEAR(tgl_mulai)=%d ".
			"GROUP BY k.divisi",
			$tahunSekarang);
	
	$resultPerencanaanTahun = mysqli_query($mysqli, $queryPerencanaanTahun);
	$listDivisi = array("Guest",
			"Keuangan",
			"Kantor",
			"Marketing",
			"Campaign",
			"Program",
			RA_ID_ADMIN => "Administrator Perencanaan"
	);
	
	$jumlahPerencenaan = 0;
	$arrayRekapDivisi = array();
	while ($dataRekapDivisi = mysqli_fetch_assoc($resultPerencanaanTahun)) {
		$arrayRekapDivisi[$dataRekapDivisi['divisi']] = $dataRekapDivisi['rencana'];
		$jumlahPerencenaan += $dataRekapDivisi['rencana'];
	}
	
	//==================== CASH ON HANDS =======
	$nilaiSaldoAwal = querybuilder_getscalar("SELECT SUM(saldo) FROM saldo_awal");
	if ($nilaiSaldoAwal == null) $nilaiSaldoAwal=0;
	$dataRekapSaldo = querybuilder_getscalar(
		"SELECT SUM(penerimaan), SUM(pengeluaran) ".
		"FROM cache_saldo");
	
	$saldoAkhir = 0;
	if ($dataRekapSaldo != null) {
		$jumlahPenerimaan = $dataRekapSaldo[0];
		$jumlahPengeluaran = $dataRekapSaldo[1];
	
		$saldoAkhir = $nilaiSaldoAwal + ($jumlahPenerimaan - $jumlahPengeluaran);
	}
 ?>
 	<div class="row">
		<div class="col-xs-12 center" style="text-align: center;">					
			<ul class="stat-boxes">
				<li class="popover-visits">
					<div class="right" style='width:300px'>
						<strong style='color:#459D1C;'><?php 
							echo to_rupiah($jumlahMasukTahun);
						?></strong>
						Penerimaan Kas tahun <?php echo $tahunSekarang; ?>
					</div>
				</li>
				<li class="popover-users">
					<div class="right" style='width:300px'>
						<strong style='color:#BA1E20;'><?php 
							echo to_rupiah($jumlahKeluarTahun);
						?></strong>
						Pengeluaran/Penyaluran kas tahun <?php echo $tahunSekarang; ?>
					</div>
				</li>
			</ul>
		</div>	
	</div>
	<div class="row">
		<div class="col-xs-12 center" style="text-align: center;">					
			<ul class="stat-boxes">
				<li class="popover-perencanaan">
					<div class="right" style='width:300px'>
						<strong><?php 
							echo to_rupiah($jumlahPerencenaan);
						?></strong>
						Rencana Pengeluaran Tahun <?php echo $tahunSekarang; ?>
					</div>
				</li>
				<li class="popover-tickets">
					<div class="right" style="width:300px;">
						<strong><?php 
							echo to_rupiah($saldoAkhir);
						?></strong>
						Cash-On-Hand
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 center" style="text-align: center;">					
			<ul class="quick-actions">
				<li>
					<a href="#">
						<i class="icon-cal"></i>
						Manage Events
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-shopping-bag"></i>
						Manage Orders
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-database"></i>
						Manage DB
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-people"></i>
						Manage Users
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-lock"></i>
						Security
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-piechart"></i>
						Statistics
					</a>
				</li>
			</ul>
		</div>	
	</div>

	<div id="siz_popover_perencanaan" style="display: none;">
<?php
	foreach ($arrayRekapDivisi as $idDivisi => $itemDivisi) {
		echo "<span class=\"content-big\">".to_rupiah($itemDivisi)."</span>
			<span class=\"content-small\">".$listDivisi[$idDivisi]."</span><br />";
	}
?>
	</div>