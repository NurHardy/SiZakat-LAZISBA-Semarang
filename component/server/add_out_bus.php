<?php 
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['save_out'])){
		$jml = clear_injection($_POST['jumlah']);
		$jenjang = clear_injection($_POST['jenjang']);
		$bln = clear_injection($_GET['bln']);
		
		$query = mysql_query("select * from user WHERE id_user='$_SESSION[iduser]'");
		$user = mysql_fetch_array($query);
		
		$sql1 = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun='2.1.$user[wilayah_bus].' AND MID(tanggal,7,4) ='".date('Y')."' ");
		$dd = mysql_fetch_array($sql1);
		
		$sql2 = mysql_query("SELECT SUM(jumlah) as jumlah_keluar, jenjang FROM penyaluran_bus WHERE YEAR(tanggal) = '".date('Y')."' AND wilayah='$user[wilayah_bus]'");
		$dd2 = mysql_fetch_array($sql2);
		
		$saldo = $dd['jumlah'] - $dd2['jumlah_keluar'];	
		
		
		if($jml > $saldo){
			$_SESSION['error'] = "Proses Gagal, Saldo Tidak Mencukupi. <br /> Saldo Saat Ini Sebesar : Rp $saldo,-";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
		}
		else {
			$quera = mysql_query("select * from opsi WHERE name='dana_bus_jenjang'");
		
			$jenjang1 = mysql_fetch_array($quera);
			$j = explode('#',$jenjang1['value']);
		
		
			$sqlaa = mysql_query(
				"SELECT jenjang, count(jenjang) jml FROM penerima_bus WHERE (MONTH(off_date) > '".($bln)."' OR MONTH(off_date)  = '00') AND (YEAR(off_date) >= '".(date('Y'))."' OR YEAR(off_date) = '00') AND wilayah='$user[wilayah_bus]' AND jenjang='$jenjang' GROUP BY jenjang"
			);
			$sd = $smp = $sma = 0;
			$qja = mysql_fetch_array($sqlaa);
			$jen1  = $qja['jml']*$j[$jenjang-1]; //uang yg dibutuhkan
			
			$sqlaa1 = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran_bus WHERE wilayah='$user[wilayah_bus]' AND jenjang='$jenjang' AND bulan='$bln'");
			//echo $jen1;
			$qjaa = mysql_fetch_array($sqlaa1);
			if($qjaa['jumlah'] >= $jen1){
				$_SESSION['error'] = "Proses Gagal, Jumlah yang harus disalurkan melebihi kebutuhan";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
			}else{
				$sql1 = mysql_query("INSERT INTO penyaluran_bus (jumlah, jenjang,bulan, wilayah, id_user) VALUES ('$jml','$jenjang','$bln','$user[wilayah_bus]','$_SESSION[iduser]')");
				
				
				if($sql1){
					$_SESSION['success'] = "Data Penyaluran BUS Berhasil Ditambah";
					//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
				}
				else {
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
					//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addoutbus&bln=$bln\">";
				}
			}
		}
		
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=addbus&bln=$bln\">";
	}
	
?>