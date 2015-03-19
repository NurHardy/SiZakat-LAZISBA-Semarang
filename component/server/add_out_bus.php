<?php 
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save_out'])){
		$jml = clear_injection($_POST['jumlah']);
		$jenjang = clear_injection($_POST['jenjang']);
		$bln = clear_injection($_GET['bln']);
		
		$query = mysqli_query($mysqli, "select * from user WHERE id_user='$_SESSION[iduser]'");
		$user = mysqli_fetch_array($query);
		
		$sql1 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun='2.1.$user[wilayah_bus].' AND MID(tanggal,7,4) ='".date('Y')."' ");
		$dd = mysqli_fetch_array($sql1);
		
		$sql2 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah_keluar, jenjang FROM penyaluran_bus WHERE YEAR(tanggal) = '".date('Y')."' AND wilayah='$user[wilayah_bus]'");
		$dd2 = mysqli_fetch_array($sql2);
		
		$saldo = $dd['jumlah'] - $dd2['jumlah_keluar'];	
		
		
		if($jml > $saldo){
			$_SESSION['error'] = "Proses Gagal, Saldo Tidak Mencukupi. <br /> Saldo Saat Ini Sebesar : Rp $saldo,-";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
		}
		else {
			$quera = mysqli_query($mysqli, "select * from opsi WHERE name='dana_bus_jenjang'");
		
			$jenjang1 = mysqli_fetch_array($quera);
			$j = explode('#',$jenjang1['value']);
		
		
			$sqlaa = mysqli_query($mysqli, 
				"SELECT jenjang, count(jenjang) jml FROM penerima_bus WHERE (MONTH(off_date) > '".($bln)."' OR MONTH(off_date)  = '00') AND (YEAR(off_date) >= '".(date('Y'))."' OR YEAR(off_date) = '00') AND wilayah='$user[wilayah_bus]' AND jenjang='$jenjang' GROUP BY jenjang"
			);
			$sd = $smp = $sma = 0;
			$qja = mysqli_fetch_array($sqlaa);
			$jen1  = $qja['jml']*$j[$jenjang-1]; //uang yg dibutuhkan
			
			$sqlaa1 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penyaluran_bus WHERE wilayah='$user[wilayah_bus]' AND jenjang='$jenjang' AND bulan='$bln'");
			//echo $jen1;
			$qjaa = mysqli_fetch_array($sqlaa1);
			if($qjaa['jumlah'] >= $jen1){
				$_SESSION['error'] = "Proses Gagal, Jumlah yang harus disalurkan melebihi kebutuhan";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
			}else{
				$sql1 = mysqli_query($mysqli, "INSERT INTO penyaluran_bus (jumlah, jenjang,bulan, wilayah, id_user) VALUES ('$jml','$jenjang','$bln','$user[wilayah_bus]','$_SESSION[iduser]')");
				
				
				if($sql1){
					$_SESSION['success'] = "Data Penyaluran BUS Berhasil Ditambah";
					//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
				}
				else {
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
				}
			}
		}
		
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addbus&bln=$bln\">";
	}
	
?>