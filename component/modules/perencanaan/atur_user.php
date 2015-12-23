<?php
/*
 * atur_user.php
 * ==> Halaman user management untuk mengatur hak akses pengguna
 *
 * AM_SIZ_RA_USERMGMT | Halaman User Management
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) exit;
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	if (!$isAdmin) {
		show_error_page("Anda tidak mempunyai otoritas untuk mengakses halaman.");
		return;
	}
	$SIZPageTitle = "Atur User";
	$breadCrumbPath[] = array("Managemen User",ra_gen_url("user"),true);
	
	// --- Get list user
	$queryListUser = "SELECT * FROM user WHERE level=99";
	$resultListUser = mysqli_query($mysqli, $queryListUser);
	if (!$resultListUser) {
		show_error_page("Query error: ".$mysqli->error);
		exit;
	}
	
	ra_print_status($namaDivisiUser); ?>

<!-- Gritter -->
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.min.js"></script>
<script>var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";</script>
<script>
function edit_priv(idUser) {
	var editElmtId = "#ra_user_"+idUser;
	var idDivisi = $(editElmtId).data("divid");

	$(editElmtId+" td.ra_edit_area").append($("#ra_select_template").html());
	$(editElmtId+" td.ra_edit_area select.cmb_divisi").val(idDivisi+'');
	$(editElmtId+" .hide_on_edit").hide();
	$(editElmtId+" .show_on_edit").show();
	return false;
}
function cancel_edit(idUser) {
	var editElmtId = "#ra_user_"+idUser;
	$(editElmtId+" td.ra_edit_area .ra_edit_cmb").remove();
	$(editElmtId+" .hide_on_edit").show();
	$(editElmtId+" .show_on_edit").hide();
	return false;
}
function apply_priv(idUser) {
	var editElmtId = "#ra_user_"+idUser;
	
	var idDivisi = $(editElmtId+" td.ra_edit_area select.cmb_divisi").val();;
	_ajax_send({
		act: 'user.setpriv',
		id: idUser,
		priv: idDivisi
	}, function(response){
		if (response.status == 'ok') {
			$.gritter.add({
				title: "Pembaruan Berhasil",
				text: "Setting hak akses berhasil diperbarui..",
				image: 'images/icons/success.png',
				fade_out_speed: 200
			});
			$(editElmtId).data("divid", idDivisi);
			$(editElmtId+" .ra_curdivisi").html(response.new_priv);
			cancel_edit(idUser);
		} else {
			$.gritter.add({
				title: 'Terjadi kesalahan',
				text: response.error,
				image: 'images/icons/error.png',
				fade_out_speed: 200
			});
		}
	}, "Menyimpan...", AJAX_URL);
	return false;
}
</script>
<div style="display:none;" id="ra_select_template">
	<div class="ra_edit_cmb">
		<select class="cmb_divisi form-control" name="ra_cmb_divisi">
<?php foreach ($listDivisi as $idDivisi => $namaDivisi) {
	echo "<option value='".$idDivisi."'>".$namaDivisi."</option>\n";
} //----------- end foreach -- ?>
		</select>
	</div>
</div>
<div class="col-md-10">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Hak Akses Pengguna</h5>
		</div>
		<div class="widget-content">
			<div class="row"><div class='col-md-12'>
				<table class="table table-bordered table-hover siz-operation-table">
					<thead>
						<tr>
							<th style="width:120px;">Username</th>
							<th style="width:300px;">Nama</th>
							<th style="width:250px;">Divisi</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
<?php while ($rowUser = mysqli_fetch_array($resultListUser)) { //---------------------
		$curIdUser = $rowUser['id_user']
	?>
	<tr id="ra_user_<?php echo $curIdUser; ?>" data-divid="<?php echo $rowUser['divisi']; ?>">
		<td><b><?php echo $rowUser['username']; ?></b></td>
		<td><?php echo $rowUser['nama']; ?>
			<div style="font-size:0.9em;">
				<span class="glyphicon glyphicon-envelope"></span>
				<?php echo htmlspecialchars($rowUser['email']); ?></div></td>
		<td class="ra_edit_area"><span class="hide_on_edit ra_curdivisi"><?php echo $listDivisi[$rowUser['divisi']]; ?></span></td>
		<td><span class="hide_on_edit">
			<a href="#edit-priv" onclick="return edit_priv(<?php echo $curIdUser; ?>);">
				<span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</a>
			</span>
			<span class="show_on_edit">
				<a href="#apply-priv" onclick="return apply_priv(<?php echo $curIdUser; ?>);">
					<span class="glyphicon glyphicon-ok"></span>&nbsp;Simpan</a> -
				<a href="#cancel" onclick="return cancel_edit(<?php echo $curIdUser; ?>);" class="red_link">
					<span class="glyphicon glyphicon-remove"></span>&nbsp;Batal</a>
			</span>
		</td></tr>
<?php } //--------------------------------- End foreach -------- ?>
					</tbody>
				</table>
			</div></div>
		</div>
	</div>
</div>
