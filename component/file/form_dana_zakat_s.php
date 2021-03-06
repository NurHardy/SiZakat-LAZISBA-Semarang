<?php 
	$breadCrumbPath[] = array("Transaksi Harian","main.php?s=transaksi",false);
	$breadCrumbPath[] = array("Tambah Pengeluaran","main.php?s=form_dana_zakat_s",true);
	
	require_once COMPONENT_PATH.'/libraries/querybuilder.php';
	require_once COMPONENT_PATH.'/file/transaksi_harian/helper_transaksi.php';

	$currentUserId = $_SESSION['iduser'];
	$currentDate = date("Y-m-d");
	
	$queryPenerimaanHariIni = sprintf(
			"SELECT SUM(jumlah) AS jumlah FROM penerimaan WHERE tanggal='%s'",
			$currentDate
	);
	$queryPengeluaranHariIni = sprintf(
			"SELECT SUM(jumlah) AS jumlah FROM penyaluran WHERE tanggal='%s'",
			$currentDate
	);
	
	$jumlahPenerimaanHariIni = querybuilder_getscalar($queryPenerimaanHariIni);
	$jumlahPengeluaranHariIni = querybuilder_getscalar($queryPengeluaranHariIni);
?>
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.min.js"></script>
<script type='text/javascript'>
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
function submit_pengeluaran() {
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
	$(document).ready(function(){
		$('.jenis').change(function(){
			
			var jns = $(this).val();
			var tgl = $('.tgl').val();
			
			$.ajax({
				type:'POST',
				data:'jenis='+jns+'&tgl='+tgl,
				url:'component/server/list_jenis.php',
				success:function(msg){
					$('.jmlsalur').val(msg);
					$.ajax({
						type:'POST',
						data:'jenis='+jns,
						url:'component/server/list_sumber_jenis.php',
						success:function(msg){
							//alert(msg);
							$('.sumber11').html(msg);
						}
					});
				}
			});			
		});
	});
</script>
		<div class="col-md-8">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Tambah Transaksi Pengeluaran/Penyaluran</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="component/server/server_add_dana_zakat.php" Method="POST">
					<div class="form-group">
						<?php
							if(ISSET($_SESSION['success']) || ISSET($_SESSION['error'])){
								if(ISSET($_SESSION['success'])){
									echo '<div class="alert alert-success" style="margin:10px;">'.$_SESSION['success'].'</div>';
									unset($_SESSION['success']);
								}
								
								if(ISSET($_SESSION['error'])){
									echo '<div class="alert alert-error" style="margin:10px;">'.$_SESSION['error'].'</div>';
									unset($_SESSION['error']);
								}
							}
						?>
					</div>
										  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="siz-tanggal">Tanggal Transaksi</label>
						<div class="controls span3">
						  <input type="text" data-date-format="yyyy-mm-dd" id="siz-tanggal"
						  	value="<?php echo date('Y-m-d');?>" class="datepicker form-control input-small tgl"
						  	name="tgl" required="required" style='width: 150px;'/>
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="siz-nota">No Nota/Transaksi</label>
						<div class="controls span5">
						  <input type="text" id="siz-nota" class="form-control input-small"
						  	name="no_nota" value="" style='width:80%;' placeholder="Nomor nota (jika ada)">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Jenis Transaksi</label>
						<div class="controls span5">
							<select name="jenis_transaksi" class='jenis siz-use-select2' style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
							<option></option>
							<?php
								include_once "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM akun WHERE (jenis = '2') AND (idakun NOT IN (SELECT idParent FROM akun))");
								// echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
								while( $pecah = mysqli_fetch_array($sql)) {
									echo "<option value=\"".$pecah['kode']."\">".$pecah['kode']." - ".$pecah['namaakun']."</option>";
								}
							?>
							</select>
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="siz-keterangan">Keterangan</label>
						<div class="controls span8">
							<input name="keterangan" class="span8 form-control" style='width:80%;' required
								id="siz-keterangan"/>
						</div>
					  </div>
					  <!-- 
					  <div class="form-row control-group row-fluid form_sumber">
						<label class="control-label span3" for="normal-field">Sumber Dana</label>
						<div class="controls span5">
						  <select name="sumber" class='sumber11' style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
							<option></option>
						  </select>
						</div>
					  </div>
					   -->
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Amilin/PJ</label>
						<div class="controls span5">
						  <select name="amilin" style='width:80%;' data-placeholder='-- Pilih Penanggung Jawab --'
						  	required="required" class="siz-use-select2">
						  <option ></option>
							<?php
								include "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 99");
								while ( $pecah = mysqli_fetch_array ( $sql ) ) {
									$isSelected = ($pecah['id_user'] == $currentUserId);
									echo "<option value=\"{$pecah['id_user']}\" ".($isSelected?"selected":"").">".
										"{$pecah['id_user']} - {$pecah['nama']}</option>";
								}
							?>
							</select>
						</div>
					  </div>
						  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="siz-jumlah">Jumlah Penyaluran</label>
						<div class="controls span5">
						  <div class="input-group">
							  <span class="input-group-addon">Rp.</span>
							  <input type="text" class="form-control" name="jumlah"
							  	required='required' style='width: 200px;' id="siz-jumlah"
							  	placeholder="Nominal Transaksi"/>
							</div>
						</div>
					  </div>	 
				
					<div class="form-actions">
						<button type="submit" name="save" class="btn btn-primary btn-small">
							<span class="glyphicon glyphicon-ok"></span> Simpan</button>
					</div>
					</form>
				</div>
			</div>						
		</div>
<div class="col-md-4" style="text-align: center;">					
	<ul class="stat-boxes">
		<li style="width:100%">
			<div class="right" style="text-align: center;width:100%;">
				<strong style='color:#459D1C;' id="siz-today-income"><?php 
					echo to_rupiah($jumlahPenerimaanHariIni);
				?></strong>
				Transaksi Penerimaan Hari Ini
			</div>
		</li>
		<li style="width:100%">
			<div class="right" style="text-align: center;width:100%;">
				<strong style='color:#BA1E20;' id="siz-today-outcome"><?php 
					echo to_rupiah($jumlahPengeluaranHariIni);
				?></strong>
				Transaksi Pengeluaran Hari Ini
			</div>
			<hr>
		</li>
	</ul>
	<a href="main.php?s=form_penerimaan" class="btn btn-success btn-block">
		<span class="glyphicon glyphicon-plus"></span> Transaksi Penerimaan
		<span class="glyphicon glyphicon-chevron-right"></span> 
	</a>
</div>	