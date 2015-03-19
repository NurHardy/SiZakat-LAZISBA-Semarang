<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		
		$sd= clear_injection($_POST['sd']);
		$smp = clear_injection($_POST['smp']);
		$sma = clear_injection($_POST['sma']);
	
		$satu = $sd."#".$smp."#".$sma;
		
		$sql = mysqli_query($mysqli, "UPDATE opsi SET value = '$satu' WHERE name = 'dana_bus_jenjang'");
		
		if($sql){
				$_SESSION['success'] = "Berhasil Mengubah Dana BUS"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
				echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
			}
		
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_bus\">";
	}

?>