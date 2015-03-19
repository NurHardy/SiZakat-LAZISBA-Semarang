<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		
		$bulan= clear_injection($_POST['bulan']);
		$tahun = clear_injection($_POST['tahun']);
		$satu = $bulan."#".$tahun;
		
		$id_akun = $_POST['id_akun'];
		$saldo_akun = $_POST['saldo_akun'];
		
		print_r($_POST['datates']);
	
		$sql = mysql_query("UPDATE opsi SET value = '$satu' WHERE name = 'bln_thn_saldo'");
		
		for($i=0;$i<count($saldo_akun);$i++){
			$sql11 = mysql_query("SELECT * FROM saldo_awal WHERE id_akun = '".$id_akun[$i]."'");
			if(mysql_num_rows($sql11) > 0){
				$sqla = mysql_query("UPDATE saldo_awal SET saldo='$saldo_akun[$i]' WHERE id_akun='$id_akun[$i]'");
			}else{
				$sqla = mysql_query("INSERT INTO saldo_awal (id_akun,saldo) VALUES ('$id_akun[$i]','$saldo_akun[$i]')");
			}
		}
		
		
		if($sqla){
				$_SESSION['success'] = "Berhasil"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
				echo mysql_error();
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
			}
		
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
	}

?>