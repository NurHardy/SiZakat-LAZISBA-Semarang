<?php
/*
 * hapus_kegiatan_confirm.php
 * ==> Halaman konfirmasi hapus master kegiatan
 *
 * AM_SIZ_RA_CONFHPSMASTKGT | Konfirmasi hapus master kegiatan
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) exit;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$backUrl		= "javascript:backAway();";
	
	$SIZPageTitle .= "Hapus Master Kegiatan";
	$breadCrumbPath[] = array("Master Kegiatan",ra_gen_url("list"),true);
	$idKegiatan = $_GET['id'];
	
	// Cek master kegiatan...
	$queryCek = sprintf("SELECT * FROM ra_kegiatan WHERE id_kegiatan=%d", $idKegiatan);
	$resultCek = mysqli_query($mysqli, $queryCek);
	if ($resultCek) {
		$rowKegiatan = mysqli_fetch_assoc($resultCek);
		if (!$rowKegiatan) {
			show_error_page("Master kegiatan tidak ditemukan dalam database!");
			return;
		}
	} else {
		$errorDesc = mysqli_error($mysqli);
		show_error_page("Terjadi kesalahan internal: ".$errorDesc);
		return;
	}
	
	// Cek kegiatan, apakah mempunyai agenda..?
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	$queryCek = sprintf("SELECT COUNT(*) AS j FROM ra_agenda WHERE id_kegiatan=%d",
			$idKegiatan);
	$jumlahAgenda = querybuilder_getscalar($queryCek);
	
	if ($jumlahAgenda != null) {
		if ($jumlahAgenda > 0) {
			$queryAgenda = sprintf(
				"SELECT YEAR(tgl_mulai) AS tahun, COUNT(id_agenda) AS jumlah, SUM(jumlah_anggaran) AS anggaran ".
				"FROM ra_agenda WHERE id_kegiatan=%d GROUP BY YEAR(tgl_mulai)", $idKegiatan);
			$resultQueryAgenda = mysqli_query($mysqli, $queryAgenda);
			$listAgenda = array();
			while ($rowAgenda = mysqli_fetch_assoc($resultQueryAgenda)) {
				$listAgenda[] = $rowAgenda;
			}
		}
	} else {
		$errorDesc = mysqli_error($mysqli);
		show_error_page("Terjadi kesalahan internal: ".$errorDesc);
		return;
	}
	
?>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
var RA_HOME_URL = "<?php echo ra_gen_url("home"); ?>";
function init_page() {
	
}
function backAway(){
    //if it was the first page
    if(history.length === 1){
        window.location = "main.php?s=perencanaan";
    } else {
        history.back();
    }
}
</script>
<div class="col-md-12">
	<?php ra_print_status($namaDivisiUser); ?>
	
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
		
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="glyphicon glyphicon-list"></i>									
						</span>
						<h5>Hapus Master Kegiatan</h5>
					</div>
					<div class="widget-content">
						<div class="alert alert-danger" id="siz-alert-errors" style="display:none;"></div>
						<div class="alert alert-success" id="siz-alert-infos" style="display:none;"></div>
<?php if ($jumlahAgenda > 0) { //== Jika terdapat agenda ================= ?>
						<div class="alert alert-warning alert-icon alert-icon-warning" id="siz-alert-text">
							<b>Maaf, master kegiatan &quot;<?php
								echo htmlspecialchars($rowKegiatan['nama_kegiatan']);
							?>&quot; tidak dapat dihapus saat ini.</b><br>
							Master kegiatan ini sudah diagendakan pada tahun berikut. Silakan hapus agenda
							terlebih dahulu sebelum Anda menghapus master kegiatan.
							
						</div>
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr><th>Tahun</th><th>Anggaran</th></tr>
							</thead>
							<tbody>
	<?php foreach ($listAgenda as $itemAgenda) { //------ Foreach -----
		$detailLink = ra_gen_url("kegiatan",$itemAgenda['tahun'],"id=".$idKegiatan);
		echo "<tr><td><a href=\"".htmlspecialchars($detailLink)."\" target=\"_blank\">";
		echo	"<span class=\"glyphicon glyphicon-calendar\"></span> <b>".$itemAgenda['tahun']."</b> ";
		echo	"(".$itemAgenda['jumlah']." agenda)</a></td>";
		echo "<td>".to_rupiah($itemAgenda['anggaran'])."</td></tr>\n";
	} //------------- End foreach ------------------------------- ?>
							</tbody>
						</table>
<?php } else { //================== Jika tidak terdapat agenda =========== ?>
						<div class="alert alert-info alert-icon alert-icon-info" id="siz-alert-text">
							Silakan hapus master kegiatan dari halaman list master kegiatan.
						</div>
<?php } //========================= End If ===============================
/*
 * TODO: Selesaikan link kembali setelah hapus dokumen -_-
 */
?>
						<div class="row">
							<div class="col-md-4">
								<a href="<?php echo htmlspecialchars($backUrl); ?>" class="btn btn-default btn-block"
									id="siz-btn-cancel"><span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>