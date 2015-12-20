<?php
/*
 * rekap_tahunan.php
 * ==> Menampilkan rekapitulasi kegiatan pada satu tahun
 *
 * AM_SIZ_RA_REKAPTHN | Rekap Tahunan
 * ------------------------------------------------------------------------
 * Note: Jumlah anggaran masing2 agenda diambil dari cache jumlah_anggaran di tabel agenda
 */

	// Cek privilege
	if (!ra_check_privilege()) return;
	
	$SIZPageTitle = "Perencanaan";
	$tahunDokumen = $_GET['th'];
	if (empty($tahunDokumen) || !is_numeric($tahunDokumen)) {
		show_error_page("Argumen tidak lengkap.");
		return;
	}
	
	$SIZPageTitle .= " Tahun ".$tahunDokumen;
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),true);
	
	// Cek Dokumen
	$rowDocument = ra_cek_dokumen($tahunDokumen);
	if (!$rowDocument) return;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	// Query rekap kegiatan dalam satu tahun
	$queryRekap  = "SELECT c.id_kegiatan, k.nama_kegiatan, k.divisi ";
	$queryRekap .= "FROM ra_catatan_kegiatan AS c, ra_kegiatan AS k ";
	$queryRekap .= "WHERE c.tahun=".$tahunDokumen." AND c.id_kegiatan=k.id_kegiatan ";
	$queryRekap .= "ORDER BY divisi";
	
	$resultQueryRekap = mysqli_query($mysqli, $queryRekap);
	$queryCount++;
	if ($resultQueryRekap == null) {
		show_error_page( "Terjadi kesalahan query: ".mysqli_error($mysqli) );
		return;
	}
	
	// Query rekap prioritas agenda...
	$queryPrioritas = sprintf(
		"SELECT SUM(jumlah_anggaran) AS jumlah, COUNT(jumlah_anggaran) AS cnt, prioritas_agenda ".
		"FROM ra_agenda WHERE YEAR(tgl_mulai)=%d GROUP BY prioritas_agenda", $tahunDokumen
	);
	$resultRekapPrioritas = mysqli_query($mysqli, $queryPrioritas);
	$queryCount++;
	if ($resultRekapPrioritas == null) {
		show_error_page( "Terjadi kesalahan query: ".mysqli_error($mysqli) );
		return;
	}
?>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Rekapitulasi Kegiatan Tahun <b><?php echo $tahunDokumen; ?></b></h5>
		</div>
		
		<div class="widget-content">
<?php 
echo "<div class=\"well well-sm\"><b>Catatan Dokumen:</b>\n";

if ($isAdmin)
	echo "<a href=\"".ra_gen_url("edit-catatan-dokumen",$tahunDokumen)."\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;Edit</a>";
echo "<br>\n";
if (!empty($rowDocument['catatan'])) {
	echo htmlspecialchars($rowDocument['catatan']); 
}
echo "</div>\n";
?>
			Silakan atur list kegiatan pada halaman
			<a href="<?php echo ra_gen_url("list",$tahunDokumen); ?>">
				<i class="glyphicon glyphicon-list"></i> Master Kegiatan</a>.
			<div style="margin-bottom:10px;">
				<a href="<?php echo htmlspecialchars(ra_gen_url("export",$tahunDokumen,"type=xlsx")); ?>"
					class="btn btn-default">
					<i class="glyphicon glyphicon-file"></i> Export ke Excel</a>

				<a href="<?php echo htmlspecialchars(ra_gen_url("timeline",$tahunDokumen)); ?>"
					class="btn btn-default">
					<i class="glyphicon glyphicon-calendar"></i> Lihat Timeline</a>
<?php if ($isAdmin) { //========== Khusus Admin ============= ?>
				<a href="<?php echo htmlspecialchars(ra_gen_url("hapus-dokumen",$tahunDokumen)); ?>"
					class="btn btn-danger">
					<i class="glyphicon glyphicon-erase"></i> Hapus Dokumen</a>
<?php } //======================== End If =================== ?>
				<div class="pull-right">
					<form action="main.php#siz-content" method="get">
						<input type="hidden" name="s" value="perencanaan" />
						<input type="hidden" name="action" value="cari" />
						<input type="hidden" name="th" value="<?php echo $tahunDokumen; ?>" />
						<label for="siz-query">Cari Rincian Agenda:</label>
						<input type="text" name="q" required id="siz-query" required placeholder="Cari"/>
						<button type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-search"></span> Cari</button>
					</form>
				</div>
				
			</div>
