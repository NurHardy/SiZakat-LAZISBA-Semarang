<?php
	/**/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save_penerima'])){
		$nama = clear_injection($_POST['nama']);
		$alamat = clear_injection($_POST['alamat']);
		$wilayah = $_SESSION['wil_bus'];
		$jenjang = clear_injection($_POST['jenjang']);
		$ayah = clear_injection($_POST['ayah']);
		$ibu = clear_injection($_POST['ibu']);
		$tlahir = clear_injection($_POST['tlahir']);
		//$prestasi = clear_injection($_POST['prestasi']);
		
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
		
		$sql = mysql_query("INSERT INTO penerima_bus(nama,alamat,wilayah,jenjang,add_date,tanggal_lahir,ayah,ibu,foto,alamatortu, pekayah, pekibu, penghasilan, status, hobi) VALUES ('$nama','$alamat','$wilayah','$jenjang','$tanggal','$tlahir','$ayah','$ibu','$target','$alamatortu','$pekayah','$pekibu','$penghasilan','$status','$hobi')");
		
		$sql5 = mysql_query("select * from penerima_bus where nama = '$nama' AND alamat = '$alamat' AND tanggal_lahir = '$tlahir' ");
		$f5 = mysql_fetch_array($sql5);
		$id = $f5['id_penerima'];
		
		$sql2 = mysql_query("INSERT INTO prestasi(alquran,doa,minat,rapor,id_penerima) VALUES ('$alquran','$doa','$minat','$rapor','$id')");
		
		if(($sql)&&($sql2)){
			$_SESSION['success'] = "Data Penerima Bus Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addbus\">";
			//echo mysql_error();
		}
		else {
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addbus\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addbus\">";
	}
?>