<div id="sidebar">
	<!--<a href="#" class="hide"><i class="glyphicon glyphicon-home"></i> Dashboard</a>-->
	<ul>
<?php if($_SESSION['level']==99){ //================== JIKA ADMIN ===================== ?>
		<li <?php setActiveMenu('home');?>><a href="main.php?s=home"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a></li>
		<li <?php echo setActiveOpen(array('form_info_baru','daftar_info','edit_info','kirim_sms','daftar_sms','pengaturan_lain_lain'));?>>
			<a href="#"><i class="glyphicon glyphicon-info-sign"></i> <span>Informasi</span></a>
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
				  <li <?php setActiveMenu('bank');?>><a href="main.php?s=transaksi&amp;action=bank">Daftar Akun Bank</a></li>
				 <!-- <li><a href="">Menu3</a></li>
				  <li><a href="">Menu4</a></li>-->
			</ul>
		</li>
		
		<li <?php echo setActiveOpen(array('transaksi','form_penerimaan','daftar_penerimaan','detail_bulanan','form_dana_zakat_s','daftar_penyaluran','detail_bulanan2','form_pengeluaran','lapbulanan','lapakun','lapbulan','lapblndetail','laplistakun'));?>>
			<a href="#"><i class="glyphicon glyphicon-transfer"></i> <span>Transaksi Harian</span> <span class="label">5</span></a>
			<ul>
				<li <?php setActiveMenu('form_penerimaan');?>><a href="main.php?s=form_penerimaan">Tambah Transaksi Penerimaan</a></li>
				<li <?php setActiveMenu('form_dana_zakat_s');?>><a href="main.php?s=form_dana_zakat_s">Tambah Transaksi Penyaluran</a></li>
				
						<li <?php setActiveMenu('form_pengeluaran');?>><a href="main.php?s=form_pengeluaran">Tambah Transaksi Penyaluran Lain - lain</a></li>
						
				<!--<li <?php setActiveMenu('daftar_penerimaan');?>><a href="main.php?s=daftar_penerimaan&q=1&th=<?php echo date('Y');?>">Daftar Penerimaan</a></li>
				
				  <li <?php setActiveMenu('daftar_penyaluran');?>><a href="main.php?s=daftar_penyaluran&q=2&th=<?php echo date('Y');?>">Daftar Penyaluran</a></li> -->
				  <li <?php setActiveMenu('lapbulanan'); setActiveMenu('lapbulan');setActiveMenu('lapblndetail');?>><a href="main.php?s=lapbulanan">Laporan Bulanan</a></li>
				  <li <?php setActiveMenu('lapakun');setActiveMenu('laplistakun');?>><a href="main.php?s=lapakun">Laporan Perakun</a></li>
				  <li <?php setActiveMenu('transaksi');?>><a href="main.php?s=transaksi"><i class="glyphicon glyphicon-hdd"></i> Mutasi Transaksi</a></li>
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
			<a href="#"><i class="glyphicon glyphicon-credit-card"></i> <span>Transaksi Kubah</span> <span class="label">5</span></a>
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
			<a href="#"><i class="glyphicon glyphicon-user"></i> <span>Manajemen BUS</span> <span class="label">2</span></a>
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
			<a href="#"><i class="glyphicon glyphicon-wrench"></i> <span>Pengaturan</span> <span class="label">6</span></a>
			<ul>
				
				  <li <?php setActiveMenu('pengaturan_umum');?>><a href="main.php?s=pengaturan_umum">Pengaturan Umum</a></li>
				
				  <li <?php setActiveMenu('pengaturan_bus');?>><a href="main.php?s=pengaturan_bus">Pengaturan Dana BUS</a></li>
				  <li <?php setActiveMenu('pengaturan_saldo');?>><a href="main.php?s=pengaturan_saldo">Pengaturan Saldo Awal</a></li>
				  <li <?php setActiveMenu('persamaan_akun');?>><a href="main.php?s=persamaan_akun">Pengaturan Persamaan Akun</a></li>
				  <li <?php setActiveMenu('opsi_ramadhan');?>><a href="main.php?s=opsi_ramadhan">Pengaturan Ramadhan</a></li>
				<li <?php setActiveMenu('ubah_akun_pribadi');?>><a href="main.php?s=ubah_akun_pribadi">Ubah Akun user : <?php echo $_SESSION['username'];?></a></li>
				
			</ul>
		</li>
		
		<!-- MODUL PERENCANAAN ANGGARAN -->
		<li <?php echo setActiveOpen(array('perencanaan'));?>>
			<a href="#"><i class="glyphicon glyphicon-star"></i> <span>Perencanaan</span> <span class="label">6</span></a>
			<ul>
				
				  <li <?php setActiveMenu('perencanaan');?>><a href="main.php?s=perencanaan">Perencanaan Anggaran</a></li>
				  <li <?php setActiveMenu('perencanaan','list');?>><a href="main.php?s=perencanaan&amp;action=list">Master Kegiatan</a></li>

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