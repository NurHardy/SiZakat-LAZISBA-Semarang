<?php
	session_start();	
	
	// Jika berlum login, maka tidak boleh mengakses
	if ((!isset($_SESSION['username']))AND (!isset($_SESSION['passsword']))){
		header("Location: login.php?next=".urlencode($_SERVER['REQUEST_URI']));
		//echo "<meta http-equiv=\"refresh\" content=\"0; url=login.php\">";
	}
	
	include "component/config/koneksi.php";
	include "component/libraries/injection.php";

	function setActiveMenu($link){
		$get = clear_injection(@$_GET['s']);
		if($link == $get){
			echo "class='active'";
		}
	}
	
	function setActiveOpen($link){
		$get = clear_injection(@$_GET['s']);
		if(in_array($get,$link)){
			echo "class='active open'";
		}else{
			echo "class='submenu'";
		}
	}
	
	function getTitle(){
		if(ISSET($_GET['s'])){
					if($_GET['s'] == 'form_dana_zakat_s'){
						echo "Tambah Transaksi Penyaluran";
					}else if($_GET['s'] == 'dashboard'){
						echo "Dasboard";
					}else if($_GET['s'] == 'form_amilin'){
						echo "Tambah Data Amilin";
					}else if($_GET['s'] == 'form_muzakki'){
						echo "Tambah Data Muzakki";
					}else if($_GET['s'] == 'form_mustahik'){
						echo "Tambah Data Mustahik";
					}else if($_GET['s'] == 'editmuzakki'){
						echo "Ubah Data Muzakki";
					}else if($_GET['s'] == 'daftar_muzakki'){
						echo "Daftar Muzakki";
					}else if($_GET['s'] == 'edit_amilin'){
						echo "Ubah Data Amilin";
					}else if($_GET['s'] == 'view_amilin'){
						echo "Daftar Amilin";
					}else if($_GET['s'] == 'editmustahik'){
						echo "Ubah Data Mustahik";
					}else if($_GET['s'] == 'daftarmustahik'){
						echo "Daftar Mustahik";
					}else if($_GET['s'] == 'form_akun'){
						echo "Tambah Akun Baru";
					}else if($_GET['s'] == 'daftarakun'){
						echo "Daftar Akun";
					}else if($_GET['s'] == 'editakun'){
						echo "Ubah Akun";
					}else if($_GET['s'] == 'form_info_baru'){
						echo "Tambah Informasi Baru";
					}else if($_GET['s'] == 'daftar_info'){
						echo "Daftar Informasi";
					}else if($_GET['s'] == 'edit_info'){
						echo "Ubah Informasi";
					}else if($_GET['s'] == 'hapus_info'){
						echo "Hapus Informasi";
					}else if($_GET['s'] == 'form_penerimaan'){
						echo "Tambah Transaksi Penerimaan";
					}else if($_GET['s'] == 'daftar_penerimaan'){
						echo "Daftar Transaksi penerimaan";
					}else if($_GET['s'] == 'detail_bulanan'){
						echo "Detail Transaksi Bulan ".getBulan($_GET['bulan']);
					}else if($_GET['s'] == 'daftar_penyaluran'){
						echo "Daftar Transaksi Penyaluran";
					}else if($_GET['s'] == 'detail_bulanan2'){
						echo "Detai Transaksi Bulan ".getBulan($_GET['bulan']);
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'home'){
						echo "Beranda";
					}else if($_GET['s'] == 'zakatku'){
						echo "Daftar Donasiku";
					}else if($_GET['s'] == 'rekapzakat'){
						echo "Rekapitulasi Donasi";
					}else if($_GET['s'] == 'penerimaan_ramadhan'){
						echo "Tambah Penerimaan Ramadhan";
					}else if($_GET['s'] == 'penyaluran_ramadhan'){
						echo "Tambah Penyaluran Ramadhan";
					}else if($_GET['s'] == 'daftar_penerimaan_ramadhan'){
						echo "Daftar Penerimaan Ramadhan";
					}else if($_GET['s'] == 'daftar_penyaluran_ramadhan'){
						echo "Daftar Penyaluran Ramadhan";
					}else if($_GET['s'] == 'opsi_ramadhan'){
						echo "Opsi Ramadhan";
					}
					//yang ini yang tambahan mas
					else if($_GET['s'] == 'form_pengeluaran'){
						echo "Tambah Transaksi Pengeluaran";
					}else if($_GET['s'] == 'pengaturan_bus'){
						echo "Pengaturan Jumlah Dana BUS";
					}else if($_GET['s'] == 'pengaturan_saldo'){
						echo "Pengaturan Saldo Awal";
					}
					//baru di edit
					else if($_GET['s'] == 'daftar_ukm'){
						echo "Daftar UKM";
					}else if($_GET['s'] == 'form_ukm'){
						echo "Tambah UKM";
					}else if($_GET['s'] == 'edit_ukm'){
						echo "Edit UKM";
					}else if($_GET['s'] == 'salur_kubah'){
						echo "Tambah Transaksi Penyaluran Kubah";
					}else if($_GET['s'] == 'cicil_kubah'){
						echo "Tambah Transaksi Cicilan Kubah";
					}else if($_GET['s'] == 'transaksi_kubah'){
						echo "Daftar Transaksi Kubah";
					}		
					else if($_GET['s'] == 'ubah_akun_zakat'){
						echo "Ubah Akun";
					}else if($_GET['s'] == 'ubah_akun_bus'){
						echo "Ubah Akun";
					}//yang ini baru diubah
					else if($_GET['s'] == 'akun_pengeluaran'){
						echo "Tambah Akun Lain-Lain";
					}else if($_GET['s'] == 'daftar_akun_lain'){
						echo "Daftar Akun Lain-Lain";
					}else if($_GET['s'] == 'edit_akun_pengeluaran'){
						echo "Ubah Akun";
					}
					else if($_GET['s'] == 'form_sabab'){
						echo "Tambah Sabab";
					}else if($_GET['s'] == 'daftar_sabab'){
						echo "Daftar Sabab";
					}else if($_GET['s'] == 'edit_sabab'){
						echo "Ubah Data Sabab";
					}else if($_GET['s'] == 'lihat_detail_sabab'){
						echo "Detail Data Sabab";
					}
					//terbaru 9/12/2013
					else if($_GET['s'] == 'pengaturan_lain_lain'){
						echo "Pengaturan lain lain";
					}else if($_GET['s'] == 'ubah_akun_pribadi'){
						echo "Ubah Akun Pribadi";
					}
					
					
					
					else if($_GET['s'] == 'ubah_akun_sabab'){
						echo "Ubah Data Pribadi";
					}
					
					//baruuuuuuuuuuuuuuuuuuuuu
					else if($_GET['s'] == 'daftar_peserta_bus'){
						echo "Daftar Peserta Bus";
					}
					
					
					else{
						echo "";
					}
				}
	}
	
	function getBulan($bln){
		$month = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
		return ((ISSET($month[$bln])) &&($month[$bln] != ""))?$month[$bln]:"";
	}
	?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>LAZISBA</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
		<link rel="stylesheet" href="css/fullcalendar.css" />	
		<link rel="stylesheet" href="css/unicorn.main.green.css" />
		<link rel="stylesheet" href="css/unicorn.green.css" class="skin-color" />
        <link rel="stylesheet" href="css/colorpicker.css" />
        <link rel="stylesheet" href="css/datepicker.css" />
		<link rel="stylesheet" href="css/icheck/flat/blue.css" />
		<link rel="stylesheet" href="css/select2.css" />		
		<link rel="stylesheet" href="css/jquery.treeview.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
		<script src="js/jquery.min.js"></script>
	</head>
	<body>
		<div id="header">
			<h1><a href="">LAZISBA</a></h1>	
			<a id="menu-trigger" href="#"><i class="glyphicon glyphicon-align-justify"></i></a>	
		</div>
		<?php
			/*
			  - bulanan	(1-12)
			  - 3 bulanan (1,5,9)
			  - 6 bulanan (1,7)
			  - 1 tahunan 1
			 */
			 
			 $bln3 = array(1,5,9);
			 $bln6 = array(1,7);
			 
			
			$sql1 = mysqli_query($mysqli, "SELECT * FROM user WHERE level='1'");
			$jml = 0;
			while($d = mysqli_fetch_array($sql1)){
				//cek bulanan
				if($d['jns_donatur'] == '1'){
					$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
					$a = mysqli_fetch_array($sqla);
					if($a['jumlah'] <= 0){
						$jml++;
					}
				}elseif($d['jns_donatur'] == '2'){
					if(in_array(date('m'),$bln3)){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}elseif($d['jns_donatur'] == '3'){
					if(in_array(date('m'),$bln6)){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}elseif($d['jns_donatur'] == '4'){
					if(date('m') == '1'){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}
				
			}
		?>

		<div id="user-nav">
            <ul class="btn-group">
				<?php if($_SESSION['level']==99){?>
				<li class="btn" id="menu-messages"><a href="main.php?s=view_alert" data-target="#menu-messages"><i class="glyphicon glyphicon-calendar"></i> <span class="text">Pengingat</span> <span class="label label-danger"><?php echo $jml;?></span></a>
				
				
				<?php
					include "component/config/koneksi.php";
					$sql = mysqli_query($mysqli, "SELECT COUNT(*) AS jumlah FROM user WHERE level = 1 and MID(tanggal_lahir,4,2) = MONTH(CURDATE())");
					$f = mysqli_fetch_array($sql);
					
					
				?>
				<li class="btn" id="menu-messages"><a href="main.php?s=view_messages"><i class="glyphicon glyphicon-gift"></i> <span class="text">Ulang Tahun</span> <span class="label label-danger"><?php echo $f['jumlah'];?></span></a>
				
				<?php };?>
				<li class="btn"><a title=""><span class="text">Selamat Datang,<?php echo $_SESSION['username'];?></span></a></li>
                <li class="btn"><a title="" href="main.php?s=logout"><i class="glyphicon glyphicon-share-alt"></i> <span class="text">Logout</span></a></li>
            </ul>
        </div>
            
		<div id="sidebar">
			<!--<a href="#" class="hide"><i class="glyphicon glyphicon-home"></i> Dashboard</a>-->
			<ul>
				<?php 
					if($_SESSION['level']==99){
				?>
				<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
				<li <?php echo setActiveOpen(array('form_info_baru','daftar_info','edit_info','kirim_sms','daftar_sms','pengaturan_lain_lain'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Informasi</span> <span class="label">5</span></a>
					<ul>
						<li <?php setActiveMenu('form_info_baru');?>><a href="main.php?s=form_info_baru">Tambah Informasi Baru</a></li>
						<li <?php setActiveMenu('daftar_info');?>><a href="main.php?s=daftar_info">Daftar Informasi</a></li>
						<!-- yang ini tambahan terbaru mas tanggal 9/12/2013-->
						<li <?php setActiveMenu('pengaturan_lain_lain');?>><a href="main.php?s=pengaturan_lain_lain">Pengaturan Link</a></li>
						
						<li <?php setActiveMenu('kirim_sms');?>><a href="main.php?s=kirim_sms">Kirim SMS</a></li>
						<li <?php setActiveMenu('daftar_sms');?>><a href="main.php?s=daftar_sms">Inboks SMS</a></li>
					</ul>
				</li>
				<li <?php echo setActiveOpen(array('form_akun','daftarakun','editakun','daftar_akun_lain','akun_pengeluaran'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Daftar Akun</span> <span class="label">4</span></a>
					<ul>
						  <li <?php setActiveMenu('form_akun');?>><a href="main.php?s=form_akun">Tambah Akun Baru</a></li>
						   <!-- ini yang baru di ubah-->
						  <li <?php setActiveMenu('akun_pengeluaran');?>><a href="main.php?s=akun_pengeluaran">Tambah Akun Lain Lain</a></li>
						  <li <?php setActiveMenu('daftarakun');?>><a href="main.php?s=daftarakun">Daftar Akun</a></li>
						   <!-- ini yang baru di ubah-->
						  <li <?php setActiveMenu('daftar_akun_lain');?>><a href="main.php?s=daftar_akun_lain">Daftar Akun Lain-lain</a></li>
						 
						 <!-- <li><a href="">Menu3</a></li>
						  <li><a href="">Menu4</a></li>-->
					</ul>
				</li>
				
				<li <?php echo setActiveOpen(array('form_penerimaan','daftar_penerimaan','detail_bulanan','form_dana_zakat_s','daftar_penyaluran','detail_bulanan2','form_pengeluaran','lapbulanan','lapakun','lapbulan','lapblndetail','laplistakun'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Transaksi Harian</span> <span class="label">5</span></a>
					<ul>
						<li <?php setActiveMenu('form_penerimaan');?>><a href="main.php?s=form_penerimaan">Tambah Transaksi Penerimaan</a></li>
						<li <?php setActiveMenu('form_dana_zakat_s');?>><a href="main.php?s=form_dana_zakat_s">Tambah Transaksi Penyaluran</a></li>
						
								<li <?php setActiveMenu('form_pengeluaran');?>><a href="main.php?s=form_pengeluaran">Tambah Transaksi Penyaluran Lain - lain</a></li>
								
						<!--<li <?php setActiveMenu('daftar_penerimaan');?>><a href="main.php?s=daftar_penerimaan&q=1&th=<?php echo date('Y');?>">Daftar Penerimaan</a></li>
						
						  <li <?php setActiveMenu('daftar_penyaluran');?>><a href="main.php?s=daftar_penyaluran&q=2&th=<?php echo date('Y');?>">Daftar Penyaluran</a></li> -->
						  <li <?php setActiveMenu('lapbulanan'); setActiveMenu('lapbulan');setActiveMenu('lapblndetail');?>><a href="main.php?s=lapbulanan">Laporan Bulanan</a></li>
						  <li <?php setActiveMenu('lapakun');setActiveMenu('laplistakun');?>><a href="main.php?s=lapakun">Laporan Perakun</a></li>
					</ul>
				</li>
				
				<!--<li <?php echo setActiveOpen(array());?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Transaksi Ramadhan</span> <span class="label">2</span></a>
					<ul>
						<li <?php setActiveMenu('');?>><a href="main.php">Tambah Transaksi Penerimaan Ramadhan</a></li>
						<li <?php setActiveMenu('');?>><a href="main.php">Tambah Transaksi Penyaluran Ramadhan </a></li>
						<li <?php setActiveMenu('');?>><a href="main.php">Daftar Penerimaan Ramadhan</a></li>
						
						  <li <?php setActiveMenu('');?>><a href="main.php?s=daftar_penyaluran&q=3&th=<?php echo date('Y');?>">Daftar Penyaluran Ramadhan</a></li>
					</ul>
				</li>-->
				
				<li <?php echo setActiveOpen(array('salur_kubah','cicil_kubah','daftar_ukm','form_ukm','transaksi_kubah','edit_ukm'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Transaksi Kubah</span> <span class="label">5</span></a>
					<ul>
						<li <?php setActiveMenu('form_ukm');?>><a href="main.php?s=form_ukm">Tambah Penerima Kubah</a></li>
						<li <?php setActiveMenu('salur_kubah');?>><a href="main.php?s=salur_kubah">Transaksi Penyaluran Kubah</a></li>
						<li <?php setActiveMenu('cicil_kubah');?>><a href="main.php?s=cicil_kubah">Transaksi Cicilan Kubah</a></li>
						<li <?php setActiveMenu('daftar_ukm');?>><a href="main.php?s=daftar_ukm">Daftar Penerima Kubah</a></li>
						<li <?php setActiveMenu('lapkubahth');?>><a href="main.php?s=lapkubahth">Daftar Transaksi Kubah</a></li>
					</ul>
				</li>
				
				<!-- BARU DIEDIT TANGGAL 9/01/2013-->
				
				<li <?php echo setActiveOpen(array('daftar_peserta_bus','daftar_peserta_wilayah'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Manajemen BUS</span> <span class="label">2</span></a>
					<ul>
						<li <?php setActiveMenu('daftar_peserta_bus');?>><a href="main.php?s=daftar_peserta_bus">Daftar Semua Peserta BUS</a></li>
						<li <?php setActiveMenu('daftar_peserta_wilayah');?>><a href="main.php?s=daftar_peserta_wilayah">Daftar Peserta BUS per Wilayah</a></li>
						<li <?php setActiveMenu('status_bus');?>><a href="main.php?s=status_bus">Informasi Umum Bus</a></li>
					</ul>
				</li>
				
				<!-- BARU DIEDIT TANGGAL 9/01/2013-->
				
				
				
				
				
				<li <?php echo setActiveOpen(array('form_amilin','form_mustahik','form_muzakki','view_amilin','daftarmustahik','daftar_muzakki','edit_amilin','editmustahik','editmuzakki','daftar_sabab','form_sabab','edit_sabab','lihat_detail_sabab'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Data Master</span> <span class="label">6</span></a>
					<ul>
						  <li <?php setActiveMenu('form_amilin');?>><a href="main.php?s=form_amilin">Tambah Data Amilin dan Korwil</a></li>
						   <!-- yang ini baru diubah-->
						  <li <?php setActiveMenu('form_sabab');?>><a href="main.php?s=form_sabab">Tambah Data Sabab</a></li>
						  <li <?php setActiveMenu('form_muzakki');?>><a href="main.php?s=form_muzakki">Tambah Data Donatur</a></li>
						  <li <?php setActiveMenu('view_amilin');?>><a href="main.php?s=view_amilin">Daftar Amilin & Korwil</a></li>
						  <li <?php setActiveMenu('daftar_muzakki');?>><a href="main.php?s=daftar_muzakki">Daftar Donatur</a></li>
						  <!-- yang ini baru diubah-->
						    <li <?php setActiveMenu('daftar_sabab');?>><a href="main.php?s=daftar_sabab">Daftar Sabab</a></li>
					</ul>
				</li>
				
				
				
				 <!-- TAMBAHAN MAS-->
				<li <?php echo setActiveOpen(array('pengaturan_bus','pengaturan_saldo','persamaan_akun','opsi_ramadhan','pengaturan_umum'));?>>
					<a href="#"><i class="glyphicon glyphicon-th-list"></i> <span>Pengaturan</span> <span class="label">6</span></a>
					<ul>
						
						  <li <?php setActiveMenu('pengaturan_umum');?>><a href="main.php?s=pengaturan_umum">Pengaturan Umum</a></li>
						
						  <li <?php setActiveMenu('pengaturan_bus');?>><a href="main.php?s=pengaturan_bus">Pengaturan Dana BUS</a></li>
						  <li <?php setActiveMenu('pengaturan_saldo');?>><a href="main.php?s=pengaturan_saldo">Pengaturan Saldo Awal</a></li>
						  <li <?php setActiveMenu('persamaan_akun');?>><a href="main.php?s=persamaan_akun">Pengaturan Persamaan Akun</a></li>
						  <li <?php setActiveMenu('opsi_ramadhan');?>><a href="main.php?s=opsi_ramadhan">Pengaturan Ramadhan</a></li>
						<li <?php setActiveMenu('ubah_akun_pribadi');?>><a href="main.php?s=ubah_akun_pribadi">Ubah Akun user : <?php echo $_SESSION['username'];?></a></li>
						
					</ul>
				</li>
				
				<?php 
					$sql = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name = 'ramadhan' ");
					$opsi_ramadhan = mysqli_fetch_array($sql);
					
					if($opsi_ramadhan['value']== 1){
						echo "
						<li "; setActiveOpen(array('penerimaan_ramadhan','penyaluran_ramadhan','daftar_penerimaan_ramadhan','daftar_penyaluran_ramadhan','laporan_ramadhan','lapbulan_ramadhan','lapblndetail_ramadhan')); echo ">
						<a href=\"#\"><i class=\"glyphicon glyphicon-th-list\"></i> <span>Program Ramadhan</span> <span class=\"label\">3</span></a>
							<ul>
							<li "; setActiveMenu('penerimaan_ramadhan'); echo"><a href=\"main.php?s=penerimaan_ramadhan\">Penerimaan Ramadhan</a></li>
							
							<li "; setActiveMenu('penyaluran_ramadhan'); echo "><a href=\"main.php?s=penyaluran_ramadhan\">Penyaluran Ramadhan</a></li>";
							
							/*<li "; setActiveMenu('daftar_penerimaan_ramadhan'); echo"><a href=\"main.php?s=daftar_penerimaan_ramadhan\"><b>Daftar Penerimaan Ramadhan</b></a></li>
							
							<li "; setActiveMenu('daftar_penyaluran_ramadhan'); echo"><a href=\"main.php?s=daftar_penyaluran_ramadhan\"><b>Daftar Penyaluran Ramadhan</b></a></li>*/
							echo "<li "; setActiveMenu('laporan_ramadhan');setActiveMenu('lapbulan_ramadhan');setActiveMenu('lapblndetail_ramadhan'); echo"><a href=\"main.php?s=laporan_ramadhan\">Laporan Ramadhan</a></li>
							</ul>
					</li>";
					}
				
				?>
				
				
				<?php }elseif($_SESSION['level'] == 1){ ?>
					<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
					<li <?php setActiveMenu('lapbulanan');?>><a href="main.php?s=lapbulanan"><i class="glyphicon glyphicon-home"></i> <span>Rekapitulasi Donasi</span></a></li>
					<li <?php setActiveMenu('zakatku');?>><a href="main.php?s=zakatku"><i class="glyphicon glyphicon-home"></i> <span>Donasiku</span></a></li>
					
					<li <?php setActiveMenu('ubah_akun_zakat');?>><a href="main.php?s=ubah_akun_zakat"><i class="glyphicon glyphicon-th-list"></i> <span>Ubah Akun</span></a></li>
				
				
				<?php }elseif($_SESSION['level'] == 3){ ?>
					<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
					<li <?php setActiveMenu('addbus');?>><a href="main.php?s=addbus"><i class="glyphicon glyphicon-home"></i> <span>Tambah Peserta BUS</span></a></li>
					<li <?php setActiveMenu('viewbus');?>><a href="main.php?s=viewbus"><i class="glyphicon glyphicon-home"></i> <span>Daftar Peserta BUS</span></a></li>
					<li <?php setActiveMenu('lapbus');?>><a href="main.php?s=lapbus"><i class="glyphicon glyphicon-home"></i> <span>Laporan Keuangan</span></a></li>
					<li <?php setActiveMenu('ubah_akun_bus');?>><a href="main.php?s=ubah_akun_bus"><i class="glyphicon glyphicon-th-list"></i> <span>Ubah Akun</span></a></li>
		
				<?php }elseif($_SESSION['level'] == 2){ ?>
					<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
					<li <?php setActiveMenu('lapukm_ukm');?>><a href="main.php?s=lapukm_ukm&th=<?php 
						$sqlaqq = mysqli_query($mysqli, "SELECT DISTINCT th_kubah FROM penyaluran WHERE id_ukm = '$_SESSION[iduser]' ORDER BY th_kubah DESC");
						$dqq = mysqli_fetch_array($sqlaqq);
						echo $dqq['th_kubah'];
					?>"><i class="glyphicon glyphicon-home"></i> <span>Laporan Penyaluran UKM</span></a></li>
					<li <?php setActiveMenu('ubah_akun_ukm');?>><a href="main.php?s=ubah_akun_ukm"><i class="glyphicon glyphicon-th-list"></i> <span>Ubah Akun</span></a></li>
				
				
				<!-- yang ini tambahan yang baru bro-->
				<?php }elseif($_SESSION['level'] == 4){ ?>
					<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
					<li <?php setActiveMenu('lihat_detail_sabab');?>><a href="main.php?s=lihat_detail_sabab"><i class="glyphicon glyphicon-home"></i> <span>Data Pribadi</span></a></li>
					
					<li <?php setActiveMenu('ubah_akun_sabab');?>><a href="main.php?s=ubah_akun_sabab"><i class="glyphicon glyphicon-th-list"></i> <span>Ubah Data Pribadi dan Akun</span></a></li>

				
				<!-- yang ini tambahan yang baru bro-->
				<?php }elseif($_SESSION['level'] == 66){ ?>
					<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
					<li <?php setActiveMenu('form_info_baru');?>><a href="main.php?s=form_info_baru">Tambah Informasi Baru</a></li>
					<li <?php setActiveMenu('daftar_info');?>><a href="main.php?s=daftar_info">Daftar Informasi</a></li>
					<!-- yang ini tambahan terbaru mas tanggal 9/12/2013-->
					<li <?php setActiveMenu('pengaturan_lain_lain');?>><a href="main.php?s=pengaturan_lain_lain">Pengaturan Link</a></li>
					<li <?php setActiveMenu('pengaturan_umum');?>><a href="main.php?s=pengaturan_umum">Pengaturan Umum</a></li>
				
				<?php } ?>
			</ul>
		
		</div>
		
		
		
		<div id="content">
			<div id="content-header">
				<h1><?php getTitle();?></h1>
			</div>
			<div id="breadcrumb">
				<!--<a href="#" title="Go to Home" class="tip-bottom"><i class="glyphicon glyphicon-home"></i> Home</a>
				<a href="#" class="current">Dashboard</a>-->
			</div>
			<div class="container-fluid">
            <div class="row">
			
              <?php
			  if(isset($_SESSION['level']) && ($_SESSION['level']==99)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'form_dana_zakat_s'){
						include"component/file/form_dana_zakat_s.php";
					}else if($_GET['s'] == 'dashboard'){
						include"component/file/dashboard.php";
					}else if($_GET['s'] == 'form_amilin'){
						include"component/file/form_amilin.php";
					}else if($_GET['s'] == 'form_muzakki'){
						include"component/file/form_muzakki.php";
					}else if($_GET['s'] == 'form_mustahik'){
						include"component/file/form_mustahik.php";
					}else if($_GET['s'] == 'editmuzakki'){
						include"component/file/form_muzakki.php";
					}else if($_GET['s'] == 'daftar_muzakki'){
						include"component/file/daftar_muzakki.php";
					}else if($_GET['s'] == 'edit_amilin'){
						include"component/file/form_amilin.php";
					}else if($_GET['s'] == 'view_amilin'){
						include"component/file/view_amilin.php";
					}else if($_GET['s'] == 'editmustahik'){
						include"component/file/form_mustahik.php";
					}else if($_GET['s'] == 'daftarmustahik'){
						include"component/file/daftarmustahik.php";
					}else if($_GET['s'] == 'form_akun'){
						include"component/file/form_akun.php";
					}else if($_GET['s'] == 'daftarakun'){
						include"component/file/daftarakun.php";
					}else if($_GET['s'] == 'editakun'){
						include"component/file/form_akun.php";
					}else if($_GET['s'] == 'form_info_baru'){
						include"component/file/form_info_baru.php";
					}else if($_GET['s'] == 'daftar_info'){
						include"component/file/daftar_info.php";
					}else if($_GET['s'] == 'edit_info'){
						include"component/file/form_info_baru.php";
					}else if($_GET['s'] == 'hapus_info'){
						include"component/file/hapus_info_baru.php";
					}else if($_GET['s'] == 'form_penerimaan'){
						include"component/file/form_penerimaan.php";
					}else if($_GET['s'] == 'daftar_penerimaan'){
						include"component/file/daftar_penerimaan.php";
					}else if($_GET['s'] == 'detail_bulanan'){
						include"component/file/detail_bulanan.php";
					}else if($_GET['s'] == 'daftar_penyaluran'){
						include"component/file/daftar_penyaluran.php";
					}else if($_GET['s'] == 'detail_bulanan2'){
						include"component/file/detail_bulanan2.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}else if($_GET['s'] == 'opsi_ramadhan'){
						include"component/file/opsi_ramadhan.php";
					}else if($_GET['s'] == 'penerimaan_ramadhan'){
						include"component/file/form_penerimaan_ramadhan.php";
					}else if($_GET['s'] == 'penyaluran_ramadhan'){
						include"component/file/form_penyaluran_ramadhan.php";
					}
					else if($_GET['s'] == 'daftar_penerimaan_ramadhan'){
						include"component/file/daftar_penerimaan_ramadhan.php";
					}else if($_GET['s'] == 'daftar_penyaluran_ramadhan'){
						include"component/file/daftar_penyaluran_ramadhan.php";
					}else if($_GET['s'] == 'salur_kubah'){
						include"component/file/form_salur_kubah.php";
					}else if($_GET['s'] == 'cicil_kubah'){
						include"component/file/form_cicilan_kubah.php";
					}else if($_GET['s'] == 'transaksi_kubah'){
						include"component/file/daftar_transaksi_kubah.php";
					}else if($_GET['s'] == 'daftar_ukm'){
						include"component/file/daftar_ukm.php";
					}else if($_GET['s'] == 'form_ukm'){
						include"component/file/form_ukm.php";
					}else if($_GET['s'] == 'lapbulan'){
						include"component/file/lapbulan1.php";
					}else if($_GET['s'] == 'lapbulanan'){
						include"component/file/lapbulanan.php";
					}
					
					else if($_GET['s'] == 'lapbulan_ramadhan'){
						include"component/file/lapbulan_ramadhan.php";
					}else if($_GET['s'] == 'laporan_ramadhan'){
						include"component/file/lapbulanan_ramadhan.php";
					}else if($_GET['s'] == 'lapblndetail_ramadhan'){
						include"component/file/lapblndetail_ramadhan.php";
					}else if($_GET['s'] == 'lapkubahth'){
						include"component/file/lapkubahth.php";
					}
					
					
					//yang ini yang tambahan mas
					else if($_GET['s'] == 'form_pengeluaran'){
						include"component/file/form_pengeluaran.php";
					}else if($_GET['s'] == 'pengaturan_bus'){
						include"component/file/pengaturan_bus.php";
					}else if($_GET['s'] == 'pengaturan_saldo'){
						include"component/file/pengaturan_saldo.php";
					}else if($_GET['s'] == 'pengaturan_umum'){
						include"component/file/pengaturan_umum.php";
					}
					//yang di edit
					else if($_GET['s'] == 'edit_ukm'){
						include"component/file/form_ukm.php";
					}
					else if($_GET['s'] == 'view_messages'){
						include"component/file/view_messages.php";
					}
					else if($_GET['s'] == 'view_alert'){
						include"component/file/view_alert.php";
					}
					else if($_GET['s'] == 'akun_pengeluaran'){
						include"component/file/form_akun_pengeluaran.php";
					}else if($_GET['s'] == 'daftar_sms'){
						include"component/file/daftar_sms.php";
					}
					else if($_GET['s'] == 'lapblncetak'){
						include"component/file/lapblncetak.php";
					}else if($_GET['s'] == 'lapdetailcetak'){
						include"component/file/lapdetailcetak.php";
					}else if($_GET['s'] == 'lapblndetail'){
						include"component/file/lapblndetail.php";
					}else if($_GET['s'] == 'lapakun'){
						include"component/file/lapakun.php";
					}else if($_GET['s'] == 'laplistakun'){
						include"component/file/laplistakun.php";
					}
					
					else if($_GET['s'] == 'persamaan_akun'){
						include"component/file/persamaan_akun.php";
					}
					else if($_GET['s'] == 'transaksi_kubah_detail'){
						include"component/file/transaksi_kubah_detail.php";
					}else if($_GET['s'] == 'kirim_sms'){
						include"component/file/kirim_sms.php";
					}
					else if($_GET['s'] == 'edit_akun'){
						include"component/file/form_akun.php";
					}
					//yang ini baru diubah
					else if($_GET['s'] == 'edit_akun_pengeluaran'){
						include"component/file/form_akun_pengeluaran.php";
					}
					else if($_GET['s'] == 'daftar_akun_lain'){
						include"component/file/daftar_akun_lain.php";
					}else if($_GET['s'] == 'form_sabab'){
						include"component/file/form_sabab.php";
					}
					else if($_GET['s'] == 'edit_sabab'){
						include"component/file/form_sabab.php";
					}
					else if($_GET['s'] == 'daftar_sabab'){
						include"component/file/daftar_sabab.php";
					}else if($_GET['s'] == 'lihat_detail_sabab'){
						include"component/file/lihat_detail_sabab.php";
					}
					
					///terbaruuuuuuuuuuuuuuuuu brooooooooooooooo
					else if($_GET['s'] == 'pengaturan_lain_lain'){
						include"component/file/pengaturan_lain_lain.php";
					}else if($_GET['s'] == 'ubah_akun_pribadi'){
						include"component/file/ubah_akun_admin.php";
					}
					
					//baruuuuuuuuuuuuuuuuuuuu cetak_detail_bus_wilayah.php
					else if($_GET['s'] == 'daftar_peserta_bus'){
						include"component/file/daftar_peserta_bus.php";
					}else if($_GET['s'] == 'daftar_peserta_bus_wilayah'){
						include"component/file/daftar_peserta_bus_wilayah.php";
					}else if($_GET['s'] == 'cetak_detail_bus_wilayah'){
						include"component/server/cetak_detail_bus_wilayah.php";
					}else if($_GET['s'] == 'status_bus'){
						include"component/server/status_bus.php";
					}
					
					else if($_GET['s'] == 'cetak_detail_bus'){
						include"component/server/cetak_detail_bus.php";
					}else if($_GET['s'] == 'detailbus'){
							include"component/file/detailbus.php";
					}else if($_GET['s'] == 'daftar_peserta_wilayah'){
							include"component/file/daftar_peserta_wilayah.php";
					}//////////////////////////////////////
					else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
			}elseif(isset($_SESSION['level']) && ($_SESSION['level']== 1)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}else if($_GET['s'] == 'lapbulan'){
						include"component/file/lapbulan1.php";
					}else if($_GET['s'] == 'lapbulanan'){
						include"component/file/lapbulanan.php";
					}else if($_GET['s'] == 'lapblndetail'){
						include"component/file/lapblndetail.php";
					}else if($_GET['s'] == 'zakatku'){
							include"component/file/zakatku.php";
					}else if($_GET['s'] == 'ubah_akun_zakat'){
							include"component/file/ubah_akun_zakat.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
			}elseif(isset($_SESSION['level']) && ($_SESSION['level']== 3)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}elseif($_GET['s'] == 'addbus'){
						include "component/file/addbus.php";
					}elseif($_GET['s'] == 'viewbus'){
						include "component/file/viewbus.php";
					}else if($_GET['s'] == 'lapbus'){
						include "component/file/lapbus.php";
					}else if($_GET['s'] == 'addoutbus'){
						include "component/file/addoutbus.php";
					}else if($_GET['s'] == 'editbus'){
							include"component/file/addbus.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'detailbus'){
							include"component/file/detailbus.php";
					}
					else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
			}elseif(isset($_SESSION['level']) && ($_SESSION['level']== 2)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}else if($_GET['s'] == 'lapukm_ukm'){
						include"component/file/lapukm_ukm.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'ubah_akun_ukm'){
						include"component/file/ubah_akun_ukm.php";
					}
					
					else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
				
			//yang ini yang baru diubah lagi
			}else if(isset($_SESSION['level']) && ($_SESSION['level']== 4)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}else if($_GET['s'] == 'lihat_detail_sabab'){
						include"component/file/lihat_detail_sabab.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'ubah_akun_sabab'){
							include"component/file/ubah_akun_sabab.php";
					}
					else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
			}else if(isset($_SESSION['level']) && ($_SESSION['level']== 66)){
				if(ISSET($_GET['s'])){
					if($_GET['s'] == 'home'){
						include"component/file/home.php";
					}else if($_GET['s'] == 'pengaturan_umum'){
						include"component/file/pengaturan_umum.php";
					}else if($_GET['s'] == 'form_info_baru'){
						include"component/file/form_info_baru.php";
					}else if($_GET['s'] == 'daftar_info'){
						include"component/file/daftar_info.php";
					}else if($_GET['s'] == 'edit_info'){
						include"component/file/form_info_baru.php";
					}else if($_GET['s'] == 'hapus_info'){
						include"component/file/hapus_info_baru.php";
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'pengaturan_lain_lain'){
						include"component/file/pengaturan_lain_lain.php";
					}else if($_GET['s'] == 'ubah_akun_pribadi'){
						include"component/file/ubah_akun_admin.php";
					}
					else{
						echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
					}
				}else{
					echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
				}
			}else{
				echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
			}
			?>
            </div>
            <!-- End .content -->

				
		</div>

		</div>
		<div class="row">
			<div id="footer" class="col-12">
				Copyright &copy; <?php echo date('Y')?> - Undip
			</div>
		</div>

            
            <script src="js/jquery-ui.custom.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/fullcalendar.min.js"></script>
            <script src="js/jquery.jpanelmenu.min.js"></script>
            <script src="js/unicorn.js"></script>
            <script src="js/unicorn.form_common.js"></script>
			
         
            <script src="js/bootstrap-colorpicker.js"></script>
            <script src="js/bootstrap-datepicker.js"></script>
            <script src="js/jquery.icheck.min.js"></script>
            <script src="js/select2.min.js"></script>
            <script src="js/jquery.dataTables.min.js"></script>
            <script src="js/unicorn.tables.js"></script>
			
            <script src="js/jquery.treeview.js"></script>
			 <script src="js/unicorn.tree.js"></script>
			<script src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
				tinyMCE.init({
					// General options
					//mode : "textareas",
					theme : "advanced",
					selector : "textarea.editme",
					plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

					// Theme options
					theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,image,|,insertdate,inserttime,preview,|,forecolor",
					theme_advanced_buttons2 : "",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,

					// Example content CSS (should be your site CSS)
					// using false to ensure that the default browser settings are used for best Accessibility
					// ACCESSIBILITY SETTINGS
					content_css : false,
					// Use browser preferred colors for dialogs.
					browser_preferred_colors : true,
					detect_highcontrast : true,

					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",

					// Style formats
					style_formats : [
						{title : 'Bold text', inline : 'b'},
						{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
						{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
						{title : 'Example 1', inline : 'span', classes : 'example1'},
						{title : 'Example 2', inline : 'span', classes : 'example2'},
						{title : 'Table styles'},
						{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
					],

					// Replace values for the template plugin
					template_replace_values : {
						username : "Some User",
						staffid : "991234"
					}
				});
				
				$(selector).chosen(config[selector]);
			</script>
</BoDy ><!-- Menghindari injeksi < /body > pada provider telkom -->
</hTmL >
