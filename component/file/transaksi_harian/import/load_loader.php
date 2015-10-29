<?php
/*
 * load_loader.php
 * ==> Halaman load stage impor
 *
 * AM_SIZ_STGLOAD_LOADER | Load stage loader
 * ------------------------------------------------------------------------
 */
	$doProcess = $_GET['do'];
	$doProcess = strtolower($doProcess);
	
	$stageName = "";
	if ($doProcess == "penerimaan") {
		$stageName = "penerimaan";
	} else if ($doProcess == "pengeluaran") {
		$stageName = "pengeluaran";
	} else {
		show_error_page("Tabel stage tidak dikenali!");
		return;
	}
?>
<div class="col-12">
	<?php import_submodule_printinfo(); ?>
	<div class="well well-sm">
		<a href="javascript:history.back();">
			<span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
	</div>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-download"></i>
			</span>
			<h5>Load stage ke data transaksi</h5>
		</div>
		<div class="widget-content">
			<div class="alert alert-danger" style="display:none" id="siz-export-alert"></div>
			<div class="alert alert-success" id="siz-loader-success" style="display:none;">
				<span class="glyphicon glyphicon-ok"></span>&nbsp;<span class="siz-msg"></span>
			</div>
			<div class="row">
				<div class="col-sm-offset-3 col-sm-6" id="siz-export-panel">
					<h3>Processing...</h3>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var AJAX_URL = "main.php?s=ajax&m=transaksi";
function do_load() {
	_ajax_send({
		act: 'flush.stage',
		stage: <?php echo json_encode($stageName); ?>
	}, function(response){
		if (response.status == 'ok') {
			var downloadHTML = "";
			downloadHTML += response.message;
			$("#siz-export-panel").hide();
			$("#siz-loader-success .siz-msg").html(downloadHTML);
			$("#siz-loader-success").fadeIn(250);
		} else {
			$("#siz-export-panel").hide();
			$("#siz-export-alert")
			.html("<span class=\"glyphicon glyphicon-warning-sign\"></span> Terjadi kesalahan: "+response.error)
			.fadeIn(250);
		}
	}, "Sedang memproses...", AJAX_URL);
}
function init_page() {
	do_load();
}
</script>