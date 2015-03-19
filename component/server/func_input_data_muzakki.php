<?php
	session_start();
	

	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if (ISSET ($_POST['save']))  {
				$nama =	clear_injection($_POST['namalengkap']);
				$tmp =	clear_injection($_POST['tempatlahir']);
				$tgl =	clear_injection($_POST['tanggallahir']);
				$alamat = 	clear_injection($_POST['alamat']);
				$kota = clear_injection($_POST['kota']);
				$hp =	clear_injection($_POST['hp']);
				$email = clear_injection($_POST['email']);
				$pek =	clear_injection($_POST['pekerjaan']);
				$phs  =	clear_injection($_POST['penghasilan']);
				$peru	= clear_injection($_POST['perusahaan']);
				$alperu= 	clear_injection($_POST['alamatperusahaan']);
				$user= 	clear_injection($_POST['user']);
				$pass= 	clear_injection($_POST['passwords']);
				$jns= 	clear_injection($_POST['jenis']);
				$pass = sha1(sha1(md5($pass)));
				
				$sql = "INSERT INTO user(id_user,username,password,nama,tempat_lahir,tanggal_lahir,alamat,kota,hp,email,pekerjaan,penghasilan,perusahaan,alamat_perusahaan,level,jns_donatur) VALUES
				('','$user','$pass','$nama','$tmp','$tgl','$alamat','$kota','$hp','$email','$pek','$phs','$peru','$alperu','1','$jns')";

				$aksi= mysql_query($sql);
				if($aksi){
					$_SESSION['success'] = "Data Muzakki Berhasil Ditambah";
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
				}else{
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
				}
	}	
	else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
	}
	
?>