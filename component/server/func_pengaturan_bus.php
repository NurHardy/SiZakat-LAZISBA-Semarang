<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		
		$sd= clear_injection($_POST['sd']);
		$smp = clear_injection($_POST['smp']);
		$sma = clear_injection($_POST['sma']);
	
		$satu = $sd."#".$smp."#".$sma;
		
		$sql = mysql_query("UPDATE opsi SET value = '$satu' WHERE name = 'dana_bus_jenjang'");
		
		if($sql){
				$_SESSION['success'] = "Berhasil Mengubah Dana BUS"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
				echo mysql_error();
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
			}
		
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
	}

?>