<?php if (mysqli_num_rows($resultQueryRekap) > 0) { //==================== JIKA ADA KEGIATAN ========== ?>
			<table class="table table-bordered table-hover siz-operation-table">
				<tr>
					<th rowspan="2" style='width:30px;'>No.</th>
					<th rowspan="2">Kegiatan</th>
					<th colspan="12">Pelaksanaan</th>
					<th rowspan="2">Jumlah Anggaran</th>
					<th rowspan="2">Aksi</th>
				</tr>
				<tr>
					<?php for ($bln=1;$bln<=12;$bln++) {
						echo "<th>".$bln."</th>";
					} ?>
				</tr>
		<?php
		$grandTotalAnggaran	= 0;
		$counterKegiatan	= 0;
		$totalAnggaran		= 0;
		$anggaranKegiatan	= 0;
		
		$bulanSekarang	= 0;
		$tampilNamaBulan = false;
		
		$currDivisi = -1; // Menyimpan sementara divisi yang aktif
		$rowKegiatan = mysqli_fetch_array($resultQueryRekap);
		while ($rowKegiatan) {
			if ($currDivisi != $rowKegiatan['divisi']) {
				if ($currDivisi != -1) {
					// Baris tambah kegiatan
					if ($isAdmin || ($divisiUser == $rowKegiatan['divisi'])) {
						echo "<tr class=\"siz_add_kegiatan\">\n";
						echo "	<td>&nbsp;</td>\n";
						echo "	<td><a href=\"".htmlspecialchars(ra_gen_url("tambah-kegiatan",$tahunDokumen,"div=".$currDivisi));
						echo "\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-plus\">";
						echo "</span> Tambah Kegiatan</a></td>\n";
						echo "  <td colspan=\"12\">&nbsp;</td>";
						echo "	<td>&nbsp;</td>\n";
						echo "	<td>&nbsp;</td>\n";
						echo "</tr>\n";
					}
			
					// Baris jumlah anggaran per divisi
					echo "<tr><td colspan=\"14\"><b>Jumlah</b></td>";
					echo "<td><b>".to_rupiah($totalAnggaran)."</b></td><td>&nbsp;</td></tr>\n";
					$grandTotalAnggaran += $totalAnggaran;
					$counterKegiatan = $totalAnggaran = 0;
				}
				$currDivisi = $rowKegiatan['divisi'];
				echo "<tr id=\"siz_kegiatan_divisi_".$currDivisi."\">\n";
				echo "<td colspan=\"16\"><h4><span class=\"glyphicon glyphicon-triangle-right\"></span> ";
				echo "Divisi ".$listDivisi[$currDivisi]."</h4></td>";
				echo "</tr>\n";
			}
			$anggaranKegiatan	= 0;
			$counterKegiatan++;
			$namaDivisi = (isset($listDivisi[$rowKegiatan['divisi']])?
					$listDivisi[$rowKegiatan['divisi']]:
					"Unknown");
			
			echo "<tr id=\"siz_kegiatan_".$rowKegiatan['id_kegiatan']."\">\n";
			echo "	<td>".$counterKegiatan."</td>\n";
			$urlKegiatan = ra_gen_url('kegiatan',$tahunDokumen,'id='.$rowKegiatan['id_kegiatan']);
			echo "	<td><a href=\"".htmlspecialchars($urlKegiatan)."\">";
			echo htmlspecialchars($rowKegiatan['nama_kegiatan'])."</a></td>\n";
			
			$queryAgenda = sprintf(
				"SELECT *, MONTH(tgl_mulai) AS bulan FROM ra_agenda ".
				"WHERE id_kegiatan=%d AND YEAR(tgl_mulai)=%d ORDER BY tgl_mulai",
				$rowKegiatan['id_kegiatan'], $tahunDokumen
			);
			$resultAgenda = mysqli_query($mysqli, $queryAgenda);
			$queryCount++;
			if ($resultAgenda != null) {
				$ctrBulan = 0;
				$rowAgenda = mysqli_fetch_array($resultAgenda);
				for ($ctrBulan=1;$ctrBulan<=12;$ctrBulan++) {
					$jmlKegiatan = 0;
					$tooltipContent = "Bulan <b>".$monthName[$ctrBulan]."</b><br>";
					echo "<td>";
					while ($rowAgenda != null) {
						if ($rowAgenda['bulan']==$ctrBulan) {
							$jmlKegiatan++;
							$anggaranKegiatan += intval($rowAgenda['jumlah_anggaran']);

							$tooltipContent .= "&bull; Tanggal ".$rowAgenda['tgl_mulai']." ";
							$tooltipContent .= "(".to_rupiah($rowAgenda['jumlah_anggaran']).")<br>";
							
							$rowAgenda = mysqli_fetch_array($resultAgenda);
						} else break;
					}
					if ($jmlKegiatan > 0) {
						echo "<div class=\"siz_kegiatan_bulan\">";
						echo "<i class=\"glyphicon glyphicon-ok\"></i>";
						echo "<div class=\"siz_detail_tooltip\">";
						echo $tooltipContent;
						echo "</div>";
						echo "</div>";
					}
					
					echo "</td>";
				}
			} else {
				echo "<td colspan=\"12\">Query Error!</td>";
			}
			
			$totalAnggaran += $anggaranKegiatan;
			
			echo "	<td>".to_rupiah($anggaranKegiatan)."</td>\n";
			echo "	<td>";
			if ($isAdmin || ($divisiUser == $rowKegiatan['divisi'])) {
				echo "<a class=\"red_link\" href=\"";
				echo htmlspecialchars(ra_gen_url("hapus-agenda-kegiatan",$tahunDokumen,"idk=".$rowKegiatan['id_kegiatan']));
				echo "\"><span class=\"glyphicon glyphicon-trash\"></span> Hapus</a>";
			}
			echo "</td>\n";
			echo "</tr>\n";
			
			$rowKegiatan = mysqli_fetch_array($resultQueryRekap);
			
		} // End while
		if ($currDivisi != -1) { // Untuk yang terakhir...
			// Baris tambah kegiatan
			if ($isAdmin || ($divisiUser == $rowKegiatan['divisi'])) {
				echo "<tr class=\"siz_add_kegiatan\">\n";
				echo "	<td>&nbsp;</td>\n";
				echo "	<td><a href=\"".htmlspecialchars(ra_gen_url("tambah-kegiatan",$tahunDokumen,"div=".$currDivisi));
				echo "\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-plus\">";
				echo "</span> Tambah Kegiatan</a></td>\n";
				echo "  <td colspan=\"12\">&nbsp;</td>";
				echo "	<td>&nbsp;</td>\n";
				echo "	<td>&nbsp;</td>\n";
				echo "</tr>\n";
			}
	
			// Baris jumlah anggaran per divisi
			echo "<tr><td colspan=\"14\"><b>Jumlah</b></td>";
			echo "<td><b>".to_rupiah($totalAnggaran)."</b></td><td>&nbsp;</td></tr>\n";
			$grandTotalAnggaran += $totalAnggaran;
		}
} else { // == JIKA TIDAK ADA KEGIATAN ====================== ?>
	<div class="siz-dok-empty">
		<img src="images/icons/info.png" alt="INFO:" /> Belum ada kegiatan ditambahkan.<br>
		<a href="<?php echo ra_gen_url('tambah-kegiatan', $tahunDokumen); ?>"
					class="btn btn-primary btn-sm tip-right"
					title="Tambah kegiatan baru pada perencanaan tahunan">
					<span class="glyphicon glyphicon-plus"></span>
					Tambah Kegiatan</a>
		<a href="<?php echo ra_gen_url('tambah-kegiatan-rutin', $tahunDokumen); ?>"
				class="btn btn-default tip-right"
				title="Tambah kegiatan rutin pada perencanaan tahunan">
				<span class="glyphicon glyphicon-plus"></span>
				Tambah Kegiatan Rutin</a>
	</div>
	
<?php
} // ==================================== END IF ADA KEGIATAN
		?>
			</table>
			<div class="well">
				Grand Total Anggaran Tahunan : <b><?php echo to_rupiah($grandTotalAnggaran); ?></b><br>
				Rekap prioritas agenda: <br>
<?php
	if ($resultRekapPrioritas) {
		while ($rowPrioritas = mysqli_fetch_assoc($resultRekapPrioritas)) {
			$txtPrioritas = ($rowPrioritas['prioritas_agenda']==0?"Unset":
					$listPrioritasHTML[$rowPrioritas['prioritas_agenda']]);
			echo "&bull; ".$txtPrioritas.": <b>".
				to_rupiah($rowPrioritas['jumlah'])."</b> (".
				$rowPrioritas['cnt']." agenda)<br>";
		}
	}
?>
			</div>
			<a href="<?php echo ra_gen_url('tambah-kegiatan', $tahunDokumen); ?>"
				class="btn btn-primary tip-top"
				title="Tambah kegiatan baru pada perencanaan tahunan">
				<span class="glyphicon glyphicon-plus"></span>
				Tambah Kegiatan</a>
			<a href="<?php echo ra_gen_url('tambah-kegiatan-rutin', $tahunDokumen); ?>"
				class="btn btn-default tip-top"
				title="Tambah kegiatan rutin pada perencanaan tahunan">
				<span class="glyphicon glyphicon-plus"></span>
				Tambah Kegiatan Rutin</a>
		</div>
	</div>
</div>
