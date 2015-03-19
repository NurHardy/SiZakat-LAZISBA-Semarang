<?php
	session_start();
	if(ISSET($_POST)){
		include "../config/koneksi.php";
		$query = mysql_query("INSERT INTO penerimaan (no_nota,tanggal,id_donatur,id_teller,jumlah,keterangan,id_akun) VALUES(
								'$_POST[notrans]',
								'$_POST[tanggal]',
								'$_POST[muzakki]',
								'$_POST[amilin]',
								'$_POST[jumlah]',
								'$_POST[keterangan]',
								'$_POST[trans]')");
		if($query){
			$_SESSION['success'] = "Proses memasukkan informasi ketakmiran berhasil";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
		}
		else{
			$_SESSION['error'] = "Proses memasukkan informasi ketakmiran gagal, karena ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
	}


?>