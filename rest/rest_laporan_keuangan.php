<?php
	global $mysqli;
	
	function toRupiah($nilai) {
		return "Rp. ".number_format($nilai, 0, ',', '.');
	}
	
	//====================== INIT
	$errorStr = null;
	
	$hasilData = array(
		'kas'			=> toRupiah(0),
		'pemasukan'		=> array('total' => toRupiah(0), 'count' => 0, 'data' => array()),
		'penyaluran'	=> array('total' => toRupiah(0), 'count' => 0, 'data' => array()),
		'saldo'			=> toRupiah(0)
	);
	
	//=============================
	
	if ($inMonth) $bulan = $inMonth;
	else $bulan = date('m');
	
	if ($inYear) $tahun = $inYear;
	else $tahun = date('Y');
	
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	$month1 = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	
	$bln = $bulan;
	$th = $tahun;
	if($bln == 1){
		$blnm = 12;
		$thm = $th-1;
	}else{
		$blnm = $bln-1;
		$thm = $th;
	}
	
	$sql = $mysqli->query("SELECT * FROM penerimaan WHERE tanggal < '$th-$bln-01'");
	$sql1 = $mysqli->query("SELECT * FROM penyaluran WHERE tanggal < '$th-$bln-01'");
	$error = 0;
	if(($sql->num_rows > 0) || ($sql1->num_rows > 0)){
		//jika ada transaksi sebelum bulan yang dipilih
		
		//ambil saldo awal
		$sqla = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$saldo_awal = $d['saldo']; //saldo awal;
		
		//ambil penerimaan
		$sqla = $mysqli->query("SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE tanggal < '$th-$bln-01'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$penerimaan = $d['jumlah'];
		
		//ambil penyaluran
		$sqla = $mysqli->query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE tanggal < '$th-$bln-01'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$penyaluran = $d['jumlah'];
		
		$saldo_bulan_lalu = ($saldo_awal + $penerimaan) - $penyaluran;
	}else{
		//jika tidak ada transaksi sebelum bulan yang dipilih
		$sqla = $mysqli->query("SELECT * FROM opsi WHERE name='bln_th_saldo'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$d = explode('#',$d['value']);
		if(($d[0] <= $bln) && ($d[1] <= $th)){
			//ambil saldo awal
			$sqla = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
			$d = $sqla->fetch_array(MYSQLI_ASSOC);
			$saldo_bulan_lalu = $d['saldo']; //saldo awal;
		}else{
			$error = 1;
			$errorStr = "Tidak ada transaksi untuk bulan {$month1[$bulan]} {$tahun}";
		}
	}
	if($error == 0){
		$hasilAkhir['label'] = "{$month1[$bulan]} {$tahun}";
		/* SQL */
		$sql = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
		$s = $sql->fetch_array(MYSQLI_ASSOC);
		
		//Penerimaan
		$sql1 = $mysqli->query("SELECT * FROM `penerimaan` WHERE tanggal <= '$tahun-$bulan-01'");
		if($sql1->num_rows <= 0){
			$sql = $mysqli->query("SELECT * FROM saldo_awal");
			$s = $sql1->fetch_array(MYSQLI_ASSOC);
		}
		$hasilData['kas'] = toRupiah($saldo_bulan_lalu);
	
		$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penerimaan p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND id_akun LIKE '1.%' GROUP BY p.id_akun");
		$i=0;
		$total_masuk = 0;
		while($q = $sqls->fetch_array(MYSQLI_ASSOC)) {
			$i++;
			$total_masuk = $total_masuk + $q['jumlah'];
			
			$hasilData['pemasukan']['data'][] = array(
				'label' => $q['namaakun'],
				'nominal' => toRupiah($q['jumlah'])
			);
			
		}
		$hasilData['pemasukan']['count'] = $i;
		$hasilData['pemasukan']['total'] = toRupiah($total_masuk);
	
		// ===== PENGELUARAN / PENYALURAN
		$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '2.%') GROUP BY p.id_akun");
		$i=0;
		$total_keluar = 0;
		while($q = $sqls->fetch_array(MYSQLI_ASSOC)) {
			$i++;
			$total_keluar = $total_keluar + $q['jumlah'];
			
			$hasilData['penyaluran']['data'][] = array(
				'label' => $q['namaakun'],
				'nominal' => toRupiah($q['jumlah'])
			);
		}
	
		$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, pengeluaran a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '4.%') GROUP BY p.id_akun");
		$total_keluar1 = 0;
		
		while($q = $sqls->fetch_array(MYSQLI_ASSOC)){
			$i++;
			$total_keluar1 = $total_keluar1 + $q['jumlah'];
			
			$hasilData['penyaluran']['data'][] = array(
				'label' => $q['namaakun'],
				'nominal' => toRupiah($q['jumlah'])
			);
		}
		
		$hasilData['penyaluran']['count'] = $i;
		$hasilData['penyaluran']['total'] = toRupiah($total_keluar+$total_keluar1);
		$hasilData['saldo'] = toRupiah($saldo_bulan_lalu+$total_masuk-$total_keluar-$total_keluar1);
		
		$hasilAkhir['result'] = "ok";
		$hasilAkhir['data'] = $hasilData;
	}else{
		$hasilAkhir['result'] = "error";
		$hasilAkhir['error'] = $errorStr;
	}

	// =========================
