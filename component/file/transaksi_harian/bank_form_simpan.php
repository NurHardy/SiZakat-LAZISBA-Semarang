<?php
/*
 * bank_form_simpan.php
 * ==> Tampilan form dan proses menyimpan (menambah dan edit) akun bank
 *
 * AM_SIZ_BANKFORM | Tampilan form bank
 * ------------------------------------------------------------------------
 */
	$showForm = true;
	$backUrl = "main.php?s=transaksi&action=bank";
	$formActionUrl = $_SERVER ['REQUEST_URI']; // Aksi ke script ini lagi.
	
	$submitError = array ();
	
	$idBank = 0;
	$isEditing = false;
	$isSubmit = false;
	
	if (isset ( $_GET ['id'] )) {
		$isEditing = true;
		$idBank = intval ( $_GET ['id'] );
	}
	
	// Isi nilai default di sini!
	$bankData = array (
			"nama_bank"		=> "",
			"no_rekening"	=> "",
			"atas_nama"		=> "",
			"saldo_awal"	=> 0,
			"url_gambar"	=> null,
			"erase_logo"	=> false
	);
	
	if (isset ( $_POST ['siz_submit'] )) {
		$isSubmit = true;
		$bankData = array (
			"nama_bank"		=> trim($_POST ['siz_nama']),
			"no_rekening"	=> trim($_POST ['siz_norek']),
			"atas_nama"		=> trim($_POST ['siz_atasnama']),
			"saldo_awal"	=> trim($_POST ['siz_saldo_awal']),
			"url_gambar"	=> trim($_POST ['siz_url_logo']),
			"erase_logo"	=> ($_POST['siz_erase_logo']==1)
		);
		
		//======== Validasi
		if (empty($bankData['nama_bank']) || empty($bankData['no_rekening']) ||
			empty($bankData['atas_nama'])) {
			$submitError[] = "Semua input harus terisi.";
		} else if (!preg_match("/^[0-9-\s]+$/", $bankData['no_rekening'])) {
			$submitError[] = "Nomor rekening harus berupa karakter numerik (0-9) atau dash (-).";
		} else if (!is_numeric($bankData['saldo_awal'])) {
			$submitError[] = "Saldo awal harus numerik!";
		} else if ($bankData['saldo_awal'] < 0) {
			$submitError[] = "Saldo awal tidak boleh negatif!";
		} else {
			// Lolos tahap validasi
			$bankData['saldo_awal'] = intval($bankData['saldo_awal']);
		}
		
		
		// Tidak ada error
		if (empty($submitError)) {
			// Kita butuh ini untuk upload...
			require_once COMPONENT_PATH."\\libraries\\helper_upload.php";
			
			$uploadLogoSetting = array(
				'path' => 'images/bank',
				'exts' => array('png','jpg','jpeg'),
				'size' => 2 * 1024 * 1024, // 2 MB
				'name' => 'siz_new_banklogo'
			);
			$uploadErrorDesc = null;
			$uploadResult = do_upload($uploadLogoSetting, $uploadErrorDesc);
			// Jika ada error
			if (($uploadResult == null) && ($uploadErrorDesc != null)) {
				$submitError[] = $uploadErrorDesc;
			} else {
				require_once COMPONENT_PATH."\\libraries\\querybuilder.php";
				
				//=== Proses simpan ke database
				$fieldToSave = array(
						'no_rekening'	=> $bankData['no_rekening'],
						'atas_nama'		=> $bankData['atas_nama'],
						'bank'			=> $bankData['nama_bank'],
						'saldo_awal'	=> $bankData['saldo_awal']
				);
				if ($uploadResult != null) {
					$fieldToSave['logo'] = $uploadResult['url'];
					$bankData['url_gambar'] = $uploadResult['url'];
				} else if ($bankData['erase_logo']) {
					$fieldToSave['logo'] = null;
					$bankData['url_gambar'] = null;
				}
				$saveQuery = "";
				$actionWord = "";
				$tglSekarang = date("Y-m-d H:i:s");
				if ($isEditing) {
					$fieldToSave['tgl_update'] = $tglSekarang;
					$setQuery = querybuilder_generate_set($fieldToSave);
					$saveQuery = "UPDATE bank SET ".$setQuery." WHERE id_bank=".$idBank;
					$actionWord = "diperbaharui";
				} else {
					$fieldToSave['tgl_submit'] = $tglSekarang;
					$setQuery = querybuilder_generate_set($fieldToSave);
					$saveQuery = "INSERT INTO bank SET ".$setQuery;
					$actionWord = "ditambahkan";
				}
				$saveResult = mysqli_query($mysqli, $saveQuery);
				if ($saveResult == null) {
					$submitError[] = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
				} else {
					$submitInfo = "Informasi bank berhasil ".$actionWord."!";
					$showForm = false;
				}
				
			}
		} else { // If there some error
			if (!empty($_FILES['siz_new_banklogo']['name'])) {
				$uploadNotice = "<span class='glyphicon glyphicon-exclamation-sign'></span>
					Upload batal. Silakan muat ulang file.";
			}
		}
	} else { // If no submit
		if ($isEditing) {
			$queryGet = sprintf ( "SELECT * FROM bank WHERE id_bank=%d", $idBank );
			$resultGet = mysqli_query ( $mysqli, $queryGet );
			if ($resultGet != null) {
				$rowBank = mysqli_fetch_assoc ( $resultGet );
				if ($rowBank == null) {
					$submitError [] = "Data akun bank tidak ditemukan dalam database.";
				} else {
					$bankData ['nama_bank'] = $rowBank ['bank'];
					$bankData ['no_rekening'] = $rowBank ['no_rekening'];
					$bankData ['atas_nama'] = $rowBank ['atas_nama'];
					$bankData ['saldo_awal'] = $rowBank ['saldo_awal'];
					$bankData ['url_gambar'] = $rowBank ['logo'];
				}
			} else {
				$submitError [] = "Terjadi kesalahan internal: " . mysqli_error ( $mysqli );
			}
		}
	} // End if no submit
