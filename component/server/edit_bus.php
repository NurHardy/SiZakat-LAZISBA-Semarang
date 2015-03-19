<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save_penerima'])){
		$id = clear_injection($_GET['id']);
		$nama = clear_injection($_POST['nama']);
		$alamat = clear_injection($_POST['alamat']);
		$wilayah = $_SESSION['wil_bus'];
		$jenjang = clear_injection($_POST['jenjang']);
		$ayah = clear_injection($_POST['ayah']);
		$ibu = clear_injection($_POST['ibu']);
		$tlahir = clear_injection($_POST['tlahir']);
		
		$alamatortu = clear_injection($_POST['alamatortu']);
		$pekayah = clear_injection($_POST['pekayah']);
		$pekibu = clear_injection($_POST['pekibu']);
		$penghasilan = clear_injection($_POST['penghasilan']);
		$status = clear_injection($_POST['status']);
		$hobi = clear_injection($_POST['hobi']);
		
		$doa = $_POST['doa'];
		$alquran = $_POST['alquran'];
		$minat = $_POST['minat'];
		$rapor = $_POST['rapor'];
		
		
		$tanggal = date('Y-m-d');
		$foto = $_FILES['bus']['name'];
		$source = $_FILES['bus']['tmp_name'];
		$target = $_FILES['bus']['name'];
		move_uploaded_file($source,'../../img/bus/'.$target);
		
		$sql = mysql_query("UPDATE penerima_bus SET 
								nama = '$nama',
								alamat = '$alamat',
								wilayah = '$wilayah',
								jenjang = '$jenjang', 
								add_date = '$tanggal',
								tanggal_lahir = '$tlahir',
								ayah = '$ayah',
								ibu = '$ibu',
								foto = '$target' WHERE id_penerima = '$id'");
								
		$sql2 = mysql_query("UPDATE prestasi SET 
								alquran = '$alquran',
								doa = '$doa',
								minat = '$minat',
								rapor = '$rapor'
								WHERE id_penerima = '$id'");
		
		if(($sql)&&($sql2)){
			$_SESSION['success'] = "Data Penerima Bus Berhasil Diedit";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editbus&id=$id\">";
		}
		else {
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editbus&id=$id\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editbus&id=$id\">";
	}
?>