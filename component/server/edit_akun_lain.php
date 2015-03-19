<?php
	session_start();
	
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save'])){
		$id = $_GET['id'];
		$nama 			= clear_injection($_POST['nama']);
		$induk			= clear_injection($_POST['induk']);
		$keterangan		= clear_injection($_POST['keterangan']);
		
		$sql = mysqli_query($mysqli, "UPDATE pengeluaran SET namaakun = '$nama' , keterangan = '$keterangan' , idParent = '$induk' WHERE idakun = '$id'");
		if($sql){
				$_SESSION['success'] = "Ubah Akun Berhasil";
				
		}else{
				$_SESSION['error'] = "Terjadi Kelasahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
	}
	echo "<meta http-equiv='refresh' content='0;url=../../main.php?s=edit_akun_pengeluaran&id=$_GET[id]'>";


?>