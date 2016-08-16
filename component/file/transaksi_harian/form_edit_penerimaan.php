<?php
/*
 * form_edit_penerimaan.php
 * ==> Tampilan form edit dan proses menyimpan penerimaan
 *
 * AM_SIZ_FRMEDITPENERIMAAN | Tampilan form edit penerimaan
 * ------------------------------------------------------------------------
 */

	$SIZPageTitle = "Edit Transaksi Penerimaan";
	$breadCrumbPath[] = array("Edit Penerimaan","main.php?s=form_penerimaan",true);

	$trxData = array();
	$processError = $processSuccess = array();
	$showForm = true;
	
	if (! isset ( $isEditing ))
		$isEditing = true;
	
	$trxId = intval($_GET ['id']);
	if ($trxId <= 0) { // Error jika tidak ada ID...
		$errorDescription = "Transaction ID expected.";
		include COMPONENT_PATH . "/file/pages/error.php";
		return;
	}
	
	if (isset($_POST['submit'])) {
		require COMPONENT_PATH."/file/transaksi_harian/functions/simpan_penerimaan.php";
		
		if (empty($processError)) {
			$showForm = false;
			$processSuccess = "Transaksi berhasil diperbarui.";
		}
	} else {
		if ($isEditing) {
			$idPenerimaan = intval ( $_GET ['id'] );
			$queryGet = sprintf ( "SELECT * FROM penerimaan WHERE id_penerimaan=%d", $idPenerimaan );
			$transactionResult = $mysqli->query ( $queryGet );
			$transactionData = ($transactionResult == null ? null : $transactionResult->fetch_array ( MYSQLI_ASSOC ));
			if ($transactionData == null) {
				$errorDescription = "Data transaksi penerimaan tidak ditemukan dalam database.";
				include COMPONENT_PATH . "/file/pages/error.php";
				return;
			}
			$trxData ['tanggal'] = date ( "Y-m-d", strtotime ( $transactionData ['tanggal'] ) );
			$trxData ['jumlah'] = $transactionData ['jumlah'];
			$trxData ['nota'] = $transactionData ['no_nota'];
			$trxData ['id_donatur'] = $transactionData ['id_donatur'];
			$trxData ['id_petugas'] = $transactionData ['id_teller'];
			$trxData ['kode_akun'] = $transactionData ['id_akun'];
			$trxData ['id_bank'] = $transactionData ['id_bank'];
			$trxData ['keterangan'] = $transactionData ['keterangan'];
		
			$trxData ['id_trx']	= $transactionData ['id_penerimaan'];
		} else {
			$trxData ['tanggal'] = date ( "Y-m-d" );
		}
	}
	
	
?>
<script src="js/sizakat/select2_formatter.js"></script>
<script type="text/javascript">

function init_page() {
	var GLOBAL_DELAY = 250;
	$('select.select2_akun').select2({
	    ajax: {
	        type: 'post',
	        delay: GLOBAL_DELAY,
	        dataType: 'json',
	        url: 'main.php?s=ajax&m=akun',
	        data: function (params) {
	          var queryParameters = {
	            q: params.term,
	            act: 'get.akun.penerimaan'
	          };
	          return queryParameters;
	        },
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	      },
	      minimumInputLength: 3,
	      templateResult: formatItemAkun
	});
	$('select.select2_donatur').select2({
	    ajax: {
	        type: 'post',
	        delay: GLOBAL_DELAY,
	        dataType: 'json',
	        url: 'main.php?s=ajax&m=user',
	        data: function (params) {
	          var queryParameters = {
	            q: params.term,
	            act: 'get.user.donatur'
	          };
	          return queryParameters;
	        },
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	      },
	      minimumInputLength: 3,
	      templateResult: formatItemDonatur
	});
}

</script>

<div class="col-md-9">
	<div class="widget-box">
		<div class="box gradient">
			<div class="widget-title">
				<h5>
<?php if ($isEditing) { //--------- If isEditing ----------- ?>
					<span class="glyphicon glyphicon-pencil"></span>
					<span>Edit Transaksi Penerimaan</span>
<?php } else { //------------------ Else If ---------------- ?>
					<span class="glyphicon glyphicon-plus"></span>
					<span>Tambah Transaksi Penerimaan</span>
<?php } //------------------------- End If ----------------- ?>
				</h5>
			</div>
			<div class="widget-content nopadding">
				<div style="padding:10px;">
<?php
if (!empty($processError)) {
	if (!is_array($processError)) $processError = array($processError);
	echo '<div class="alert alert-danger">';
	foreach ($processError as $itemError) {
		echo "<div><span class=\"glyphicon glyphicon-alert\"></span> ".$itemError."</div>\n";
	}
	echo '</div>';
}
if (!empty($processSuccess)) {
	if (!is_array($processSuccess)) $processSuccess = array($processSuccess);
	echo '<div class="alert alert-success">';
	foreach ($processSuccess as $itemSuccess) {
		echo "<div><span class=\"glyphicon glyphicon-ok\"></span> ".$itemSuccess."</div>\n";
	}
	echo '</div>';
}

//-------- Untuk versi lama....
if (ISSET ( $_SESSION ['success'] ) || ISSET ( $_SESSION ['error'] )) {
	if (ISSET ( $_SESSION ['success'] )) {
		echo '<div class="alert alert-success" style="margin:10px;">' . $_SESSION ['success'] . '</div>';
		unset ( $_SESSION ['success'] );
	}
	
	if (ISSET ( $_SESSION ['error'] )) {
		echo '<div class="alert alert-error" style="margin:10px;">' . $_SESSION ['error'] . '</div>';
		unset ( $_SESSION ['error'] );
	}
} ?>
				</div>
