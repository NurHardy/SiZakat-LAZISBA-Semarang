<?php
	/*save_akun.php*/
	session_start();
	
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$nama 			= clear_injection($_POST['nama']);
		$induk			= clear_injection($_POST['induk']);
		$keterangan		= clear_injection($_POST['keterangan']);
		//echo $induk;
		
		//cek dulu keberadaannya
		$data = mysqli_query($mysqli, "SELECT * FROM akun WHERE namaakun='$nama' AND idParent = '$induk'");
		if(mysqli_num_rows($data) == 0){
			/*Mengambil data induk*/
			$sql1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE idakun='$induk'");
			$fa = mysqli_fetch_array($sql1);
			
			//menentukan jenis diambil dari awal kode
			$jenis = explode(".",$fa['kode']);
			$jenis = $jenis[0];
			
			/*mengambil kode terakhir yang sejenis*/
			$sql1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE kode LIKE '$fa[kode]%' AND idParent='$fa[idakun]' ORDER BY kode DESC LIMIT 0,1");
			$kd = mysqli_fetch_array($sql1);
			
			//echo "SELECT * FROM akun WHERE kode LIKE '$fa[kode]%' AND idParent='$fa[idakun]' ORDER BY kode DESC LIMIT 0,1";
			//membuat kode
			
			if(mysqli_num_rows($sql1) > 0){
			$kd = explode(".",$kd['kode']);
			$j = count($kd);
			$last = $kd[$j-2]+1;
			//print_r($kd);
			$kd[$j-2] = $last;
			
			$kode = implode(".",$kd);
			}else{
				$kode = $fa['kode'].'1.';
			}
			
			//echo $kode;
			
			$sql = mysqli_query($mysqli, "
				INSERT INTO akun (jenis,kode,idParent,namaakun,keterangan) VALUES 
				('$jenis','$kode','$induk','$nama','$keterangan')
			");
			
			if($sql){
				$_SESSION['success'] = "Tambah Akun Berhasil";
			}else{
				$_SESSION['error'] = "Terjadi Kelasahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			}
			
		}else{
			$_SESSION['error'] = "Akun Sudah Tersedia";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
	}
	echo "<meta http-equiv='refresh' content='0;url=../../main.php?s=form_akun'>";
	
?>