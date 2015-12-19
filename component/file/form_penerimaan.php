<?php
	$breadCrumbPath[] = array("Transaksi Harian","main.php?s=transaksi",false);
	$breadCrumbPath[] = array("Tambah Penerimaan","main.php?s=form_penerimaan",true);

	require_once COMPONENT_PATH.'/file/transaksi_harian/helper_transaksi.php';
	
	$currentUserId = $_SESSION['iduser'];
	$currentDate = date("Y-m-d");
?>
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.min.js"></script>
<script>
var AJAX_URL = "main.php?s=ajax&m=transaksi";
var currentDate = "<?php echo $currentDate; ?>";
var currentUser = "<?php echo $currentUserId; ?>";
function reset_form() {
	$("#siz-form-error").hide();
	$("#form-penerimaan").find("input[type=text], textarea").val("");
	$(".siz-use-select2").each(function(){
		$(this).select2("val", "");
	});
	$("#siz-tanggal").val(currentDate);
	$("#siz-petugas").val(currentUser);
	
}
function submit_penerimaan() {
	var formData = $("#form-penerimaan").serialize();
	_ajax_send(formData,
	function(response){
		if (response.status == 'ok') {
			//reset_form();
			$("#tbl-last-trx tbody").html(response.html);
			scrollTo("#form-penerimaan");
			$.gritter.add({
				title: 'Transaksi berhasil disimpan!',
				text: 'Transaksi berhasil disimpan',
				image: 'images/icons/success.png',
				fade_out_speed: 200
			});
		} else {
			$("#siz-form-error").html(response.error).slideDown(200);
			scrollTo("#siz-form-error");
		}
		
	}, "Menyimpan...", AJAX_URL);
	return false;
}
function _debug_glitter() {
	$.gritter.add({
		title: 'Transaksi berhasil disimpan!',
		text: 'Transaksi berhasil disimpan',
		image: 'images/icons/success.png',
		fade_out_speed: '100'
	});
}
</script>
<div class="col-md-8">
	<div class="widget-box siz-form-penerimaan">
		<div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="glyphicon glyphicon-plus"></i><span> Tambah Transaksi
						Penerimaan</span>
				</h5>
			</div>
			<div class="widget-content">
				<form class="form-horizontal row-fluid" id="form-penerimaan"
					action="component/server/func_input_penerimaan.php" method="POST"
					onsubmit="return submit_penerimaan()">
					<a href="#" onclick="_debug_glitter();return false;">
						<span class="glyphicon glyphicon-bell"></span> Notify!
					</a>
					<div class="alert alert-danger alert-icon alert-icon-error"
						style="display:none;" id="siz-form-error"></div>
					<div class="form-group">
					<?php
					if (ISSET ( $_SESSION ['success'] ) || ISSET ( $_SESSION ['error'] )) {
						if (ISSET ( $_SESSION ['success'] )) {
							echo '<div class="alert alert-success" style="margin:10px;">' . $_SESSION ['success'] . '</div>';
							unset ( $_SESSION ['success'] );
						}
						
						if (ISSET ( $_SESSION ['error'] )) {
							echo '<div class="alert alert-error" style="margin:10px;">' . $_SESSION ['error'] . '</div>';
							unset ( $_SESSION ['error'] );
						}
					}
					?>
					</div>

					<div class="form-row control-group row-fluid form-group">
						<label class="control-label span3" for="siz-tanggal">Tanggal</label>
						<div class="controls span5">
							<input required='required' type="text"
								data-date-format="yyyy-mm-dd" id="siz-tanggal"
								value="<?php echo $currentDate;?>"
								class="datepicker form-control input-small span5" name='tanggal'
								style='width: 150px;' />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_nota" style="display: block;">
						<label class="control-label span3" for="siz-no-nota">No. Nota</label>
						<div class="controls span5">
							<input type="text" id="siz-no-nota" pattern="^[0-9-\s]+$"
								class="form-control input-small" name="notrans"
								style='width: 80%;' />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_akun" style="display: block;">
						<label class="control-label span3" for="siz-akun">Akad</label>
						<div class="controls span5">
							<select name='trans' id="siz-akun"
								class="form-control input-small siz-use-select2"
								style='width: 80%;' data-placeholder="-- Pilih Akun --">
								<option></option>
					<?php
					$q1 = mysqli_query ( $mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)" );
					while ( $p1 = mysqli_fetch_array ( $q1 ) ) {
						echo "
								<option value='$p1[kode]'>$p1[kode] - $p1[namaakun]</option>
								";
					}
					?>
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_muzakki" style="display: block;">
						<label class="control-label span3" for="siz-muzakki">Muzakki</label>
						<div class="controls span5">
							<select name='muzakki' id="siz-muzakki"
								class="form-control input-small siz-use-select2"
								style='width: 80%;' data-placeholder="-- Pilih Muzakki --"
								required='required'>
								<option></option>
					<?php
					$q3 = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 1" );
					while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
						echo "
								<option value='$p3[id_user]'>$p3[id_user] - $p3[nama]</option>
								";
					}
					?>
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_amilin" style="display: block;">
						<label class="control-label span3" for="siz-petugas">Amilin</label>
						<div class="controls span5">
							<select name='amilin' id="siz-petugas"
								class="form-control input-small siz-use-select2"
								style='width: 80%;' data-placeholder="-- Pilih Amilin --">
								<option></option>
					<?php
					$sql = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 99" );
					while ( $pecah = mysqli_fetch_array ( $sql ) ) {
						$isSelected = ($pecah['id_user'] == $currentUserId);
						echo "<option value=\"{$pecah['id_user']}\" ".($isSelected?"selected":"").">".
							"{$pecah['id_user']} - {$pecah['nama']}</option>";
					}
					?>
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_bank">
						<label class="control-label span3" for="normal-field">Bank</label>
						<div class="controls span5">
							<select name='bank' id='select_bank' class="form-control input-small siz-use-select2"
								style='width: 80%;' data-placeholder="-- Pilih Bank --">
								<option value='0'>Cash</option>
					 <?php
						$q3 = mysqli_query ( $mysqli, "SELECT * FROM bank" );
						while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
							echo "
								<option value='$p3[id_bank]'>$p3[bank] - $p3[no_rekening]</option>
								";
						}
						?> 
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_jumlah_set" style="display: block;">
						<label class="control-label span3" for="siz-jumlah">Jumlah</label>
						<div class="controls span5">
							<div class="input-group">
							  <span class="input-group-addon">Rp.</span>
							  <input type="text" class="form-control" name="jumlah"
							  	required='required' style='width: 200px;' id="siz-jumlah"
							  	placeholder="Nominal Transaksi"/>
							</div>
						</div>
					</div>

					<div class="form-row control-group row-fluid" id="field_keterangan"
						style="display: block;">
						<label class="control-label span3" for="siz-ket">Keterangan</label>
						<div class="controls span8">
							<textarea name="keterangan" class="span8 no-hresize"
								style='width: 80%;' id="siz-ket"></textarea>
						</div>
					</div>

					<div class="form-actions" style="display: block;">
						<button type="submit" name='save'
							class="btn btn-primary btn-small">
							<span class="glyphicon glyphicon-plus"></span> 
					<?php echo ($_GET['s'] == 'form_penerimaan')?"Tambah Transaksi":"Ubah Transaksi";?></button>
					</div>
					<input type="hidden" name="act" value="penerimaan.add" />
					<input type="hidden" name="submit" value="form-penerimaan" />
				</form>
			</div>
		</div>
	</div>
	
	<!-- Tabel trx terakhir -->
	<div class="widget-box">
		<div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="glyphicon glyphicon-clock"></i>
					<span>Transaksi Penerimaan Terakhir</span>
				</h5>
			</div>
			<div class="widget-content nopadding">
				<table class='table table-bordered table-striped table-hover siz-operation-table'
					id="tbl-last-trx">
					<thead>
						<tr>
							<th width='10%'>Tanggal Transaksi</th>
							<th width='15%'>Akad Transaksi</th>
							<th width='15%'>Terima Dari</th>
							<th width='10%'>Jumlah (Rp)</th>
							<th width='5%'>Aksi</th>
						</tr>
					</thead>
					<tbody>
<?php
	echo generate_latest_trx_penerimaan();
?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4" style="text-align: center;">					
	<ul class="stat-boxes">
		<li style="width:100%">
			<div class="right" style="text-align: center;width:100%;">
				<strong style='color:#459D1C;'><?php 
					echo 0;
				?></strong>
				Transaksi Penerimaan Hari Ini
			</div>
		</li>
		<li style="width:100%">
			<div class="right" style="text-align: center;width:100%;">
				<strong style='color:#BA1E20;'><?php 
					echo 0;
				?></strong>
				Transaksi Pengeluaran Hari Ini
			</div>
			<hr>
		</li>
	</ul>
	<a href="main.php?s=form_dana_zakat_s" class="btn btn-danger btn-block">
		<span class="glyphicon glyphicon-plus"></span> Transaksi Pengeluaran
		<span class="glyphicon glyphicon-chevron-right"></span> 
	</a>
</div>	