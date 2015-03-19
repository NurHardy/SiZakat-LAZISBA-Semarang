<?php
	session_start();
	if(ISSET($_POST)){
		include "../config/koneksi.php";
		$query = mysqli_query($mysqli, "INSERT INTO penerimaan (no_nota,tanggal,id_donatur,id_teller,jumlah,keterangan,id_akun) VALUES(
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
			$_SESSION['error'] = "Proses memasukkan informasi ketakmiran gagal, karena ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
	}


?>