<?php
	session_start();
	
	if(ISSET($_POST)){
		include "../config/koneksi.php";
		include "../libraries/injection.php";
			
		if($_POST['p1'] == ""){
			$pas = "";
		}else{
			$pas = "password = sha1(sha1(md5('$_POST[p1]'))),";
		}
		
		$query = mysqli_query($mysqli, "UPDATE user SET
								nama = '$_POST[nama]',
								alamat = '$_POST[alamat]',
								hp = '$_POST[telp]',
								pj = '$_POST[pj]',
								username = '$_POST[username]',
								$pas
								WHERE id_user = '$_GET[id]'
								");
			
				if($query){
						$_SESSION['success'] = "Data UKM Berhasil Diubah";
						echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_ukm&id=$_GET[id]\">";
						echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				}else{
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_ukm&id=$_GET[id]\">";
				}
		}else{
			$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_ukm&id=$_GET[id]\">";
		}
	
?>