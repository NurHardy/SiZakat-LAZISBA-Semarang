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
		
		$query = mysql_query("UPDATE user SET
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
						echo mysql_error();
				}else{
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_ukm&id=$_GET[id]\">";
				}
		}else{
			$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_ukm&id=$_GET[id]\">";
		}
	
?>