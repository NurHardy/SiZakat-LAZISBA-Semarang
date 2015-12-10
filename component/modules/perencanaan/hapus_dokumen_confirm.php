<?php
/*
 * hapus_dokumen_confirm.php
 * ==> Halaman konfirmasi hapus dokumen
 *
 * AM_SIZ_RA_CONFHPSDOKUMEN | Konfirmasi hapus/clear dokumen
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) exit;
	
	$tahunDokumen = $_GET['th'];
	if (empty($tahunDokumen) || !is_numeric($tahunDokumen)) {
		show_error_page("Argumen tidak lengkap.");
		return;
	}
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$backUrl		= "javascript:backAway();";
	
	$rowDokumen = ra_cek_dokumen($tahunDokumen);
	if (!$rowDokumen) return;
	
	$SIZPageTitle .= "Hapus Dokumen Perencanaan Tahun ".$tahunDokumen;
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Hapus Dokumen",null,true);

	// Cek dokumen, apakah mempunyai agenda..?
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	$queryCek = sprintf("SELECT COUNT(*) AS j FROM ra_agenda WHERE YEAR(tgl_mulai)=%d",
			$tahunDokumen);
	$jumlahAgenda = querybuilder_getscalar($queryCek);
	
	if ($jumlahAgenda != null) {
		
	} else {
		$errorDesc = mysqli_error($mysqli);
		show_error_page("Terjadi kesalahan internal: ".$errorDesc);
		return;
	}
	
	
	
?>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
var RA_HOME_URL = "<?php echo ra_gen_url("home"); ?>";
function show_error(errorText) {
	$("#siz-alert-infos").hide();
	$("#siz-alert-errors").hide().html(errorText).fadeIn(250);
}
function show_info(infoText) {
	$("#siz-alert-errors").hide();
	$("#siz-alert-infos").hide().html(infoText).fadeIn(250);
}
function submit_delete() {
	$("#siz-btn-submit").prop('disabled','disabled');
	var serializedForm = $("#siz-form-chk-hpsdok").serialize();
	_ajax_send(
		serializedForm
	, function(response){
		if (response.status=='ok') {
			$("#siz-btn-cancel, #siz-btn-submit").hide();
			$("#siz-btn-ok").prop("disabled","disabled").show();
			$("#siz-alert-text").fadeOut(250,function(){
				show_info("<span class='glyphicon glyphicon-ok'></span> Dokumen perencanaan (<b>"+
						response.agenda_deleted+"</b> agenda) berhasil dihapus.");
			});
		} else {
			show_error("<b>Terjadi kesalahan</b>: "+response.error);
			$("#siz-btn-submit").removeAttr('disabled');
		}
	}, "Sedang memproses...", AJAX_URL);
	return false;
}
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
			<form action="#" method="post" onsubmit="return submit_delete();" id="siz-form-chk-hpsdok">
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="glyphicon glyphicon-list"></i>									
						</span>
						<h5>Hapus Dokumen Perencanaan</h5>
					</div>
					<div class="widget-content">
						<div class="alert alert-danger" id="siz-alert-errors" style="display:none;"></div>
						<div class="alert alert-success" id="siz-alert-infos" style="display:none;"></div>
<?php if ($jumlahAgenda > 0) { //== Jika terdapat agenda ================= ?>
						<div class="alert alert-warning alert-icon alert-icon-warning" id="siz-alert-text">
							<b>Anda akan menghapus dokumen perencanaan berikut beserta agenda dan rinciannya.</b><br>
							Jika Anda yakin dan mengerti apa yang Anda dilakukan, klik 'Hapus Dokumen'.
							Harap diperhatikan bahwa data yang sudah terhapus tidak dapat dikembalikan lagi.
						</div>
<?php } else { //================== Jika tidak terdapat agenda =========== ?>
						<div class="alert alert-info alert-icon alert-icon-info" id="siz-alert-text">
							Silakan klik 'Hapus Dokumen' untuk melanjutkan.
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
							<div class="col-md-8">
								<input type="hidden" name="siz_submit" value="<?php echo "siz-".date("Ymd-His");?>" />
								<button type="submit" class="btn btn-danger btn-block" id="siz-btn-submit">
									<span class="glyphicon glyphicon-trash"></span> Hapus Dokumen</button>
								<a href="<?php echo htmlspecialchars(ra_gen_url("home"));?>" class="btn btn-default btn-block"
									id="siz-btn-ok" style="display:none;">
										<span class="glyphicon glyphicon-home"></span> </a>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<input type="hidden" name="act" value="dokumen.delete" />
				<input type="hidden" name="th" value="<?php echo $tahunDokumen; ?>" />
			</form>
		</div>
	</div>
</div>