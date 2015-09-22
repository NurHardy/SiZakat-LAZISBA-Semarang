<?php

	/**
	 * Mengecek record akun penerimaan/penyaluran
	 * 
	 * @author M Nur Hardyanto
	 * @param string $kodeAkun Kode akun yang akan diperiksa (Misal: 1.2. )
	 * @return NULL|array Mengebalikan associative array jika ditemukan, NULL jika sebaliknya
	 */
	function cek_kode_akun($kodeAkun) {
		global $mysqli, $queryCount;
		
		$queryCekAkun = sprintf("SELECT * FROM akun WHERE kode='%s'",
				mysqli_real_escape_string($mysqli, $kodeAkun));
		$resultCekAkun = mysqli_query($mysqli, $queryCekAkun);
		$queryCount++;
		
		$dataAkun = mysqli_fetch_assoc($resultCekAkun);
		return $dataAkun;
	}