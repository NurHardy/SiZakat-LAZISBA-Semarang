<?php

/*
 *	Hitung jumlah penerimaan dari akun $kodeAkunPenerimaan
 *
 */
function hitung_saldo_akun($kodeAkunPenerimaan, $tahunSaldo, $bulanSaldo) {
	global $mysqli;
	
	//saldo awal
	$resultSaldoAwal = mysqli_query(
		$mysqli,
		"SELECT * FROM saldo_awal WHERE id_akun='".$kodeAkunPenerimaan."'"
	);
	$rowSaldo = mysqli_fetch_array($resultSaldoAwal);
	$saldoAwal = ($rowSaldo==null?0:$rowSaldo['saldo']);
	
	//penerimaan Bulan Lalu
	$queryPenerimaan = sprintf(
		"SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE id_akun='%s' AND tanggal < '%04d-%02d-01'",
		$kodeAkunPenerimaan, $tahunSaldo, $bulanSaldo
	);
	$resultPenerimaan = mysqli_query($mysqli, $queryPenerimaan);
	$tmp = mysqli_fetch_array($resultPenerimaan);
	$penerimaan_bln_lalu = $tmp['jumlah'];
	
	// Pengeluaran bulan lalu
	// => diambil dari pengeluaran yang menggunakan sumber dana $kodeAkunPenerimaan
	$queryPengeluaran = sprintf(
		"SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE sumber_dana='%s' AND tanggal < '%04d-%02d-01'",
		$kodeAkunPenerimaan, $tahunSaldo, $bulanSaldo
	);
	$resultPenerimaan = mysqli_query($mysqli, "A");
	$tmp = mysqli_fetch_array($resultPenerimaan);
	$penerimaan_bln_lalu = $tmp['jumlah'];
}

function update_cache_saldo($tahunSaldo, $bulanSaldo) {
	global $mysqli, $queryCount;
	$tanggalSekarang = date("Y-m-d H:i:s");
	
	// Ambil jumlah penerimaan
	$queryPenerimaan = sprintf(
			"SELECT SUM(jumlah) AS jumlah FROM penerimaan WHERE MONTH(tanggal)=%d AND YEAR(tanggal)=%d",
			$bulanSaldo, $tahunSaldo
	);
	$resultPenerimaan = mysqli_query($mysqli, $queryPenerimaan);
	$queryCount++;
	
	$tmp = mysqli_fetch_array($resultPenerimaan);
	$penerimaanBulanSaldo = $tmp['jumlah'];
	
	// Ambil jumlah pengeluaran/penyaluran
	$queryPengeluaran = sprintf(
			"SELECT SUM(jumlah) AS jumlah FROM penyaluran WHERE MONTH(tanggal)=%d AND YEAR(tanggal)=%d",
			 $bulanSaldo, $tahunSaldo
	);
	$resultPengeluaran = mysqli_query($mysqli, $queryPengeluaran);
	$queryCount++;
	
	$tmp = mysqli_fetch_array($resultPengeluaran);
	$pengeluaranBulanSaldo = $tmp['jumlah'];
	
	// Update cache
	$queryUpdateSaldo = sprintf(
			"INSERT INTO cache_saldo (tahun, bulan, penerimaan, pengeluaran, tgl_update) ".
			"VALUES (%d, %d, %d, %d, '%s') ".
			"ON DUPLICATE KEY UPDATE penerimaan=%d, pengeluaran=%d, tgl_update='%s'",
			$tahunSaldo, $bulanSaldo, $penerimaanBulanSaldo, $pengeluaranBulanSaldo, $tanggalSekarang,
			$penerimaanBulanSaldo, $pengeluaranBulanSaldo, $tanggalSekarang
	);
	$resultUpdate = mysqli_query($mysqli, $queryUpdateSaldo);
	if ($resultUpdate) {
		return array($penerimaanBulanSaldo, $pengeluaranBulanSaldo);
	} else {
		echo mysqli_error($mysqli);
		return null;
	}
}

function update_cache($startTahun, $startBulan) {
	$tahunSekarang = intval(date("Y"));
	$bulanSekarang = intval(date("m"));
	
	$counterTahun = $startTahun;
	$counterBulan = $startBulan;
	
	while (($counterTahun <= $tahunSekarang) || ($counterBulan <= $bulanSekarang)) {
		if ($counterBulan > 12) {
			$counterBulan = 1; $counterTahun++;
		}
		$hasil = update_cache_saldo($counterTahun, $counterBulan);
		if ($hasil == null) {
			if (IS_DEBUGGING) echo "$counterTahun - $counterBulan: Gagal! ";
		}
		$counterBulan++;
	}
}