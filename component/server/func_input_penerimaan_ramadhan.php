<?php
	session_start();
	if(ISSET($_POST)){
		include "../config/koneksi.php";
		$sql = mysql_query("SELECT value FROM opsi WHERE name = 'tahun' ");
		$fetch = mysql_fetch_array($sql);
		$thn = $fetch['value'];
		
		$query = mysql_query("INSERT INTO penerimaan(id_penerimaan,tanggal,id_donatur,id_teller,jumlah,keterangan,id_akun,is_ramadhan,thn_ramadhan) VALUES(
								'',
								'$_POST[tanggal]',
								'$_POST[muzakki]',
								'$_POST[amilin]',
								'$_POST[jumlah]',
								'$_POST[keterangan]',
								'$_POST[trans]',
								'1',
								'$thn'							
								)");
		if($query){
			$_SESSION['success'] = "Proses memasukkan informasi ketakmiran berhasil";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penerimaan_ramadhan\">";
		}
		else{
			$_SESSION['error'] = "Proses memasukkan informasi ketakmiran gagal, karena ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penerimaan_ramadhan\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=penerimaan_ramadhan\">";
	}


?>