<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$nama		= clear_injection($_POST['nama']);
		$dana		= clear_injection($_POST['cicilan']);
		$pegawai	= clear_injection($_POST['petugas']);
		$th	= clear_injection($_POST['thn']);
		$tanggal	= date('Y-m-d');
		
		//---------------------------------------------------------------------------------------------------------------
		//$sql = mysql_query("SELECT value FROM opsi WHERE name = 'tahun_anggaran' ");
		
		 $q = mysqli_query($mysqli, "SELECT SUM(jumlah) as jum FROM penyaluran WHERE id_akun = '2.10.' AND id_ukm = '$nama' ");
		 $f1 = mysqli_fetch_array($q);
		 $j1 = $f1['jum'];
		 
		 $q2 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jum2 FROM penerimaan WHERE id_akun = '1.9.' AND id_donatur = '$nama'  ");
		 $f2 = mysqli_fetch_array($q2);
		 $j2 = $f2['jum2'];
		 
		 $total = $j1 - $j2;
		//---------------------------------------------------------------------------------------------------------------
		
		if( $dana > $total ){
			$_SESSION['error'] = "Cicilan anda melebihi jumlah peminjaman ";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=cicil_kubah\">";
		}else{
			$sql = mysqli_query($mysqli, "INSERT INTO penerimaan (tanggal,id_donatur,id_teller,jumlah,keterangan,id_akun,th_kubah) VALUES ('$tanggal','$nama','$pegawai','$dana','Cicilan dana KUBAH ke LAZIS','1.9.','$th')");
			if($sql){
				$_SESSION['success'] = "Cicilan dana KUBAH Berhasil Ditambah";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=cicil_kubah\">";
			}else{
				$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan :".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=cicil_kubah\">";
			}
		}
		//---------------------------------------------------------------------------------------------------------------
		
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=cicil_kubah\">";
	}
	
?>