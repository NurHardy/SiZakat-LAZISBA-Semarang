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
	
		$sql = mysqli_query($mysqli, "UPDATE opsi SET value = '$satu' WHERE name = 'bln_thn_saldo'");
		
		for($i=0;$i<count($saldo_akun);$i++){
			$sql11 = mysqli_query($mysqli, "SELECT * FROM saldo_awal WHERE id_akun = '".$id_akun[$i]."'");
			if(mysqli_num_rows($sql11) > 0){
				$sqla = mysqli_query($mysqli, "UPDATE saldo_awal SET saldo='$saldo_akun[$i]' WHERE id_akun='$id_akun[$i]'");
			}else{
				$sqla = mysqli_query($mysqli, "INSERT INTO saldo_awal (id_akun,saldo) VALUES ('$id_akun[$i]','$saldo_akun[$i]')");
			}
		}
		
		
		if($sqla){
				$_SESSION['success'] = "Berhasil"; 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
				echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
			}
		
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=pengaturan_saldo\">";
	}

?>