<?php
	
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('FCPATH', str_replace(SELF, '', __FILE__));
	define('COMPONENT_PATH', FCPATH."component");

	// Untuk menghitung waktu eksekusi
	$timeStart = microtime(true);
	$queryCount = 0;
	
	session_start();
	
	require_once COMPONENT_PATH."/config/koneksi.php";
	require_once COMPONENT_PATH."/libraries/injection.php";
	
	$breadCrumbPath = array();
	$errorDescription = "";
	
	//================= AJAX HANDLER ====
	if ($_GET['s']=='ajax') {
		if (!isset($_SESSION['username']))
			die("Access denied.");
		
		if ($_GET['m'] == 'perencanaan') {
			include COMPONENT_PATH."/modules/perencanaan/ajax.php";
		} else if ($_GET['m'] == 'transaksi') {
			include COMPONENT_PATH."/file/transaksi_harian/ajax.php";
		} else if ($_GET['m'] == 'akun') {
			include COMPONENT_PATH."/file/akun/ajax.php";
		} else if ($_GET['m'] == 'user') {
			include COMPONENT_PATH."/file/user/ajax.php";
		} else {
			die("Module not found!");
		}
		exit;
	}
	
	// Jika berlum login, maka tidak boleh mengakses
	if ((!isset($_SESSION['username']))AND (!isset($_SESSION['password']))){
		header("Location: index.php?s=login&next=".urlencode($_SERVER['REQUEST_URI']));
		exit;
	}

	function setActiveMenu($link, $action = null){
		$get = clear_injection(@$_GET['s']);
		$act = $_GET['action'];
		if (($link == $get)&&($act==$action)){
			echo "class='active'";
		}
	}
	
	function setActiveOpen($link){
		$get = clear_injection(@$_GET['s']);
		if(in_array($get,$link)){
			echo "class='submenu active'";
		}else{
			echo "class='submenu'";
		}
	}
	
	function getTitle(){
		if(ISSET($_GET['s'])){
					if($_GET['s'] == 'form_dana_zakat_s'){
						return "Tambah Transaksi Penyaluran";
					}else if($_GET['s'] == 'dashboard'){
						return "Dasboard";
					}else if($_GET['s'] == 'form_amilin'){
						return "Tambah Data Amilin";
					}else if($_GET['s'] == 'form_muzakki'){
						return "Tambah Data Muzakki";
					}else if($_GET['s'] == 'form_mustahik'){
						return "Tambah Data Mustahik";
					}else if($_GET['s'] == 'editmuzakki'){
						return "Ubah Data Muzakki";
					}else if($_GET['s'] == 'daftar_muzakki'){
						return "Daftar Muzakki";
					}else if($_GET['s'] == 'edit_amilin'){
						return "Ubah Data Amilin";
					}else if($_GET['s'] == 'view_amilin'){
						return "Daftar Amilin";
					}else if($_GET['s'] == 'editmustahik'){
						return "Ubah Data Mustahik";
					}else if($_GET['s'] == 'daftarmustahik'){
						return "Daftar Mustahik";
					}else if($_GET['s'] == 'form_akun'){
						return "Tambah Akun Baru";
					}else if($_GET['s'] == 'daftarakun'){
						return "Daftar Akun";
					}else if($_GET['s'] == 'editakun'){
						return "Ubah Akun";
					}else if($_GET['s'] == 'form_info_baru'){
						return "Tambah Informasi Baru";
					}else if($_GET['s'] == 'daftar_info'){
						return "Daftar Informasi";
					}else if($_GET['s'] == 'edit_info'){
						return "Ubah Informasi";
					}else if($_GET['s'] == 'hapus_info'){
						return "Hapus Informasi";
					}else if($_GET['s'] == 'form_penerimaan'){
						return "Tambah Transaksi Penerimaan";
					}else if($_GET['s'] == 'daftar_penerimaan'){
						return "Daftar Transaksi penerimaan";
					}else if($_GET['s'] == 'detail_bulanan'){
						return "Detail Transaksi Bulan ".getBulan($_GET['bulan']);
					}else if($_GET['s'] == 'daftar_penyaluran'){
						return "Daftar Transaksi Penyaluran";
					}else if($_GET['s'] == 'detail_bulanan2'){
						return "Detai Transaksi Bulan ".getBulan($_GET['bulan']);
					}else if($_GET['s'] == 'logout'){
						include"component/server/logout.php";
					}else if($_GET['s'] == 'home'){
						return "Beranda";
					}else if($_GET['s'] == 'zakatku'){
						return "Daftar Donasiku";
					}else if($_GET['s'] == 'rekapzakat'){
						return "Rekapitulasi Donasi";
					}else if($_GET['s'] == 'penerimaan_ramadhan'){
						return "Tambah Penerimaan Ramadhan";
					}else if($_GET['s'] == 'penyaluran_ramadhan'){
						return "Tambah Penyaluran Ramadhan";
					}else if($_GET['s'] == 'daftar_penerimaan_ramadhan'){
						return "Daftar Penerimaan Ramadhan";
					}else if($_GET['s'] == 'daftar_penyaluran_ramadhan'){
						return "Daftar Penyaluran Ramadhan";
					}else if($_GET['s'] == 'opsi_ramadhan'){
						return "Opsi Ramadhan";
					}
					//yang ini yang tambahan mas
					else if($_GET['s'] == 'form_pengeluaran'){
						return "Tambah Transaksi Pengeluaran";
					}else if($_GET['s'] == 'pengaturan_bus'){
						return "Pengaturan Jumlah Dana BUS";
					}else if($_GET['s'] == 'pengaturan_saldo'){
						return "Pengaturan Saldo Awal";
					}
					//baru di edit
					else if($_GET['s'] == 'daftar_ukm'){
						return "Daftar UKM";
					}else if($_GET['s'] == 'form_ukm'){
						return "Tambah UKM";
					}else if($_GET['s'] == 'edit_ukm'){
						return "Edit UKM";
					}else if($_GET['s'] == 'salur_kubah'){
						return "Tambah Transaksi Penyaluran Kubah";
					}else if($_GET['s'] == 'cicil_kubah'){
						return "Tambah Transaksi Cicilan Kubah";
					}else if($_GET['s'] == 'transaksi_kubah'){
						return "Daftar Transaksi Kubah";
					}		
					else if($_GET['s'] == 'ubah_akun_zakat'){
						return "Ubah Akun";
					}else if($_GET['s'] == 'ubah_akun_bus'){
						return "Ubah Akun";
					}//yang ini baru diubah
					else if($_GET['s'] == 'akun_pengeluaran'){
						return "Tambah Akun Lain-Lain";
					}else if($_GET['s'] == 'daftar_akun_lain'){
						return "Daftar Akun Lain-Lain";
					}else if($_GET['s'] == 'edit_akun_pengeluaran'){
						return "Ubah Akun";
					}
					else if($_GET['s'] == 'form_sabab'){
						return "Tambah Sabab";
					}else if($_GET['s'] == 'daftar_sabab'){
						return "Daftar Sabab";
					}else if($_GET['s'] == 'edit_sabab'){
						return "Ubah Data Sabab";
					}else if($_GET['s'] == 'lihat_detail_sabab'){
						return "Detail Data Sabab";
					}
					//terbaru 9/12/2013
					else if($_GET['s'] == 'pengaturan_lain_lain'){
						return "Pengaturan lain lain";
					}else if($_GET['s'] == 'ubah_akun_pribadi'){
						return "Ubah Akun Pribadi";
					}
					
					
					
					else if($_GET['s'] == 'ubah_akun_sabab'){
						return "Ubah Data Pribadi";
					}
					
					//baruuuuuuuuuuuuuuuuuuuuu
					else if($_GET['s'] == 'daftar_peserta_bus'){
						return "Daftar Peserta Bus";
					}
					else{
						return "";
					}
				}
	}
	
	function getBulan($bln){
		$month = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
		return ((ISSET($month[$bln])) &&($month[$bln] != ""))?$month[$bln]:"";
	}
	
	// Start buffering...
	ob_start();
	include COMPONENT_PATH."/skin/backend_header.php";
	include COMPONENT_PATH."/skin/backend_sidebar.php";
