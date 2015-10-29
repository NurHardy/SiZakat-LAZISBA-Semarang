<?php
/*
 * form_penerimaan.php
 * ==> Tampilan form dan proses menyimpan penerimaan
 *
 * AM_SIZ_FRMPENERIMAAN | Tampilan form penerimaan
 * ------------------------------------------------------------------------
 */
	if (! isset ( $isEditing ))
		$isEditing = false;
	
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
		$trxData ['keterangan'] = $transactionData ['keterangan'];
	} else {
		$trxData ['tanggal'] = date ( "Y-m-d" );
	}
?>
<script type="text/javascript">

$(document).ready(function(){   
	$('#aset').on('ifChecked', function(){
        $('#field_namabrg, #field_wujud, #field_harga_satuan').slideDown('fast');
        $('#field_transfer, #field_nota, #field_akun, #field_amilin').slideUp('fast');
	}).on('ifUnchecked', function(event){
        $('#field_namabrg, #field_wujud, #field_harga_satuan').slideUp('fast');
        $('#field_transfer, #field_nota, #field_akun, #field_amilin').slideDown('fast');
	});

	$('#transfer').on('ifChecked', function(){
        $('#field_bank, #field_nota').slideDown('fast');
        $('#field_aset').slideUp('fast');
	}).on('ifUnchecked', function(event){
        $('#field_bank').slideUp('fast');
        $('#field_aset, #field_nota').slideDown('fast');
	});
});

function validateData(){
	if (($('#transfer').is(":checked")) && ($("#select_bank")[0].selectedIndex <= 0)){ 
		alert('Bank untuk transfer belum dipilih');
        return false;
    };
	return true;
};
</script>

<div class="col-12">
	<div class="widget-box">
		<div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="icon-book"></i><span>Tambah Transaksi Penerimaan</span>
				</h5>
			</div>
			<div class="widget-content nopadding">
				<form class="form-horizontal row-fluid"
					action="component/server/func_input_penerimaan.php" Method="POST"
					onsubmit="return validateData()">
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
						<label class="control-label span3" for="normal-field">Tanggal</label>
						<div class="controls span5">
							<input required='required' type="text"
								data-date-format="yyyy-mm-dd"
								value="<?php echo $trxData['tanggal'];?>"
								class="datepicker form-control input-small span5" name='tanggal'
								value='' style='width: 80%;' />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_aset" style="display: block;">
						<label class="control-label span3" for="normal-field"></label>
						<div class="controls span5">
							<input type="checkbox" name="aset" id="aset" value="1"> <label
								for="aset">Barang</label> <a href="#" class="tip"
								title="Jika menerima berupa barang, cek"> <span
								class="glyphicon glyphicon-question-sign"></span>
							</a>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_transfer" style="display: block;">
						<label class="control-label span3" for="normal-field"></label>
						<div class="controls span5">
							<input type="checkbox" name="transfer" id="transfer" value="1"> <label
								for="transfer">Transfer</label> <a href="#" class="tip"
								title="Jika penerimaan melalui transfer bank, cek"> <span
								class="glyphicon glyphicon-question-sign"></span>
							</a>
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
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_akun" style="display: block;">
						<label class="control-label span3" for="normal-field">Jenis Akun</label>
						<div class="controls span5">
							<select name='trans' class="input-small" style='width: 80%;'
								data-placeholder="-- Pilih Akun --">
								<option value="">- Pilih -</option>
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
							<select name='muzakki' class="input-small" style='width: 80%;'
								data-placeholder="-- Pilih Muzakki --" required>
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
							<select name='amilin' class="input-small" style='width: 80%;'
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
						id="field_namabrg" style="display: none;">
						<label class="control-label span3" for="normal-field">Nama Barang</label>
						<div class="controls span5">
							<input type="text" id="normal-field"
								class="form-control input-small" name="namabrg"
								style='width: 80%;' />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_bank" style="display: none;">
						<label class="control-label span3" for="normal-field">Bank</label>
						<div class="controls span5">
							<select name='bank' id='select_bank' class="input-small"
								style='width: 80%;' data-placeholder="-- Pilih Bank --">
								<option value="">- Pilih -</option>
					 <?php
						$q3 = mysqli_query ( $mysqli, "SELECT * FROM bank" );
						while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
							echo "
								<option value='$p3[id_bank]'>$p3[id_bank] - $p3[bank]</option>
								";
						}
						?> 
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_wujud" style="display: none;">
						<label class="control-label span3" for="normal-field">Wujud</label>
						<div class="controls span5">
							<select name='wujud' id='select_wujud' class="input-small"
								style='width: 80%;' data-placeholder="-- Pilih Wujud Barang --">
								<option></option>
					 <?php
						$q3 = mysqli_query ( $mysqli, "SELECT * FROM aset_wujud" );
						while ( $p3 = mysqli_fetch_array ( $q3 ) ) {
							echo "
								<option value='$p3[id_wujud]'>$p3[id_wujud] - $p3[wujud]</option>
								";
						}
						?> 
					</select>
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_jumlah_set" style="display: block;">
						<label class="control-label span3" for="normal-field">Jumlah</label>
						<div class="controls span5">
							<input type="text" id="normal-field number"
								class="form-control input-small" name="jumlah"
								required='required' style='width: 80%;' pattern="[0-9]"
								value="<?php echo intval($trxData['jumlah']); ?>" />
						</div>
					</div>

					<div class="form-row control-group row-fluid form-group"
						id="field_harga_satuan" style="display: none;">
						<label class="control-label span3" for="normal-field">Harga
							Satuaan</label>
						<div class="controls span5">
							<input type="text" id="normal-field"
								class="form-control input-small" name="harga_satuan"
								style='width: 80%;' />
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

					<div class="form-actions" style="display: block;">
						<button type="submit" name='save'
							class="btn btn-primary btn-small">
					
					<?php
					
echo ($isEditing ? "<span class=\"glyphicon glyphicon-pencil\"></span> Ubah Transaksi" : "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Transaksi");
					?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>