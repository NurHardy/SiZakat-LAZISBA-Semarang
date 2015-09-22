<?php
// Output: HTML
// =======================
// Untuk AM_SIZ_STG_PENERIMAAN

	$showForm = true;
	$requestErrors = array ();
	$trxData = null;
	
	if (! isset ( $_POST ['id'] )) {
		$requestErrors [] = "Argument expected.";
	} else {
		$idStage = intval ( $_POST ['id'] );
		$queryGetRecord = sprintf ( "SELECT * FROM stage_penerimaan WHERE id_stage=%d", $idStage );
		$resultGetRecord = mysqli_query ( $mysqli, $queryGetRecord );
		
		if ($resultGetRecord != null) {
			$trxData = mysqli_fetch_assoc ( $resultGetRecord );
			if ($trxData == null) {
				$requestErrors [] = "Data transaksi tidak ditemukan dalam database.";
			}
		} else {
			$requestErrors [] = "Query error: " . mysqli_error ( $mysqli );
		}
	}
	
	// Jika ada error, jangan tampilkan form...
	if (! empty ( $requestErrors )) {
		$showForm = false;
	}
	
	if (! empty ( $requestErrors )) {
		echo "<div class='alert alert-danger'>\n";
		foreach ( $requestErrors as $itemError ) {
			echo "<div><span class='glyphicon glyphicon-alert'></span> " . $itemError . "</div>\n";
		}
		echo "</div>\n";
	}
?>
<tr class="editing_row" id="siz_frm_stgpenerimaan_<?php echo $idStage; ?>">
	<td colspan='9'>
		<small>Tanggal load: <b><?php echo $trxData['tgl_load']; ?></b></small>
