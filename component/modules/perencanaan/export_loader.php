<?php
/*
 * export_loader.php
 * ==> Halaman ekspor dokumen perencanaan
 *
 * AM_SIZ_RA_EXPORTLOADER | Export Loader
 * ------------------------------------------------------------------------
 */
		
	// Cek privilege
	if (!ra_check_privilege()) exit;

	$tahunDokumen = $_GET['th'];
	if (empty($tahunDokumen)) {
		show_error_page("Argumen tidak lengkap.");
		return;
	}
	if (!is_numeric($tahunDokumen)) {
		show_error_page("Tahun tidak valid.");
		return;
	}
	$SIZPageTitle = "Ekspor Dokumen Perencanaan";
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Ekspor",null,true);
	
	$typeDokumen = $_GET['type'];
	
	if ($typeDokumen=="pdf") {
		
	} else {
		$typeDokumen = "xlsx";
	}
?>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-download"></i>
			</span>
			<h5>Export Dokumen <b><?php echo $tahunDokumen; ?></b></h5>
		</div>
		<div class="widget-content">
			<div class="alert alert-danger" style="display:none" id="siz-export-alert"></div>
			<div class="row">
				<div class="col-sm-offset-3 col-sm-6" id="siz-export-panel">
					<h3>Exporting...</h3>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
function do_export() {
	_ajax_send({
		act: 'export.perencanaan.dokumen',
		type: '<?php echo $typeDokumen; ?>',
		th: <?php echo $tahunDokumen; ?>
	}, function(response){
		if (response.status == 'ok') {
			var downloadHTML = "";
			downloadHTML += "Download: <a href=\""+response.data.fileurl+"\">";
			downloadHTML += "<img src=\"images/icons/"+response.data.icon+"\" /> <b>"+response.data.filename+"</b>"
			downloadHTML += "</a>"
			$("#siz-export-panel").html(downloadHTML);
		} else {
			$("#siz-export-panel").hide();
			$("#siz-export-alert")
			.html("<span class=\"glyphicon glyphicon-warning-sign\"></span> Terjadi kesalahan: "+response.error)
			.fadeIn(250);
		}
	}, "Sedang memproses...", AJAX_URL);
}
function init_page() {
	do_export();
}
</script>