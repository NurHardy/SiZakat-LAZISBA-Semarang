<?php
/*
 * rekap_tahunan.php
 * ==> Menampilkan rekapitulasi kegiatan pada satu tahun
 *
 * AM_SIZ_RA_REKAPTHN | Rekap Tahunan
 * ------------------------------------------------------------------------
 * Note: Jumlah anggaran masing2 agenda diambil dari cache jumlah_anggaran di tabel agenda
 */

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
			Silakan atur list kegiatan pada halaman
			<a href="<?php echo ra_gen_url("list",$tahunDokumen); ?>">Master Kegiatan</a>.
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
					echo "<tr class=\"siz_add_kegiatan\">\n";
					echo "	<td>&nbsp;</td>\n";
					echo "	<td><a href=\"".htmlspecialchars(ra_gen_url("tambah-kegiatan",$tahunDokumen,"div=".$currDivisi));
					echo "\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-plus\">";
					echo "</span> Tambah Kegiatan</a></td>\n";
					echo "  <td colspan=\"12\">&nbsp;</td>";
					echo "	<td>&nbsp;</td>\n";
					echo "	<td>&nbsp;</td>\n";
					echo "</tr>\n";
			
					// Baris jumlah anggaran per divisi
					echo "<tr><td colspan=\"14\"><b>Jumlah</b></td>";
					echo "<td>".to_rupiah($totalAnggaran)."</td><td>&nbsp;</td></tr>\n";
					$counterKegiatan = $totalAnggaran = 0;
				}
				$currDivisi = $rowKegiatan['divisi'];
				echo "<tr id=\"siz_kegiatan_divisi_".$currDivisi."\">\n";
				echo "<td colspan=\"16\"><b>Divisi ".$listDivisi[$currDivisi]."</b></td>";
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
			echo "	<td><a href=\"".$urlKegiatan."\">";
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
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
			
			$rowKegiatan = mysqli_fetch_array($resultQueryRekap);
			
		} // End while
		if ($currDivisi != -1) { // Untuk yang terakhir...
			// Baris tambah kegiatan
			echo "<tr class=\"siz_add_kegiatan\">\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td><a href=\"".htmlspecialchars(ra_gen_url("tambah-kegiatan",$tahunDokumen,"div=".$currDivisi));
			echo "\" class=\"btn btn-primary btn-xs\"><span class=\"glyphicon glyphicon-plus\">";
			echo "</span> Tambah Kegiatan</a></td>\n";
			echo "  <td colspan=\"12\">&nbsp;</td>";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
	
			// Baris jumlah anggaran per divisi
			echo "<tr><td colspan=\"14\"><b>Jumlah</b></td>";
			echo "<td>".to_rupiah($totalAnggaran)."</td><td>&nbsp;</td></tr>\n";
		}
} // ==================================== END IF ADA KEGIATAN
		?>
			</table>
		</div>
	</div>
</div>
