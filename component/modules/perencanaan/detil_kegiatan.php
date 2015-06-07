<?php
/*
 * detil_kegiatan.php
 * ==> Menampilkan list agenda dan editor rincian untuk kegiatan tertentu
 * 
 * AM_SIZ_RA_DTLKGT | Detil Kegiatan
 * ------------------------------------------------------------------------
 * o Baris rincian, juga edit di ajax/agenda_simpan_rincian.php
 */
	
	// Cek privilege
	if (!ra_check_privilege()) return;
	
	// Query untuk keterangan kegiatan
	$queryKegiatan = sprintf("SELECT * FROM ra_kegiatan WHERE id_kegiatan=%d", $idKegiatan);
	$queryResult = mysqli_query($mysqli, $queryKegiatan);
	$queryCount++;
	if ($queryResult == null) {
		show_error_page( "Terjadi kesalahan: ".mysqli_error($queryResult) );
		return;
	} else if (mysqli_num_rows($queryResult) == 0) {
		show_error_page( "Kegiatan tidak ditemukan!" );
		return;
	}
	$dataKegiatan = mysqli_fetch_array($queryResult);
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$isAuthorized	= $isAdmin || ($divisiUser == $dataKegiatan['divisi']);
	
	// Query untuk list agenda kegiatan
	$queryAgenda = sprintf(
		"SELECT *, MONTH(tgl_mulai) AS bulan FROM ra_agenda WHERE id_kegiatan=%d AND YEAR(tgl_mulai)=%d ORDER BY tgl_mulai",
		$idKegiatan, $tahunDokumen
	);
	$listAgenda = mysqli_query($mysqli, $queryAgenda);
	$queryCount++;
	
	// Query untuk mendapatkan nama akun pengeluaran
	$queryResolve = sprintf("SELECT idakun, kode, namaakun FROM akun WHERE kode='%s'",
		$dataKegiatan['akun_pengeluaran']);
	$dataAkunPengeluaran = $mysqli->query($queryResolve);
	$queryCount++;
	
	$namaAkunPengeluaran = "- Undefined -";
	if ($dataAkunPengeluaran != null) {
		$rowDataAkun = $dataAkunPengeluaran->fetch_array(MYSQLI_ASSOC);
		if ($rowDataAkun != null) {
			$namaAkunPengeluaran = "<a href=\"".
				htmlspecialchars("main.php?s=akun&action=detail&id=".$rowDataAkun['idakun'])."\">". 
				$rowDataAkun['kode']. " " . $rowDataAkun['namaakun']."</a>";
		}
	} else {
		show_error_page("Terjadi kesalahan internal. Deskripsi: ".$mysqli->error);
		return;
	}
	
	// Query untuk list sumber dana
	$querySumber = sprintf(
		"SELECT a.namaakun AS namaakun, a.idakun AS idakun, p.* ".
		"FROM persamaan_akun AS p, akun as a ".
		"WHERE p.id_penerimaan=a.kode AND p.id_penyaluran='%s' ",
		$dataKegiatan['akun_pengeluaran']
	);
	$resultQuerySumber = mysqli_query($mysqli, $querySumber);
	$queryCount++;
	
	$namaDivisi = (isset($listDivisi[$dataKegiatan['divisi']])?
					$listDivisi[$dataKegiatan['divisi']]:
					"- Unknown -");
