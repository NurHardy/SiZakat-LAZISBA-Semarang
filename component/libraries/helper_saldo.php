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
	$resultPenerimaan = mysqli_query($mysqli, "A");
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