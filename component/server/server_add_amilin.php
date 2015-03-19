<?php
		session_start();
		include "../config/koneksi.php";
		include "../libraries/injection.php";
		if (ISSET ($_POST['save']))  {

		$nama = clear_injection($_POST['nama']);
		$tmp_lahir = clear_injection($_POST['tmp_lahir']);
		$tgl_lahir = clear_injection($_POST['tgl_lahir']);
		$alamat = clear_injection($_POST['alamat']);
		$kota = clear_injection($_POST['kota']);
		$hp = clear_injection($_POST['hp']);
		$email = clear_injection($_POST['email']);
		$user = clear_injection($_POST['user']);
		$pass = clear_injection($_POST['passwords']);
		$pass = sha1(sha1(md5($pass)));
		$level = clear_injection($_POST['level']);
		if(ISSET($_POST['wil'])){
			$wil = clear_injection($_POST['wil']);
		}else{
			$wil = "";
		}
		$sql = "INSERT INTO user(id_user,username,password,nama,tempat_lahir,tanggal_lahir,alamat,kota,hp,email,level,wilayah_bus) VALUES
		('','$user','$pass','$nama','$tmp_lahir','$tgl_lahir','$alamat','$kota','$hp','$email','$level','$wil')";
		
		$aksi= mysqli_query($mysqli, $sql);
		//$aksi
		if($aksi){
			$_SESSION['success'] = "Data Amilin Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_amilin\">";
		}
		else {
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_amilin\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_amilin\">";
	}

?>