?>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Rekapitulasi Kegiatan <b><?php echo htmlspecialchars($dataKegiatan['nama_kegiatan']);?></b>
				tahun <b><?php echo $tahunDokumen; ?></b></h5>
		</div>
		<div class="widget-content">
			<table class="siz-table-detail">
				<tr>
					<td>Nama Kegiatan</td>
					<td><?php echo htmlspecialchars($dataKegiatan['nama_kegiatan']); ?></td>
				</tr>
				<tr>
					<td>Akun Pengeluaran</td>
					<td><?php echo $namaAkunPengeluaran; ?></td>
				</tr>
				<tr>
					<td>Tahun</td>
					<td><?php echo $tahunDokumen; ?></td>
				</tr>
				<tr>
					<td>Divisi</td>
					<td><?php echo $namaDivisi; ?></td>
				</tr>
				<tr>
					<td>Sumber Dana Kegiatan</td>
					<td><br><?php
	if (mysqli_num_rows($resultQuerySumber) > 0) {
		while ($rowSumberDana = mysqli_fetch_assoc($resultQuerySumber)) {
			echo "<a href=\"main.php?s=akun&amp;action=detail&amp;id=".$rowSumberDana['idakun']."\" ";
			echo "target=\"_blank\"><b>".$rowSumberDana['namaakun']."</b></a>,<br>";
		}
		echo "<br>";
	} ?></td>
				</tr>
			</table>
			<hr>
			<!-- Tabel overview
			<table class="table table-bordered table-hover">
				<tr>
					<th rowspan="2">Bulan</th>
					<th colspan="31">Pelaksanaan</th>
					<th rowspan="2">Jumlah Anggaran</th>
					<th rowspan="2">Aksi</th>
				</tr>
				<tr>
					<?php for ($tgl=1;$tgl<=31;$tgl++) {
						echo "<th>".$tgl."</th>";
					} ?>
				</tr>
			<?php
				$ctrBulan = 1;
				for ($ctrBulan=1;$ctrBulan<=12;$ctrBulan++) {
					echo "<tr>\n";
					echo "<td>{$ctrBulan}</td>\n";
					$ctrTgl = 1;
					$firstDate = sprintf("%04d-%02d-01", $tahunDokumen, $ctrBulan);
					$dayOfWeek = intval(date("N", strtotime($firstDate)))-1;
					for ($ctrTgl=1;$ctrTgl<=31;$ctrTgl++) {
						echo "<td ".(($dayOfWeek==6)?"style=\"background-color: #fdd;\"":"").">&nbsp;</td>";
						$dayOfWeek = ($dayOfWeek+1)%7;
					}
					echo "<td>Jumlah</td>\n";
					echo "<td>-</td>\n";
					echo "</tr>\n";
				}
			?>
			</table>  -->
	<div class="row">
		<div class="col-lg-8">
