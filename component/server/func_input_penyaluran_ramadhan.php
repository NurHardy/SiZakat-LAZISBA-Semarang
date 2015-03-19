<?php
	/*save_mustahik.php*/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		$tgl 			= clear_injection($_POST['tgl']);
		//$no_transaksi 	= clear_injection($_POST['no_transaksi']);
		$jenis_transaksi = clear_injection($_POST['jenis_transaksi']);
		//$rekening 		= clear_injection($_POST['rekening']);
		//$mustahik 		= clear_injection($_POST['mustahik']);
		$amilin 		= clear_injection($_POST['amilin']);
		$jumlah 		= clear_injection($_POST['jumlah']);
		$ket 		= clear_injection($_POST['keterangan']);
		//-------------------------------------------------------------------------------------------------------------
		$sql = mysqli_query($mysqli, "SELECT value FROM opsi WHERE name = 'tahun' ");
		$fetch = mysqli_fetch_array($sql);
		$thn = $fetch['value'];
		
		$q = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penyaluran = '$jenis_transaksi' ");
		$fetch1 = mysqli_fetch_array($q);
		$id_penerimaan = $fetch1['id_penerimaan'];
		
		$q2 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jum FROM penerimaan WHERE id_akun = '$id_penerimaan' AND is_ramadhan = '1' AND thn_ramadhan = '$thn'");
		$fetch2 = mysqli_fetch_array($q2);
		$jumlah1 = $fetch2['jum'];

		$q3 = mysqli_query($mysqli, "SELECT SUM(y.jumlah) as jum2 FROM penyaluran y, persamaan_akun s WHERE s.id_penerimaan = '$id_penerimaan' AND y.id_akun = s.id_penyaluran AND y.is_ramadhan = '1' AND thn_ramadhan = '$thn'");
		$fetch3 = mysqli_fetch_array($q3);
		$jumlah2 = $fetch3['jum2'];
		
		$total = $jumlah1 - $jumlah2;
		
		//---------------------------------------------------------------------------------------------------------
		if($jumlah > $total){
			$_SESSION['error'] = "Saldo anda tidak mencukupi untuk melakukan penyaluran, Total saldo = ".$total." <br/> 
		
			";
			//echo mysql_error();
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penyaluran_ramadhan\">";
		}else{
			$sql = mysqli_query($mysqli, "
				INSERT INTO penyaluran(id_penyaluran,tanggal,id_akun,jumlah,keterangan,is_ramadhan,thn_ramadhan,id_teller) VALUES 
				('','$tgl','$jenis_transaksi','$jumlah','$ket','1','$thn','$amilin')
			");
			
			if($sql){
				$_SESSION['success'] = "Data Transaksi Penyaluran Berhasil Ditambah"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penyaluran_ramadhan\">";
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penyaluran_ramadhan\">";
			}
		}
		
		//---------------------------------------------------------------------------------------------------------
		
		
		

	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penyaluran_ramadhan\">";
	}
	
?>