?>
<div class="col-md-8">
<?php
// Tampilkan notifikasi jika ada
if (!empty($submitError)) {
	echo "<div class=\"alert alert-danger\">\n";
	foreach ($submitError as $itemError) {
		echo "<span class='glyphicon glyphicon-warning-sign'></span> ".$itemError."<br>\n";
	}
	echo "</div>\n";
}
if ($submitInfo) {
	echo "<div class=\"alert alert-success\">\n";
	echo "<span class=\"glyphicon glyphicon glyphicon-ok-sign\"></span> ".$submitInfo."\n";
	echo "</div>\n";
}
if ($showForm) { //========================= ?>
<form class="form-horizontal row-fluid" enctype="multipart/form-data"
		action="<?php echo $formActionUrl;?>" Method="POST">
		<div class="widget-box">
			<div class="box gradient">
				<div class="widget-title">
					<h5>
						<span>Akun bank</span>
					</h5>
				</div>
				<div class="widget-content nopadding">
					<div class="row">
						<div class="col-md-12">
							<div class="form-row control-group row-fluid form-group">
								<label class="control-label span3" for="siz_formbank_nama"> Nama
									Bank </label>
								<div class="controls span5">
									<input required='required' type="text" id="siz_formbank_nama"
										value="<?php echo htmlspecialchars($bankData['nama_bank']);?>"
										class="form-control input-small span5" name='siz_nama'
										placeholder="Nama Bank" maxlength="128" />
								</div>
							</div>
							<div class="form-row control-group row-fluid form-group">
								<label class="control-label span3" for="siz_formbank_norek">No
									Rekening</label>
								<div class="controls span5">
									<input required='required' type="text" id="siz_formbank_norek"
										value="<?php echo htmlspecialchars($bankData['no_rekening']);?>"
										class="form-control input-small span5" name='siz_norek'
										placeholder="Nomor Rekening Bank" maxlength="64" />
								</div>
							</div>
							<div class="form-row control-group row-fluid form-group">
								<label class="control-label span3" for="siz_formbank_atasnama">
									Atas Nama </label>
								<div class="controls span5">
									<input required='required' type="text"
										id="siz_formbank_atasnama"
										value="<?php echo htmlspecialchars($bankData['atas_nama']);?>"
										class="form-control input-small span5" name='siz_atasnama'
										placeholder="Pemilik Rekening" maxlength="128" />
								</div>
							</div>
							<div class="form-row control-group row-fluid form-group">
								<label class="control-label span3" for="siz_saldo_awal"> Saldo
									Awal </label>
								<div class="controls span5">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span> <input
											required='required' type="text" id="siz_saldo_awal"
											value="<?php echo intval($bankData['saldo_awal']);?>"
											class="form-control input-small span5" name='siz_saldo_awal'
											placeholder="Saldo awal" maxlength="64" />
									</div>
								</div>
							</div>
							<div class="form-row control-group row-fluid form-group">
								<label class="control-label span3" for="siz_new_banklogo">
									Logo Bank</label>
								<div class="controls span5">
<?php
	if ($bankData ['url_gambar'] != null) {
		echo "
 		<div style=\"margin-bottom:10px;\">
 			<input type=\"hidden\" name=\"siz_url_logo\"
 				value=\"" . htmlspecialchars ( $bankData ['url_gambar'] ) . "\" />
 			<img src=\"" . htmlspecialchars ( $bankData ['url_gambar'] ) . "\" alt=\"Logo\" />
			<div style=\"font-size: 0.9em;\">
				<input type=\"checkbox\" name=\"siz_erase_logo\" id=\"siz_erase_logo\" value=\"1\" 
				".($bankData['erase_logo']?"checked='checked'":"")."/>
			<label for=\"siz_erase_logo\">Hapus Gambar Logo</label><br>
				Upload baru untuk ganti gambar logo:
			</div>
 		</div>
		";
	}
	
	if (!empty($uploadNotice)) {
		echo "<div class='alert alert-warning'>".$uploadNotice."</div>";
	}
?>
									<input type="file" name="siz_new_banklogo" id="siz_new_banklogo"
										accept=".png,.jpg,.jpeg"/>
									<div style="font-size: 0.9em;">
										File berupa PNG, JPG atau JPEG, max. 2MB
									</div>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<input type="hidden" name="siz_submit"
							value="<?php echo "siz-".date("Ymd-His");?>" /> <a
							href="<?php echo htmlspecialchars($backUrl); ?>">
						<span
							class="glyphicon glyphicon glyphicon-chevron-left"></span>
							Kembali
						</a> -
						<button type="submit" class="btn btn-primary"><?php
	if ($isEditing) {
		echo "<span class=\"glyphicon glyphicon-pencil\"></span> Simpan Akun Bank\n";
	} else {
		echo "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Akun Bank\n";
	}
	?></button>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php } else { //=========== ELSE IF NOT SHOW FORM ========== ?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<a href="<?php echo htmlspecialchars($backUrl); ?>"> <span
						class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali
					</a>
				</div>
			</div>
		</div>
	</div>
<?php } //================== END IF SHOW FORM =============== ?>
</div>