<?php if (mysqli_num_rows($listAgenda) > 0) { //==================== JIKA ADA KEGIATAN ========== ?>
		<?php
		// Inisialisasi variabel
		$counterAgenda	= 0;
		$totalAnggaran	= 0;
		$anggaranBulan	= 0;
		
		$bulanSekarang	= 0;
		$tampilNamaBulan = false;
		
		// Hasil query sudah diurutkan per bulan, sehingga urutan dijamin
		while ($rowAgenda = mysqli_fetch_array($listAgenda)) {
			$counterAgenda++;
			
			if ($rowAgenda['bulan'] != $bulanSekarang) {
				//==== End of month
				if ($bulanSekarang != 0) {
					echo "<p><span class=\"glyphicon glyphicon-list-alt\"></span> ";
					echo "Jumlah keselurhan anggaran bulan : ";
					echo "<b class=\"siz-month-grandtotal\">".to_rupiah($anggaranBulan)."</b></p>\n";
					echo "</div>";
				}
				
				//==== Start of month
				$anggaranBulan = 0;
				$bulanSekarang = $rowAgenda['bulan'];
				
				echo "<div class=\"siz-container-month\">";
				echo "<h3><i class=\"glyphicon glyphicon-calendar\"></i> ";
				echo " Bulan ".$monthName[$bulanSekarang]."</h3>\n";
				
				$tampilNamaBulan = true;
			} else {
				$tampilNamaBulan = false;
			}
			
			//======== Mulai div agenda
			echo "<div class=\"panel panel-default\" id=\"siz_agenda_".$rowAgenda['id_agenda']."\">\n";
			echo "<div class=\"panel-heading\">";
			echo "<i class=\"glyphicon glyphicon-calendar\"></i> ";

			// Generate string tanggal agenda
			if ($rowAgenda['jenis_agenda']==1) {
				$strTanggalAgenda = tanggal_indonesia(date('n M Y', strtotime($rowAgenda['tgl_mulai'])));
			} else if ($rowAgenda['jenis_agenda']==2) {
				$strTanggalAgenda = tanggal_indonesia(date('n M Y', strtotime($rowAgenda['tgl_mulai'])));
				$strTanggalAgenda .= " sampai ". tanggal_indonesia(date('n M Y', strtotime($rowAgenda['tgl_selesai'])));
			} else {
				$strTanggalAgenda = "- undefined date -";
			}
			echo " <b>".$strTanggalAgenda."</b> ";
			// Tampilan control jika user berhak
			if ($isAuthorized) {
				echo "<small>";
				echo "<a href=\"".ra_gen_url("edit-agenda",$tahunDokumen,"id=".$rowAgenda['id_agenda']).
					"\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>\n";
				echo "<a href=\"".ra_gen_url("hapus-agenda",$tahunDokumen,"id=".$rowAgenda['id_agenda']).
				"\" class=\"red_link\" onclick=\"return hapus_agenda(".$rowAgenda['id_agenda'].");\"><span class=\"glyphicon glyphicon-trash\"></span> Hapus</a>";
				echo "</small>\n";
			}
			echo "</div>\n"; // Akhir judul agenda
			echo "<div class=\"panel-body\">\n";
			
			// Query mengambil rincian agenda
			$queryRincian = sprintf(
				"SELECT * FROM ra_rincian_agenda WHERE id_agenda=%d", $rowAgenda['id_agenda']
			);
			$resultRincian = mysqli_query($mysqli, $queryRincian);
			$queryCount++;
			
			$jumlahRincianKegiatan = 0;
			echo "</div><table class=\"table table-bordered table-hover siz-operation-table\">\n";
			echo "<thead><tr><th style=\"width: 35%;\">Nama Rincian</th><th style=\"width: 35%;\">Jumlah Rincian</th><th>Aksi</th></tr>";
			echo "</thead>";
			echo "<tbody>\n";
			if ($resultRincian != null) {
				while ($rowRincian = mysqli_fetch_array($resultRincian)) {
					// Jika mengedit ini, edit juga di javascriptnya
					$jumlahRincianKegiatan += intval($rowRincian['jumlah_anggaran']);
					echo "<tr id=\"siz_rincian_".$rowRincian['id_rincian']."\">\n";
					echo "	<td><span class=\"siz-n-rinc\">".htmlspecialchars($rowRincian['nama_rincian'])."</span></td>\n";
					echo "	<td><span class=\"siz-v-rinc\" data-nilai=\"".$rowRincian['jumlah_anggaran']."\">";
					echo to_rupiah($rowRincian['jumlah_anggaran'])."</span></td>\n";
					echo "	<td>";
					if ($isAuthorized) {
						echo "<div class=\"siz-rinc-control\"><a href=\"#\" onclick=\"return edit_rincian(".$rowRincian['id_rincian'].");\">\n";
						echo "  <span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>\n";
						echo "	    <a href=\"#\" onclick=\"return hapus_rincian(".$rowRincian['id_rincian'].");\" class=\"red_link\">\n";
						echo "  <span class=\"glyphicon glyphicon-trash\"></span> Hapus</a></div>";
					}
					echo "</td>\n</tr>\n";
				}
			}
			echo "</tbody><tfoot>";
			echo "<tr id=\"siz_tambah_rinc_ag_".$rowAgenda['id_agenda']."\">\n";
			echo "	<td><input type=\"text\" name=\"txt_rinc_n_ag_".$rowAgenda['id_agenda']."\" ";
			echo "placeholder=\"Tambah Rincian\" class=\"form-control input-sm\"></td>\n";
			echo "	<td><input type=\"text\" name=\"txt_rinc_v_ag_".$rowAgenda['id_agenda']."\" ";
			echo "placeholder=\"Jumlah Rincian\" class=\"form-control input-sm\"></td>\n";
			echo "	<td><a href=\"#\" onclick=\"return submit_rincian_tambahan(".$rowAgenda['id_agenda'].");\" ";
			echo "class=\"btn btn-sm btn-primary\"><span class=\"glyphicon glyphicon-plus\"></span> Tambah</a></td>\n";
			echo "</tr>\n";
			
			echo "<tr id=\"siz_jml_rinc_ag_".$rowAgenda['id_agenda']."\">\n";
			echo "	<td><b>Jumlah Anggaran Agenda</b></td>\n";
			echo "	<td><b class=\"siz-total-anggaran\">".to_rupiah($jumlahRincianKegiatan)."</b></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
			
			echo "</tfoot></table>\n";
			$totalAnggaran += $jumlahRincianKegiatan;
			$anggaranBulan += $jumlahRincianKegiatan;
			echo "\n</div> <!-- Akhir panel agenda -->\n";
			// ======= Akhir dari agenda
		}
		//echo "<tr><td colspan=\"5\">&nbsp;</td><td>".to_rupiah($anggaranBulan)."</td>";
		//echo "<td>&nbsp;</td></tr>\n";
		
		//echo "<tr><td colspan=\"5\"><i class=\"glyphicon glyphicon-list-alt\"></i> ";
		//echo "<b>Jumlah Anggaran</b></td><td><b>".to_rupiah($totalAnggaran)."</b></td>";
		//cho "<td>&nbsp;</td></tr>\n";
		//echo "</table>\n";
		if ($rowAgenda['bulan'] != $bulanSekarang) {
			//==== End of month
			if ($bulanSekarang != 0) {
				echo "<p><span class=\"glyphicon glyphicon-list-alt\"></span> ";
				echo "Jumlah keselurhan anggaran bulan : ";
				echo "<b class=\"siz-month-grandtotal\">".to_rupiah($anggaranBulan)."</b></p>\n";
				echo "</div>";
			}
		}
} // ==================================== END IF ?>
		</div>
	</div><!-- End row list agenda -->
		
