<?php
/*
 * pencarian.php
 * ==> Halaman pencarian modul perencanaan
 *
 * AM_SIZ_RA_FRMCARI | Halaman Home
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) exit;
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	$SIZPageTitle = "Pencarian";
	
	// Default Page - Set the BreadCrumb
	end($breadCrumbPath);
	$lastBreadCrumbId = key($breadCrumbPath);
	$breadCrumbPath[$lastBreadCrumbId][2] = true;
	
	$searchQuery = trim($_GET['q']);
	
	$tahunDokumen = -1;
	$tahunDokumen = intval($_GET['th']);
	
	//------ Proses pencarian
	if (!empty($searchQuery)) {
		$queryCari = sprintf(
				"SELECT *, jumlah_anggaran AS anggaran_rincian FROM ra_rincian_agenda ".
				"WHERE nama_rincian LIKE '%%%s%%'"
				, mysqli_escape_string($mysqli, $searchQuery)
		);
		
		$docSpecific = ($tahunDokumen > 0? "AND YEAR(a.tgl_mulai)=".$tahunDokumen:"");
		$queryCariJoin = sprintf(
				"SELECT c.*, a.*, a.jumlah_anggaran AS anggaran_agenda, k.* ".
				"FROM (%s) AS c, ra_agenda AS a, ra_kegiatan AS k ".
				"WHERE c.id_agenda=a.id_agenda AND a.id_kegiatan=k.id_kegiatan %s ".
				"GROUP BY a.id_agenda ".
				"ORDER BY a.tgl_mulai",
				$queryCari, $docSpecific
		);
		
		$searchResult = mysqli_query($mysqli, $queryCariJoin);
		$queryCount++;
		
		if (!$searchResult) {
			show_error_page("Terjadi kesalahan internal: ".mysqli_error($mysqli));
			return;
		}
	}
	
	ra_print_status($namaDivisiUser); ?>
<div class="col-md-8" id="siz-content">
	<form action="main.php#siz-content" method="get" class="form-inline">
		<input type="hidden" name="s" value="perencanaan" />
		<input type="hidden" name="action" value="cari" />
		<label for="siz-query">Cari Rincian Agenda:</label>
		<input type="text" name="q" required id="siz-query" required placeholder="Cari"
			 class="form-control" value="<?php 
			 if (!empty($searchQuery)) echo htmlspecialchars($searchQuery);
			 ?>"/>
		<label for="siz-doc">Tahun:</label>
		<select name="th" id="siz-doc" class="form-control">
			<option value='0'>Semua Tahun</option>
<?php 
	$queryGetDoc = "SELECT tahun_dokumen FROM ra_dokumen";
	$resultGetDoc = mysqli_query($mysqli, $queryGetDoc);
	while ($rowDoc = mysqli_fetch_array($resultGetDoc)) {
		$isSelected = ($rowDoc['tahun_dokumen'] == $tahunDokumen);
		echo "<option value=\"".$rowDoc['tahun_dokumen']."\" ";
		echo ($isSelected?"selected":"").">".$rowDoc['tahun_dokumen']."</option>";
	}
?>
		</select>
		<button type="submit" class="btn btn-default">
			<span class="glyphicon glyphicon-search"></span> Cari</button>
	</form>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Pencarian Agenda atau Rincian</h5>
		</div>
		<div class="widget-content">
			<div class="row"><div class='col-md-12'>
				Hasil pencarian untuk '<b><?php echo htmlspecialchars($searchQuery); ?></b>'
				<?php 
				if ($tahunDokumen > 0) {
					echo " (dokumen perencanaan tahun <b>{$tahunDokumen}</b>)";
 				}
				?>
				<hr>
<?php 
	if (mysqli_num_rows($searchResult) > 0) {
		while ($rowRincian = mysqli_fetch_assoc($searchResult)) {
			$unixTimeTgl = strtotime($rowRincian['tgl_mulai']);
			$bulanAgenda = date('n', $unixTimeTgl);
			$tahunAgenda = date('Y', $unixTimeTgl);
			$formatIdn = date('j M Y', $unixTimeTgl);
			if ($rowRincian['jenis_agenda'] == 3) { // Kegiatan satu bulan
				$tanggalAgenda = "Sepanjang ".$monthName[$bulanAgenda]." ".$tahunAgenda;
			} else {
				$tanggalAgenda = tanggal_indonesia($formatIdn);
			}
			
			$patterns = '#(^|\W)('.preg_quote($searchQuery).')($|\W)#i';
			$htmlRincian = preg_replace($patterns, '$1<span class="siz-highlight-yellow">$2</span>$3', $rowRincian['nama_rincian']);
			
			$linkTarget = ra_gen_url('kegiatan',$tahunAgenda,'id='.$rowRincian['id_kegiatan']).
				"#siz_agenda_".$rowRincian['id_agenda'];
			echo "<div class='siz-ra-search-itemagenda'>";
 			echo "<div style='font-size:1.2em;'><a href=\"".htmlspecialchars($linkTarget)."\" target=\"_blank\">";
 			echo "<b>".$rowRincian['nama_kegiatan']."</b></a>";
 			echo "<div class='siz-divsmall pull-right'><span class='glyphicon glyphicon-calendar'></span> ";
 			echo $tanggalAgenda."</div></div>";
 			echo "<div class='siz-divsmall'>Divisi <b>".$listDivisi[$rowRincian['divisi']]."</b></div>";
 			if (!empty($rowRincian['catatan']))
 				echo "<div class='well well-sm'>".htmlspecialchars($rowRincian['catatan'])."</div>";
 			echo "&bull; ".$htmlRincian." - ".to_rupiah($rowRincian['anggaran_rincian']);
 			echo "<div class='siz-divsmall'>Jumlah anggaran agenda: <b>".
 				to_rupiah($rowRincian['anggaran_agenda'])."</b></div>";
			echo "</div>\n";
		}
	} else { //------------ ELSE IF -- (Tidak ditemukan) ?>
	<span class="glyphicon glyphicon-warning-sign"></span> Tidak Ditemukan
<?php } //--- END IF ---------------------------------------- ?>
			</div></div>
		</div>
	</div>
</div>