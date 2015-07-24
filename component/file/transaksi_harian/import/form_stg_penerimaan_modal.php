<?php

//=======> CAUTION: THIS FILE IS NOT USED YET <================
//=======> For experimental purpose only...

	$showForm = true;
	$requestErrors = array();
	$trxData = null;
	
	if (!isset($_POST['id'])) {
		$requestErrors[] = "Argument expected.";
	} else {
		$idStage = intval($_POST['id']);
		$queryGetRecord = sprintf("SELECT * FROM stage_penerimaan WHERE id_stage=%d", $idStage);
		$resultGetRecord = mysqli_query($mysqli, $queryGetRecord);
		
		if ($resultGetRecord != null) {
			$trxData = mysqli_fetch_assoc($resultGetRecord);
			if ($trxData == null) {
				$requestErrors[] = "Data transaksi tidak ditemukan dalam database.";
			}
		} else {
			$requestErrors[] = "Query error: ".mysqli_error($mysqli);
		}
	}
	
	// Jika ada error, jangan tampilkan form...
	if (!empty($requestErrors)) {
		$showForm = false;
	}
	
?>
<div class="modal-header">
	<button type="button" class="close siz_btncancel" data-dismiss="modal"
		aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<h4 class="modal-title" id="siz_overlaydlg_title">Edit Item Transaksi Penerimaan</h4>
</div>
<div class="modal-body" id="siz_overlaydlg_bodyctr">
<?php

if (!empty($requestErrors)) {
	echo "<div class='alert alert-danger'>\n";
	foreach ($requestErrors as $itemError) {
		echo "<div><span class='glyphicon glyphicon-alert'></span> ".$itemError."</div>\n";
	}
	echo "</div>\n";
}
if ($showForm) { //=========== IF SHOW FORM ====================== ?>
	<div class="form-row control-group row-fluid form-group">
		<label class="control-label span3" for="normal-field">Tanggal</label>
		<div class="controls span5">
			<input required='required' type="text" data-date-format="yyyy-mm-dd"
				value="<?php echo $trxData['tanggal'];?>"
				class="datepicker form-control input-small span5" name='tanggal'
				style='width: 80%;' />
		</div>
	</div>
	
	<div class="form-row control-group row-fluid form-group"
		id="field_nota" style="display: block;">
		<label class="control-label span3" for="siz-penerimaan-nota">No. Nota</label>
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

	<div class="form-row control-group row-fluid form-group"
		id="field_muzakki" style="display: block;">
		<label class="control-label span3" for="normal-field">Donatur</label>
		<div class="controls span5">
			<div>
				<div><?php echo htmlspecialchars($trxData ['nama_donatur']); ?></div>
				<div><span class="glyphicon glyphicon-envelope"></span> <?php
					echo htmlspecialchars($trxData ['alamat_donatur']); ?></div>
			</div>
			<div>
			<select name='muzakki' id="select2_muzakki" style='width: 80%;'
				data-placeholder="-- Pilih Muzakki --" required>
				<option value="0" selected="selected">- Selected -</option>
					<?php
						//$q3 = mysqli_query ( $mysqli, "SELECT * FROM user WHERE level = 1" );
						//while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
						//	$isSelected = ($trxData ['id_donatur'] == $p3 ['id_user']);
						//	echo "<option value='{$p3[id_user]}' " . ($isSelected ? "selected" : "") . ">";
						//	echo $p3 ['id_user'] . " - " . $p3 ['nama'] . "</option>\n";
						//}
					?>
			</select></div>
		</div>
	</div>

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
		<label class="control-label span3" for="normal-field">Jumlah</label>
		<div class="controls span5">
			<div class="input-group">
     		 <div class="input-group-addon">Rp</div>
			<input type="text" id="normal-field number"
				class="form-control input-small" name="jumlah" required='required'
				style='width: 80%;'
				value="<?php echo intval($trxData['jumlah']); ?>" />
			</div>
		</div>
	</div>

	<div class="form-row control-group row-fluid" id="field_keterangan"
		style="display: block;">
		<label class="control-label span3" for="normal-field">Keterangan</label>
		<div class="controls span8">
			<textarea name="keterangan" class="span8" style='width: 80%;'><?php
			echo ($trxData ['keterangan']);
			?></textarea>
		</div>
	</div>
<?php } //============== END IF showForm ============= ?>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default siz_btncancel"
		id="siz_overlaydlg_cancel" data-dismiss="modal">Batal</button>
<?php if ($showForm) { //=== ?>
	<button type="submit" class="btn btn-primary" id="siz_overlaydlg_ok">
		<span class='glyphicon glyphicon-ok'></span> Simpan
	</button>
<?php } //================== ?>
</div>