<?php if ($showForm) { // =========== IF SHOW FORM ====================== ?>
	<div class="editing-form-ctr" style="display:none;">
		<form action="#save" method="post"
				onsubmit="return submit_trx_penerimaan(<?php echo $idStage; ?>);">
			<div class='row'>
				<div class="col-md-6">
					<h4><span class="glyphicon glyphicon-pencil"></span> Edit Item Transaksi Penerimaan</h4>
				</div>
				<div class="col-md-6" style="text-align: right;">
					<a href="#close" onclick="return close_form_trx_penerimaan(<?php echo $idStage; ?>);">&times; Batal</a>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-4">
					<div class="form-row control-group row-fluid form-group">
						<label class="control-label span3" for="siz-tgl-trx">Tanggal</label>
						<div class="controls span5">
							<input required='required' type="text"
								data-date-format="yyyy-mm-dd" id="siz-tgl-trx"
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
	echo (isset ( $trxData ['no_nota'] ) ? $trxData ['no_nota'] : "");
	?>" />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_akun" style="display: block;">
						<label class="control-label span3" for="normal-field">Jenis Akun</label>
						<div class="controls span5">
							<select name='trans' class="use_select2" style='width: 80%;'
								data-placeholder="-- Pilih Akun --">
								<option value=""></option>
					<?php
	$q1 = mysqli_query ( $mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)" );
	while ( $p1 = mysqli_fetch_array ( $q1 ) ) {
		$isSelected = ($trxData ['kode_akun'] == $p1 ['kode']);
		echo "<option value='{$p1[kode]}' " . ($isSelected ? "selected" : "") . ">";
		echo $p1 ['kode'] . " - " . $p1 ['namaakun'] . "</option>\n";
	}
	?>
					</select>
						</div>
					</div>
				</div>
				<!-- /col-4 -->
				<div class="col-md-4">
					<div class="form-row control-group row-fluid form-group"
						id="field_muzakki" style="display: block;">
						<label class="control-label span3" for="normal-field">Donatur</label>
						<div class="controls span5">
							<div>
								<div><?php echo htmlspecialchars($trxData ['nama_donatur']); ?></div>
								<div>
									<span class="glyphicon glyphicon-envelope"></span> <?php
	echo htmlspecialchars ( $trxData ['alamat_donatur'] );
	?></div>
	<?php
				$linkTambah = "main.php?s=form_muzakki&nama=".urlencode($trxData ['nama_donatur']).
								"&amp;alamat=".urlencode($trxData ['alamat_donatur']);
				echo "<a href=\"".$linkTambah."\" target=\"_blank\">
					<i class='glyphicon glyphicon-plus'></i> Tambah Donatur Baru</a>\n";
				?>
							</div>
							<div>
								<select name='muzakki' id="select2_muzakki" style='width: 80%;'
									data-placeholder="-- Pilih Muzakki --" required>
									<option value="">- Selected -</option>
					<?php
	if ($trxData['id_donatur'] > 0) {
		require_once COMPONENT_PATH."\\libraries\\helper_user.php";
		$dataDonatur = cek_user_id($trxData['id_donatur']);
		if ($dataDonatur != null) {
			echo "<option value='{$trxData['id_donatur']}' selected>";
			echo $dataDonatur['nama'];
			echo "</option>\n";
		}
	} else {
		
	}
	// $q3 = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 1" );
	// while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
	// $isSelected = ($trxData ['id_donatur'] == $p3 ['id_user']);
	// echo "<option value='{$p3[id_user]}' " . ($isSelected ? "selected" : "") . ">";
	// echo $p3 ['id_user'] . " - " . $p3 ['nama'] . "</option>\n";
	// }
	?>
			</select>
							</div>
						</div>
					</div>

					<div>Amilin: <b><?php echo htmlspecialchars($trxData ['nama_amilin']); ?></b></div>
					<div class="form-row control-group row-fluid form-group"
						id="field_amilin" style="display: block;">
						<label class="control-label span3" for="normal-field">Amilin</label>
						<div class="controls span5">
							<select name='amilin' class="use_select2" style='width: 80%;'
								data-placeholder="-- Pilih Amilin --" required>
								<option value=""></option>
					<?php
	$sql = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 99" );
	while ( $pecah = mysqli_fetch_array ( $sql ) ) {
		$isSelected = ($trxData ['id_teller'] == $pecah ['id_user']);
		echo "<option value=\"{$pecah[id_user]}\" " . ($isSelected ? "selected" : "") . ">";
		echo $pecah ['id_user'] . " - " . $pecah ['nama'] . "</option>";
	}
	?>
					</select>
						</div>
					</div>
					<div class="form-row control-group row-fluid form-group"
						id="field_jumlah_set" style="display: block;">
						<label class="control-label span3" for="siz-nominal">Jumlah</label>
						<div class="controls span5">
							<div class="input-group">
								<div class="input-group-addon">Rp</div>
								<input type="text" id="siz-nominal"
									class="form-control input-small" name="jumlah"
									required='required' style='width: 80%;'
									value="<?php echo intval($trxData['jumlah']); ?>" />
							</div>
						</div>
					</div>
				</div>
				<!-- /col-4 -->
				<div class="col-md-4">
					<div class="form-row control-group row-fluid" id="field_keterangan"
						style="display: block;">
						<label class="control-label span3" for="siz-keterangan">Keterangan</label>
						<div class="controls span8">
							<textarea name="keterangan" class="span8" style='width: 80%;'
								id="siz-keterangan"><?php
	echo ($trxData ['keterangan']);
	?></textarea>
						</div>
					</div>
					<div class="form-row control-group row-fluid form-group"
						id="field_bank" style="display: block;">
						<label class="control-label span3" for="normal-field">Bank</label>
						<div class="controls span5">
							<select name='bank' class="use_select2" style='width: 80%;'
								data-placeholder="-- Pilih Bank --" required>
					<?php
	if (empty($trxData ['id_bank'])) {
		echo "<option value=\"0\">- Transaksi Cash -</option>";
	}
	$sql = mysqli_query ( $mysqli, "SELECT * FROM bank" );
	while ( $pecah = mysqli_fetch_array ( $sql ) ) {
		$isSelected = ($trxData ['id_bank'] == $pecah ['id_bank']);
		echo "<option value=\"{$pecah[id_bank]}\" " . ($isSelected ? "selected" : "") . ">";
		echo $pecah ['bank'] . " - " . $pecah ['no_rekening'] . "</option>";
	}
	?>
					</select>
						</div>
					</div>
					<div class="form-row control-group row-fluid form-group"
						id="field_th_kubah" style="display: block;">
						<div class='row'>
							<div class='col-md-6'>
								<label class="control-label span3" for="siz-th-kubah">
								Tahun Penyaluran (khusus KUBAH)</label>
								<div class="controls span5">
									<input type="text" id="siz-th-kubah"
										class="form-control input-small" name="th_kubah"
										style='width: 80%;' value="<?php
			echo (isset ( $trxData ['th_kubah'] ) ? $trxData ['th_kubah'] : "");
			?>" />
								</div>
							</div>
							<div class='col-md-6'>
								<label class="control-label span3" for="siz-th-ramadhan">
								Tahun Ramadhan</label>
								<div class="controls span5">
									<input type="text" id="siz-th-ramadhan"
										class="form-control input-small" name="th_ramadhan"
										style='width: 80%;' value="<?php
			echo (isset ( $trxData ['th_ramadhan'] ) ? $trxData ['th_ramadhan'] : "");
			?>" />
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-footer">
						<button type="button" class="btn btn-default siz_btncancel"
							onclick="return close_form_trx_penerimaan(<?php echo $idStage; ?>);"
							id="siz_overlaydlg_cancel">Batal</button>
						<button type="submit" class="btn btn-primary"
							id="siz_overlaydlg_ok">
							<span class='glyphicon glyphicon-ok'></span> Simpan
						</button>
					</div>
				</div>
				<!-- /col-4 -->
			</div>
			<!-- /row -->
			<input type="hidden" name="act" value="set.stagepenerimaan" />
		</form>
	</div>
<?php } //============== END IF showForm ============= ?>
</td>
</tr>