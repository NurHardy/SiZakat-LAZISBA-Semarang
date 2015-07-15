<?php
/*
 * home.php
 * ==> Halaman home yang menampilkan dasbor perencanaan
 *
 * AM_SIZ_RA_HOME | Halaman Home
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	ra_check_privilege();

	$yearNow = intval(date("Y"));
	ra_print_status($namaDivisiUser); ?>
<div class="col-md-6">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Daftar Dokumen Perencanaan</h5>
		</div>
		<div class="widget-content">
			<div class="row"><div class='col-md-12'>
				<div class="list-group">
				<?php
					// Proses list dokumen perencanaan berdasarkan tahun
					$groupQuery  = "SELECT tahun, SUM(anggaran) AS jumlah_anggaran FROM ((SELECT '".$yearNow."' AS tahun, 0 AS anggaran) UNION ALL ";
					$groupQuery .= "(SELECT YEAR(tgl_mulai) AS tahun, jumlah_anggaran AS anggaran FROM ra_agenda)) AS rat GROUP BY tahun ORDER BY tahun DESC";
					$groupQueryResult = mysqli_query($mysqli, $groupQuery);
					$queryCount++;
					
					if ($groupQueryResult) {
						while ($documentRow = mysqli_fetch_array($groupQueryResult)) {
							echo "<a href=\"main.php?s=perencanaan&amp;action=rekap&amp;th=".$documentRow['tahun']."\" class=\"list-group-item\">\n";
							echo "<div class=\"media-left\">\n";
							echo "	<img class=\"media-object\" src=\"images/logo.png\" alt=\"...\" style=\"width: 64px;\">\n";
							echo "</div>\n";
							echo "<div class=\"media-body\">\n";
							echo "  <h4 class=\"media-heading\">Perencanaan Tahun ".$documentRow['tahun']."</h4>\n";
							echo "  Rencana Pengeluaran : ".to_rupiah($documentRow['jumlah_anggaran'])."\n";
							echo "</div>\n";
							echo " </a>\n";
						}
					} else {
						echo "Query Error!: ".$mysqli->error;
					}
				
				?>
				</div>
			</div></div>
			<div class='clearfix clear'></div>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>	
			<h5>Agenda Terdekat</h5> | </span><a class="btn btn-default btn-sm" href="<?php
			echo htmlspecialchars(ra_gen_url("timeline", $yearNow)); ?>">
				<span class="glyphicon glyphicon-time"></span> Lihat Timeline</a>
		</div>
		<div class="widget-content nopadding updates">
			
		<?php
		$queryGetAgenda =
			"SELECT a.*, k.nama_kegiatan, k.divisi ".
			"FROM ra_agenda AS a, ra_kegiatan AS k ".
			"WHERE (tgl_mulai>NOW()) AND (a.id_kegiatan=k.id_kegiatan) ORDER BY tgl_mulai LIMIT 10";
		$resultGetAgenda = mysqli_query($mysqli, $queryGetAgenda);
		$queryCount++;
		
		if ($resultGetAgenda == null) {
			echo "Query error!: ".mysqli_error($mysqli);
		} else {
			$currentMonth = 0;
			while ($rowAgenda = mysqli_fetch_array($resultGetAgenda)) {
				$unixTimeTgl = strtotime($rowAgenda['tgl_mulai']);
				$tglEvent = date("j",$unixTimeTgl);
				$bulanEventLbl = date('M', $unixTimeTgl);
				$bulanEvent = date('n', $unixTimeTgl);
				$tahunEvent = date('Y', $unixTimeTgl);
				$urlAgenda = ra_gen_url("kegiatan", $tahunEvent, "id=".$rowAgenda['id_kegiatan']);
				if ($currentMonth != $bulanEvent) {
					echo "<div class=\"siz-ra-home-monthseparator clearfix\"><span class=\"glyphicon glyphicon-calendar\"></span> ";
					echo "<b>".$monthName[$bulanEvent]." ".$tahunEvent."</b></div>\n";
					$currentMonth = $bulanEvent;
				}
				echo "<div class=\"new-update clearfix\">\n";
				echo "	<i class=\"glyphicon glyphicon-star-empty\"></i>\n";
				echo "		<div class=\"update-done\">\n";
				echo "			<a title=\"\" href=\"".htmlspecialchars($urlAgenda)."\"><strong>";
				echo htmlspecialchars($rowAgenda['nama_kegiatan'])."</strong></a>\n";
				echo "			<span><b>".$listDivisi[$rowAgenda['divisi']]."</b> | ";
				echo to_rupiah($rowAgenda['jumlah_anggaran']);
				echo "</span>\n";
				echo "		</div>\n";
				echo "		<div class=\"update-date\"><span class=\"update-day\">".$tglEvent."</span>".$bulanEventLbl."</div>\n";
				echo "</div>\n";
			}
			
		}
		?>
			
		</div>
	</div>
</div>