<?php
	/*save_pengeluaran.php*/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		$tgl 			= clear_injection($_POST['tgl']);
		$jenis_transaksi = clear_injection($_POST['jenis_transaksi']);
		$amilin 		= clear_injection($_POST['amilin']);
		$jumlah 		= clear_injection($_POST['jumlah']);
		$ket 		= clear_injection($_POST['keterangan']);
		$pers 		= clear_injection($_POST['sumber']);
		$tanggal 		= date('Y-m-d');
		//---------------------------------------------------------------------------------------------------------
		
		
		
		$q = mysql_query("SELECT * FROM persamaan_akun WHERE id_persamaan = '$pers' ");
		$fetch1 = mysql_fetch_array($q);
		$id_penerimaan = $fetch1['id_penerimaan'];
		
		$q = mysql_query("SELECT * FROM saldo_awal WHERE id_akun = '$id_penerimaan' ");
		$fetch1 = mysql_fetch_array($q);
		$saldo_awal = $fetch1['saldo'];
		
		$q2 = mysql_query("SELECT SUM(jumlah) as jum FROM penerimaan WHERE id_akun = '$id_penerimaan'");
		$fetch2 = mysql_fetch_array($q2);
		$jumlah2 = $fetch2['jum'];
		
		$q = mysql_query("SELECT SUM(jumlah) as jum FROM penyaluran WHERE id_akun  LIKE '%4.%' ");
		$fetch = mysql_fetch_array($q);
		$jumlah3 = $fetch['jum'];
		
		
		$total = $saldo_awal + $jumlah2 - $jumlah3;
		
		if($jumlah > $total){
			$_SESSION['error'] = "Saldo anda tidak mencukupi untuk melakukan penyaluran, Total saldo = ".$total." <br/>
			Total Infaq dan Shodaqoh : ".$jumlah2."<br/>
			Total pengeluaran saat ini : ".$jumlah3."<br/>
			
			";
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_pengeluaran\">";
		}else{
			$sql = mysql_query("
				INSERT INTO penyaluran(id_penyaluran,tanggal,id_akun,jumlah,keterangan,id_teller) VALUES 
				('','$tgl','$jenis_transaksi','$jumlah','$ket','$amilin')
			");
			
			if($sql){
				$_SESSION['success'] = "Data Transaksi Pengeluaran"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_pengeluaran\">";
				echo mysql_error();
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_pengeluaran\">";
			}
		}
		//---------------------------------------------------------------------------------------------------------

	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penyaluran_ramadhan\">";
	}
	
?>