<?php if (false) { //==================== JIKA ADA KEGIATAN ========== ?>
			<table class="table table-bordered table-hover siz-table-ra-kegiatan siz-operation-table">
				<tr>
					<th rowspan="2" style='width:30px;'>No.</th>
					<th rowspan="2">Pelaksanaan</th>
					<th colspan="3">Anggaran</th>
					<th rowspan="2">Anggaran Per bulan</th>
					<th rowspan="2">Aksi</th>
				</tr>
				<tr>
					<th>Rincian</th>
					<th>Jumlah</th>
					<th>Per Kegiatan</th>
				</tr>
		<?php
		$counterAgenda	= 0;
		$totalAnggaran	= 0;
		$anggaranBulan	= 0;
		
		$bulanSekarang	= 0;
		$tampilNamaBulan = false;
		
		while ($rowAgenda = mysqli_fetch_array($listAgenda)) {
			$counterAgenda++;
			
			if ($rowAgenda['bulan'] != $bulanSekarang) {
				if ($bulanSekarang != 0) {
					echo "<tr><td colspan=\"5\">&nbsp;</td><td>".to_rupiah($anggaranBulan)."</td>";
					echo "<td>&nbsp;</td></tr>\n";
				}
				$bulanSekarang = $rowAgenda['bulan'];
				echo "<tr><td colspan=\"7\"><i class=\"glyphicon glyphicon-calendar\"></i>\n";
				echo " <b>Bulan ".$monthName[$bulanSekarang]."</b></td></tr>\n";
				$anggaranBulan = 0;
				
				$tampilNamaBulan = true;
			} else {
				$tampilNamaBulan = false;
			}
			echo "<tr id=\"siz_agenda_".$rowAgenda['id_agenda']."\">\n";
			echo "	<td>".$counterAgenda."</td>\n";
			if ($rowAgenda['jenis_agenda']==1) {
				$strTanggalAgenda = tanggal_indonesia(date('d M Y', strtotime($rowAgenda['tgl_mulai'])));
			} else if ($rowAgenda['jenis_agenda']==2) {
				$strTanggalAgenda = tanggal_indonesia(date('d M Y', strtotime($rowAgenda['tgl_mulai'])));
				$strTanggalAgenda .= " sampai ". tanggal_indonesia(date('d M Y', strtotime($rowAgenda['tgl_selesai'])));
			} else {
				$strTanggalAgenda = "- undefined date -";
			}
			echo "	<td colspan=\"4\">".$strTanggalAgenda." ";
			if ($isAuthorized) {
				echo "<small>";
				echo "<a href=\"".ra_gen_url("edit-agenda",$tahunDokumen,"id=".$rowAgenda['id_agenda']).
					"\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>\n";
				echo "<a href=\"".ra_gen_url("hapus-agenda",$tahunDokumen,"id=".$rowAgenda['id_agenda']).
				"\" class=\"red_link\" onclick=\"return hapus_agenda(".$rowAgenda['id_agenda'].");\"><span class=\"glyphicon glyphicon-trash\"></span> Hapus</a>";
				echo "</small>";
			}
			echo "</td>\n";
			echo "	<td>".$rowAgenda['tanggal']."</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
			
			$queryRincian = sprintf(
				"SELECT * FROM ra_rincian_agenda WHERE id_agenda=%d", $rowAgenda['id_agenda']
			);
			$resultRincian = mysqli_query($mysqli, $queryRincian);
			$queryCount++;
			
			$jumlahRincianKegiatan = 0;
			if ($resultRincian != null) {
				while ($rowRincian = mysqli_fetch_array($resultRincian)) {
					$jumlahRincianKegiatan += intval($rowRincian['jumlah_anggaran']);
					echo "<tr id=\"siz_rincian_".$rowRincian['id_rincian']."\">\n";
					echo "  <td>&nbsp;</td><td>&nbsp;</td>\n";
					echo "	<td><span class=\"siz-n-rinc\">".htmlspecialchars($rowRincian['nama_rincian'])."</span></td>\n";
					echo "	<td><span class=\"siz-v-rinc\" data-nilai=\"".$rowRincian['jumlah_anggaran']."\">";
					echo to_rupiah($rowRincian['jumlah_anggaran'])."</span></td>\n";
					echo "	<td><div class=\"siz-rinc-control\"><a href=\"#\" onclick=\"return edit_rincian(".$rowRincian['id_rincian'].");\">\n";
					echo "  <span class=\"glyphicon glyphicon-pencil\"></span> Edit</a>\n";
					echo "	    <a href=\"#\" onclick=\"return hapus_rincian(".$rowRincian['id_rincian'].");\" class=\"red_link\">\n";
					echo "  <span class=\"glyphicon glyphicon-trash\"></span> Hapus</a></div></td>\n";
					echo "	<td>&nbsp;</td>\n";
					echo "	<td>&nbsp;</td>\n";
					echo "</tr>\n";
				}
			}
			echo "<tr id=\"siz_tambah_rinc_ag_".$rowAgenda['id_agenda']."\">\n";
			echo "  <td>&nbsp;</td><td>&nbsp;</td>\n";
			echo "	<td><input type=\"text\" name=\"txt_rinc_n_ag_".$rowAgenda['id_agenda']."\" ";
			echo "placeholder=\"Tambah Rincian\" class=\"form-control input-sm\"></td>\n";
			echo "	<td><input type=\"text\" name=\"txt_rinc_v_ag_".$rowAgenda['id_agenda']."\" ";
			echo "placeholder=\"Jumlah Rincian\" class=\"form-control input-sm\"></td>\n";
			echo "	<td><a href=\"#\" onclick=\"return submit_rincian_tambahan(".$rowAgenda['id_agenda'].");\" ";
			echo "class=\"btn btn-sm btn-primary\">Tambah</a></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
			echo "<tr id=\"siz_jml_rinc_ag_".$rowAgenda['id_agenda']."\">\n";
			echo "  <td>&nbsp;</td><td>&nbsp;</td>\n";
			echo "	<td><b>Jumlah</b></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>".to_rupiah($jumlahRincianKegiatan)."</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
			$totalAnggaran += $jumlahRincianKegiatan;
			$anggaranBulan += $jumlahRincianKegiatan;
			
		}
		echo "<tr><td colspan=\"5\">&nbsp;</td><td>".to_rupiah($anggaranBulan)."</td>";
		echo "<td>&nbsp;</td></tr>\n";
		
		echo "<tr><td colspan=\"5\"><i class=\"glyphicon glyphicon-list-alt\"></i> ";
		echo "<b>Jumlah Anggaran</b></td><td><b>".to_rupiah($totalAnggaran)."</b></td>";
		echo "<td>&nbsp;</td></tr>\n";
		echo "</table>\n";
} // ==================================== END IF
		?>
			
			<a href="<?php echo ra_gen_url('tambah-agenda', $tahunDokumen, "idk=".$idKegiatan); ?>"
				class="btn btn-primary tip-right"
				title="Tambah agenda kegiatan baru pada tanggal tertentu">
				<span class="glyphicon glyphicon-plus"></span>
				Tambah Agenda Kegiatan</a>
		</div>
	</div>
</div>

<link rel="stylesheet" href="css/jquery.gritter.css" />
<script>var AJAX_URL = "<?php echo RA_AJAX_URL; ?>"; </script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/perencanaan/detil_kegiatan.js"></script>


