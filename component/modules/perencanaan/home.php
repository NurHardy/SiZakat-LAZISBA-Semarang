<?php
	$yearNow = intval(date("Y"));
?>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Daftar Dokumen Perencanaan</h5>
		</div>
		<div class="widget-content">
			<div class="row"><div class='col-md-6'>
				<div class="list-group">
				<?php
					// Proses list dokumen perencanaan berdasarkan tahun
					$groupQuery  = "SELECT tahun, SUM(anggaran) AS jumlah_anggaran FROM ((SELECT '".$yearNow."' AS tahun, 0 AS anggaran) UNION ";
					$groupQuery .= "(SELECT YEAR(tgl_mulai) AS tahun, jumlah_anggaran AS anggaran FROM ra_agenda)) AS rat GROUP BY tahun ORDER BY tahun DESC";
					$groupQueryResult = mysqli_query($mysqli, $groupQuery);
					
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