?>
		
		<div id="content">
			<div id="content-header">
				<h1><i class="glyphicon glyphicon-edit"></i> %PAGE_TITLE%</h1>
				<div class="btn-group">
					<a class="btn btn-large" title="Tambah Transaksi Penerimaan" href="<?php
						echo htmlspecialchars("main.php?s=form_penerimaan");
					?>"><i class="glyphicon glyphicon-plus"></i> Penerimaan</a>
					<a class="btn btn-large" title="Tambah Transaksi Penyaluran/Pengeluaran" href="<?php
						echo htmlspecialchars("main.php?s=form_dana_zakat_s");
					?>">
						<i class="glyphicon glyphicon-plus"></i> Pengeluaran</a>
					<a class="btn btn-large" title="Laporan Keuangan">
						<i class="glyphicon glyphicon-list-alt"></i></a>
					<a class="btn btn-large" title="Arsip">
						<i class="glyphicon glyphicon-folder-open"></i></a>
				</div>
			</div>
			<div id="breadcrumb">
			%PAGE_BREADCRUMB%
			</div>
			<div class="container-fluid">
            <div class="row" style="margin-top: 10px;">
			
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
						include"component/file/akun/persamaan_akun.php";
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
					
					// =========== Page tambahan | By Muh. Nur Hardyanto ===
					}else if($_GET['s'] == 'transaksi'){
						include"component/file/transaksi_harian/router_transaksi.php";
					}else if($_GET['s'] == 'donatur'){
						include "component/file/user/detil_user.php";
					}else if($_GET['s'] == 'akun'){
						include "component/file/akun/router_akun.php";
						
					// =========== MODUL PERENCANAAN ===========
					}else if($_GET['s'] == 'perencanaan'){
						include "component/modules/perencanaan.php";
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
			</div>

		</div><!-- End .content -->
<?php include COMPONENT_PATH."/skin/backend_footer.php";

	if (empty($SIZPageTitle)) $SIZPageTitle = getTitle();
	
	//========= BREADCRUMB for navigation ==============
	if ($_GET['s']=="home") {
		$SIZBreadCrumb .= "<a class=\"current\" href=\"\">";
		$SIZBreadCrumb .=  "<i class=\"glyphicon glyphicon-home\"></i> Home</a>";
	} else {
		$SIZBreadCrumb .=  "<a href=\"main.php?s=home\" title=\"Go to Home\" class=\"tip-bottom\">";
		$SIZBreadCrumb .=  "<i class=\"glyphicon glyphicon-home\"></i> Home</a>";
	}
	
	foreach ($breadCrumbPath as $pathItem) {
		$SIZBreadCrumb .= "<a href=\"".$pathItem[1]."\" ";
		$SIZBreadCrumb .= ($pathItem[2]?"class=\"current\"":"").">".$pathItem[0]."</a>\n";
	}

	$pageOutput = ob_get_contents();
	ob_end_clean();
	
	$pageOutput = str_replace("%PAGE_TITLE%", $SIZPageTitle, $pageOutput);
	$pageOutput = str_replace("%PAGE_BREADCRUMB%", $SIZBreadCrumb, $pageOutput);
	echo $pageOutput;

?>