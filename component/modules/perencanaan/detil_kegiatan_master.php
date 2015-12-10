<?php
/*
 * detil_kegiatan_master.php
 * ==> Proses menampilkan master kegiatan dan editor rincian awal kegiatan
 *
 * AM_SIZ_RA_DTLMASTKGT | Form Kegiatan
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) exit;
	
	$isAuthorized = false;
	$idKegiatan = -1;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	$SIZPageTitle	= "Detil Master Kegiatan";
	$breadCrumbPath[] = array("Master Kegiatan",ra_gen_url("list"),false);
	
	if(isset($_GET['id'])){
		$idKegiatan = intval($_GET['id']);
		$queryKegiatan =
			"SELECT r.*, a.idakun, a.namaakun ".
			"FROM ra_kegiatan AS r, akun AS a ".
			"WHERE (r.akun_pengeluaran=a.kode) AND (r.id_kegiatan={$idKegiatan})";
		$resultKegiatan = mysqli_query($mysqli, $queryKegiatan);
		$rowKegiatan = mysqli_fetch_array($resultKegiatan);
		
		if ($rowKegiatan != null) {
			$breadCrumbPath[] = array("Detil Master Kegiatan",
					ra_gen_url("master-kegiatan",null,"id=".$idKegiatan), true);
			$isAuthorized = ($isAdmin || ($divisiUser==$rowKegiatan['divisi']));
		}else {
			show_error_page( "Data kegiatan tidak ditemukan dalam database." );
			return;
		}
	} else {
		show_error_page( "Argumen tidak lengkap." );
		return;
	}
	
	ra_print_status($namaDivisiUser); ?>
<div class="col-lg-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>
			Informasi Kegiatan
			<?php 
			if ($isAuthorized) {
				echo " | <small><a href=\"".htmlspecialchars(ra_gen_url("edit-kegiatan-master", null, "id=".$idKegiatan));
				echo "\"><span class=\"glyphicon glyphicon-pencil\"></span> Edit</a></small>";
 			}
			?>
			
			</h3>
		</div>
		<div class="panel-body">
			<div class="row">
			  <div class="col-md-12">
				<table class="siz-table-detail">
					<tr>
						<td>Nama Kegiatan</td>
						<td><?php echo htmlspecialchars($rowKegiatan['nama_kegiatan']); ?> (Master)</td>
					</tr>
					<tr>
						<td>Divisi</td>
						<td><?php echo ($listDivisi[$rowKegiatan['divisi']]); ?></td>
					</tr>
					<tr>
						<td>Akun Pengeluaran</td>
						<td><?php
							echo "<a href=\"".htmlspecialchars("main.php?s=akun&action=detail&id=".$rowKegiatan['idakun'])."\" target=\"_blank\">".
								$rowKegiatan['akun_pengeluaran']." ".
								$rowKegiatan['namaakun']."</a>";
						?></td>
					</tr>
					<tr>
						<td>Jenis</td>
						<td><?php echo ($listJenisKegiatan[$rowKegiatan['jenis_kegiatan']]); ?></td>
					</tr>
					<tr>
						<td>Prioritas</td>
						<td><?php echo $listPrioritasHTML[$rowKegiatan['prioritas']];?>
							<a href="<?php echo htmlspecialchars(ra_gen_url("documentation")); ?>">
								<span class="glyphicon glyphicon-question-sign"></span></a>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Keterangan/Deskripsi:
							<div class="siz-desc-container well well-sm">
								<?php echo nl2br(htmlspecialchars($rowKegiatan['keterangan_kegiatan']));?>
							</div>
						</td>
					</tr>
					
				</table>
			  </div>
			</div> <!-- End row detil -->
		</div>
	</div> <!-- End panel informasi kegiatan -->
</div>
<div class="col-lg-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-time"></span>
				Rincian Awal Kegiatan</h3>
		</div>
		<div class="panel-body">
<?php if ($isAuthorized)
		echo "<span class=\"glyphicon glyphicon-info-sign\"></span>&nbsp;Tuliskan jumlah anggaran".
					" tanpa pemisah ribuan."; ?>
			<table class="table table-bordered table-striped table-hover" id="siz-tabel-rinc-awal">
				<thead>
					<tr>
						<th>Nama Rincian</th>
						<th style="width:200px;">Jumlah</th>
						<th style="width:75px;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$totalRincian=0;
						$queryRincian = sprintf(
							"SELECT * FROM ra_rincian_awal WHERE id_kegiatan=%d",
							$idKegiatan);
						$resultRincian = $mysqli->query($queryRincian);
						
						while($rowRincian = $resultRincian->fetch_array(MYSQLI_ASSOC)){
							$i++;
							$totalRincian  = $totalRincian + $rowRincian['jumlah_anggaran'];
							
							echo "<tr id=\"siz_mast_rincian_".$rowRincian['id_rincian']."\">";
								echo "<td><span class=\"siz-n-rinc\">".htmlspecialchars($rowRincian['nama_rincian'])."</span></td>";
								echo "<td><span class=\"siz-v-rinc\" data-nilai=\"".$rowRincian['jumlah_anggaran']."\">";
								echo to_rupiah($rowRincian['jumlah_anggaran'])."</span></td>";
								echo "<td>";
								if ($isAuthorized) {
									echo "<div class=\"siz-rinc-control\"><a href=\"#\" class=\"btn btn-xs btn-primary\" ";
									echo "onclick=\"return edit_rincian(".$rowRincian['id_rincian'].");\" title=\"Edit\">";
									echo "<i class=\"glyphicon glyphicon-pencil\"></i> </a>&nbsp;";
									echo "<a href=\"#\" class=\"btn btn-xs btn-danger\" ";
									echo "onclick=\"return hapus_rincian(".$rowRincian['id_rincian'].");\" title=\"Hapus\">";
									echo "<i class=\"glyphicon glyphicon-trash\"></i> </a></div>";
								}
							echo "</td></tr>\n";
						}
					?>
				</tbody>
<?php if ($isAuthorized) { //=========================== ?>
				<tfoot>
				<tr id="siz_row_tambah_rincian">
					<td><input type="text" name="txt_nama_rincian" id="txt_nama_rincian" 
						placeholder="Nama Rincian" class="siz-fullwidth"/></td>
					<td>
						<div class="input-group siz-input-anggaran">
							<div class="input-group-addon">Rp.</div>
							<input type="text" name="txt_anggaran_rincian" id="txt_anggaran_rincian"
						placeholder="Nilai Rincian"/></div>
					</td>
					<td><a href="#" class="btn btn-xs btn-primary"
						onclick="return submit_rincian_tambahan(<?php echo $idKegiatan; ?>);">
						<span class="glyphicon glyphicon-plus"></span> Tambah</a></td>
				</tr>
				</tfoot>
<?php } //============================================ ?>
			</table>
			<p class="siz-p-conclude">Total Rincian Awal : <b class="siz-total-rinc-awal"><?php echo to_rupiah($totalRincian);?></b></p>
				
		</div>
	</div> <!-- End panel transaksi -->		
</div><!-- End row content -->
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script>var AJAX_URL = "<?php echo RA_AJAX_URL; ?>"; </script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/perencanaan/detil_kegiatan_master.js"></script>