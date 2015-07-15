<?php
/*
 * timeline_perencanaan.php
 * ==> Menampilkan timeline kegiatan pada satu tahun
 *
 * AM_SIZ_RA_TIMELINE | Timeline
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) return;
	$tahunDokumen = $_GET['th'];
	
	
?>
<div class="col-12">
	<?php
	
	ra_print_status($namaDivisiUser);
	
	$counterBulan = 1;
	for ($counterBulan=1;$counterBulan<=12;$counterBulan++) { // ======== FOR EACH MONTH
		// Query agenda kegiatan
		$queryListKegiatan = sprintf(
			"SELECT DISTINCT a.id_kegiatan, a.*, k.nama_kegiatan, k.divisi ".
			"FROM ra_agenda AS a, ra_kegiatan AS k ".
			"WHERE (a.id_kegiatan=k.id_kegiatan) AND (YEAR(a.tgl_mulai)=%d) AND (MONTH(a.tgl_mulai)=%d) ".
			"ORDER BY k.divisi, a.tgl_mulai",
			$tahunDokumen, $counterBulan
		);
		$resultListKegiatan = mysqli_query($mysqli, $queryListKegiatan);
		$queryCount++;
		if ($resultListKegiatan == null) {
			echo mysqli_error($mysqli);
		}
		// Jika tidak ada agenda, maka tidak perlu ditampilkan timeline
		if (mysqli_num_rows($resultListKegiatan) > 0) {
			$firstDate = sprintf("%04d-%02d-01", $tahunDokumen, $counterBulan);
			$lastDayMonth	= intval(date("t", strtotime($firstDate)));
?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-calendar"></i>
			</span>
			<h5>Timeline Bulan <?php echo $monthName[$counterBulan]. " " . $tahunDokumen;?></h5>
		</div>
		<div class="widget-content nopadding">
			<div style="overflow-x: scroll;">
				<table class="table table-hover siz-table-timeline" style="width:1200px;">
				<thead>
					<tr>
						<th rowspan="2" style="width: 300px;">Kegiatan</th>
						<th colspan="31">Pelaksanaan</th>
						<th rowspan="2">Jumlah Anggaran</th>
						<th rowspan="2">Aksi</th>
					</tr>
					<tr>
					<?php
						for ($tgl=1;$tgl<=31;$tgl++) {
							echo "<th class=\"tm-day\">".($tgl > $lastDayMonth?"-":$tgl)."</th>";
						}
					?>
					</tr>
				</thead>
				<tbody>
	<?php
	while ($dataKegiatan = mysqli_fetch_array($resultListKegiatan)) {
		$ctrTgl = 1;
		$dayOfWeek = intval(date("N", strtotime($firstDate)))-1;
		$urlKegiatan = ra_gen_url('kegiatan',$tahunDokumen,'id='.$dataKegiatan['id_kegiatan']);
		
		$startDay	= date("d", strtotime($dataKegiatan['tgl_mulai']));
		$finishDay	= date("d", strtotime($dataKegiatan['tgl_selesai']));
		echo "<tr>\n";
		echo "<td><a href=\"".htmlspecialchars($urlKegiatan)."\">";
		echo htmlspecialchars($dataKegiatan['nama_kegiatan']);
		echo "</a></td>\n";
		for ($ctrTgl=1;$ctrTgl<=31;$ctrTgl++) {
			if ($ctrTgl > $lastDayMonth) {
				echo "<td style=\"background-color: #DDDDDD;\">&nbsp;</td>";
			} else if (($ctrTgl >= $startDay) && ($ctrTgl <= $finishDay)) {
				echo "<td style=\"background-color: #5E5EFF;\">&nbsp;</td>";
			} else {
				echo "<td ".(($dayOfWeek==6)?"style=\"background-color: #fdd;\"":"").">&nbsp;</td>";
			}
			$dayOfWeek = ($dayOfWeek+1)%7;
		}
		echo "<td>Jumlah</td>\n";
		echo "<td>-</td>\n";
		echo "</tr>\n";
	}
	
				?></tbody>
				</table>
			</div>
		</div>
	</div>
<?php 
		} //============ END IF ==========
	} // ============== END FOR =======

?>
</div>