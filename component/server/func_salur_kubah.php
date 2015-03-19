<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$nama		= clear_injection($_POST['nama']);
		$dana		= clear_injection($_POST['dana']);
		$pegawai	= clear_injection($_POST['petugas']);
		$th	= clear_injection($_POST['thn']);
		$tanggal	= date('Y-m-d');
		
		
		//-----------------------------------------------------------------------------------------------------------------
		$q = mysql_query("SELECT * FROM saldo_awal WHERE id_akun = '1.9.' ");
		$fetch1 = mysql_fetch_array($q);
		$saldo_awal = $fetch1['saldo'];
			
		$q2 = mysql_query("SELECT SUM(jumlah) as jum FROM penerimaan WHERE id_akun = '1.9.' ");
		$fetch2 = mysql_fetch_array($q2);
		$jumlah2 = $fetch2['jum'];
		
		$q3 = mysql_query("SELECT SUM(jumlah) as jum2 FROM penyaluran WHERE id_akun = '2.10.' ");
		$fetch3 = mysql_fetch_array($q3);
		$jumlah3 = $fetch3['jum2'];
		
		$total = $saldo_awal+$jumlah2 - $jumlah3;
		
		//-----------------------------------------------------------------------------------------------------------------
		if($dana > $total){
				$_SESSION['error'] = "Saldo anda tidak mencukupi untuk melakukan penyaluran, Total saldo = ".$total." ";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=salur_kubah\">";
		}else{
			$sql = mysql_query("INSERT INTO penyaluran (tanggal,id_akun,jumlah,keterangan,is_ukm,id_ukm,id_teller,id_persamaan,th_kubah) VALUES ('$tanggal','2.10.','$dana','Penyaluran KUBAH ke UKM','1','$nama','$pegawai','19','$th')");
			if(@$sql){
				$_SESSION['success'] = "Penyaluran dana KUBAH Berhasil Ditambah";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=salur_kubah\">";
			}else{
				$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan :".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=salur_kubah\">";
			}
		
		}
		
		//-----------------------------------------------------------------------------------------------------------------

	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=salur_kubah\">";
	}
	
?>