<?php if ($showForm) {//=========== TAMPILKAN FORM? ============= ?>
				<form action="<?php
						echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST"
						class="form-horizontal row-fluid" >
	<?php if ($isEditing) { //---------------------------- ?>
					<div class="form-row control-group row-fluid form-group">
						<label class="control-label span3" for="normal-field">ID Transaksi Masuk</label>
						<div class="controls span5">
							<p class="form-control-static"><b><?php
								echo "#".$trxData['id_trx']; ?></b></p>
						</div>
					</div>
	<?php } //------------------- END IF isEditing ------- ?>
					<div class="form-row control-group row-fluid form-group">
						<label class="control-label span3" for="siz-tanggal">Tanggal</label>
						<div class="controls span5">
							<input required='required' type="text"
								data-date-format="yyyy-mm-dd" id="siz-tanggal"
								value="<?php echo $trxData['tanggal'];?>"
								class="datepicker form-control input-small span5" name='tanggal'
								style='width: 80%;' />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_nota" style="display: block;">
						<label class="control-label span3" for="siz-penerimaan-nota">No.
							Nota</label>
						<div class="controls span5">
							<input type="text" id="siz-penerimaan-nota"
								class="form-control input-small" name="notrans" required
								style='width: 80%;'
								value="<?php
								echo (isset ( $trxData ['nota'] ) ? $trxData ['nota'] : "");
								?>" />
							<span class="help-block">
								Nomor nota harus terdiri atas strip atau angka.
							</span>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_akun" style="display: block;">
						<label class="control-label span3" for="normal-field">Jenis Akun</label>
						<div class="controls span5">
							<select name='trans' class="input-small select2_akun" style='width: 80%;'
								data-placeholder="-- Pilih Akun --">
								<!-- <option value="">- Pilih -</option> -->
					<?php
					// Get selected row
					$q1 = mysqli_query ( $mysqli, "SELECT * FROM akun WHERE kode='".$trxData ['kode_akun']."'" );
					if ( $p1 = mysqli_fetch_array ( $q1 ) ) {
						echo "<option value='{$p1['kode']}' selected>";
						echo htmlspecialchars($p1 ['namaakun']) . "</option>\n";
					}
					?>
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_muzakki" style="display: block;">
						<label class="control-label span3" for="normal-field">Donatur</label>
						<div class="controls span5">
							<select name='muzakki' class="input-small select2_donatur" style='width: 80%;'
								data-placeholder="-- Pilih Muzakki --" required='required'>
								<option value="">- Pilih -</option>
					<?php
					$q3 = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 1" );
					while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
						$isSelected = ($trxData ['id_donatur'] == $p3 ['id_user']);
						echo "<option value='{$p3[id_user]}' " . ($isSelected ? "selected" : "") . ">";
						echo $p3 ['id_user'] . " - " . $p3 ['nama'] . "</option>\n";
					}
					?>
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_amilin" style="display: block;">
						<label class="control-label span3" for="normal-field">Amilin</label>
						<div class="controls span5">
							<select name='amilin' class="input-small siz-use-select2" style='width: 80%;'
								data-placeholder="-- Pilih Amilin --">
								<option value="">- Pilih -</option>
					<?php
					$sql = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 99" );
					while ( $pecah = mysqli_fetch_array ( $sql ) ) {
						$isSelected = ($trxData ['id_petugas'] == $pecah ['id_user']);
						echo "<option value=\"{$pecah[id_user]}\" " . ($isSelected ? "selected" : "") . ">";
						echo $pecah ['id_user'] . " - " . $pecah ['nama'] . "</option>";
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
								<input type="text" id="siz-jumlah" style="width:200px;"
									class="form-control input-small" name="jumlah"
									required='required' style='width: 80%;' pattern="[0-9]+"
									value="<?php echo intval($trxData['jumlah']); ?>" />
							</div>
							
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_bank">
						<label class="control-label span3" for="normal-field">Bank</label>
						<div class="controls span5">
							<select name='bank' id='select_bank' class="input-small siz-use-select2"
								style='width: 80%;' data-placeholder="-- Pilih Bank --">
								<option value="0">Cash</option>
					 <?php
						$q3 = mysqli_query ( $mysqli, "SELECT * FROM bank" );
						while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
							$isSelected = ($trxData ['id_bank'] == $p3['id_bank']);
							echo "<option value='{$p3['id_bank']}' ".
								($isSelected?'selected':'').">{$p3['bank']} - {$p3['no_rekening']}".
								"</option>\n";
						}
						?> 
					</select>
						</div>
					</div>
					
					<div class="form-row control-group row-fluid" id="field_keterangan"
						style="display: block;">
						<label class="control-label span3" for="siz-keterangan">Keterangan</label>
						<div class="controls span8">
							<textarea name="keterangan" class="span8 no-hresize" style='width: 80%;'
								id="siz-keterangan" placeholder="Tulis keterangan (opsional)"><?php
							echo ($trxData ['keterangan']);
							?></textarea>
						</div>
					</div>

					<div class="form-actions" style="display: block;">
						<button type="submit" name='save'
							class="btn btn-primary btn-small">
					
					<?php
					
echo ($isEditing ? "<span class=\"glyphicon glyphicon-pencil\"></span> Ubah Transaksi" : "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Transaksi");
					?></button>
					</div>
					<input type="hidden" name="submit" value="siz-form-edit-trx" />
				</form>
<?php } //------------------- END IF tampilkan Form ------- ?>
			</div>
		</div>
	</div>
</div>