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
		$amilin 	= clear_injection($_POST['amilin']);
		$jumlah 	= clear_injection($_POST['jumlah']);
		$ket 		= clear_injection($_POST['keterangan']);
		$pers 		= clear_injection($_POST['sumber']);
		$noNota 	= clear_injection($_POST['no_nota']);

		//-------------------------------------------------------------------------------------------------------------
		$q = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_persamaan = '$pers' ");
		$fetch1 = mysqli_fetch_array($q);
		$id_penerimaan = $fetch1['id_penerimaan'];
		
		$q = mysqli_query($mysqli, "SELECT * FROM saldo_awal WHERE id_akun = '$id_penerimaan' ");
		$fetch1 = mysqli_fetch_array($q);
		$saldo_awal = $fetch1['saldo'];
		
		$q2 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jum FROM penerimaan WHERE id_akun = '$id_penerimaan' ");
		$fetch2 = mysqli_fetch_array($q2);
		$jumlah1 = $fetch2['jum'];

		$q3 = mysqli_query($mysqli, "SELECT SUM(y.jumlah) as jum2 FROM penyaluran y, persamaan_akun s WHERE s.id_penerimaan = '$id_penerimaan' AND y.id_akun = s.id_penyaluran");
		$fetch3 = mysqli_fetch_array($q3);
		$jumlah2 = $fetch3['jum2'];
		
		$total = $saldo_awal + $jumlah1 - $jumlah2;
		
				
		//-------------------------------------------------------------------------------------------------------------
		//if($jumlah > $total){
		if(false){
			$_SESSION['error'] = "Saldo anda tidak mencukupi untuk melakukan penyaluran, Total saldo = ".$total."";
			//echo mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_dana_zakat_s\">";
		}else{
		
			$sql = mysqli_query($mysqli, 
				"INSERT INTO penyaluran (id_penyaluran,tanggal,no_nota,id_akun,jumlah,keterangan,id_teller,id_persamaan,tgl_entry,id_creator) VALUES ".
				"('','$tgl','$noNota','$jenis_transaksi','$jumlah','$ket','$amilin','$pers','".date('Y-m-d H:i:s')."',".$_SESSION['iduser'].")"
			);
			
			if($sql){
				$_SESSION['success'] = "Data Transaksi Penyaluran Berhasil Ditambah"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_dana_zakat_s\">";
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_dana_zakat_s\">";
			}
		
		}
		
		///--------------------------------------------------------------------------------------------------------------

	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_dana_zakat_s\">";
	}
	
?>