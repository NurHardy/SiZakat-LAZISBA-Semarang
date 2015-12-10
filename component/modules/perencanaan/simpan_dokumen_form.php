<?php
/*
 * simpan_dokumen_form.php
 * ==> Menyimpan atau menambah dokumen dan menampilkan dalam form
 *
 * AM_SIZ_RA_FRMDOKUMEN | Form Catatan dokumen perencanaan tahunan
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege(RA_ID_ADMIN)) return;
	
	$formActionUrl = $_SERVER['REQUEST_URI']; // Aksi ke script ini lagi.
	$backUrl = ra_gen_url("home");
	
	$submitError	= array();
	
	$showForm		= true;
	$tahunDokumen	= -1;
	$isEditing		= (isset($_GET['th']));
	$rowDokumen		= null;
	
	$dokTahun		= intval(date("Y")+1);
	$dokCatatan		= "";
	
	if ($isEditing) {
		$tahunDokumen = intval($_GET['th']);
		$rowDokumen = ra_cek_dokumen($tahunDokumen);
		
		$backUrl = ra_gen_url("rekap",$tahunDokumen);
		$SIZPageTitle = "Edit Catatan Dokumen Perencanaan";
		$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
		$breadCrumbPath[] = array("Edit Catatan",null,true);
		
		if (!$rowDokumen) return; // Pesan error sudah dihandle oleh ra_cek_dokumen
	} else {
		$SIZPageTitle = "Tambah Dokumen Perencanaan";
		$breadCrumbPath[] = array("Tambah Dokumen",null,true);
	}
	
	$availableYear = array(2014,2015,2016,2017,2018,2019);
	
	if (isset($_POST['siz_submit'])) {
		$dokTahun = ($isEditing?$tahunDokumen:intval($_POST['dok-tahun']));
		$dokCatatan = trim($_POST['dok-catatan']);
		
		if (!$isEditing) {
			$rowDokumen = ra_cek_dokumen($dokTahun, false);
			if ($rowDokumen) {
				$submitError[] = "Dokumen perencanaan sudah ditambahkan.";
			}
		}
		if (empty($submitError)) {
			//$tglSekarang = date("Y-m-d H:i:s");
			// Bangun query jika tidak ada error
			$querySimpan  = ($isEditing?"UPDATE":"INSERT INTO")." ra_dokumen SET ";
			if (!$isEditing) $querySimpan .= " tahun_dokumen=".$dokTahun.", ";
			$querySimpan .= sprintf("catatan='%s'", mysqli_escape_string($mysqli, $dokCatatan));
			if ($isEditing) $querySimpan .= " WHERE tahun_dokumen=".$dokTahun;
			$qResult = $mysqli->query($querySimpan);
			$queryCount++;
				
			if ($qResult === true) {
				$submitInfo = "Dokumen perencanaan tahun ".$dokTahun." berhasil ".
						($isEditing?"diperbaharui.":"ditambahkan.");
				$showForm = false;
			}
		}
	} else {
		if ($isEditing) {
			if ($rowDokumen) {
				$dokTahun = $tahunDokumen;
				$dokCatatan = $rowDokumen['catatan'];
			}
		}
	}
	// Generate layout
	ra_print_status($namaDivisiUser);
	
	// Tampilkan notifikasi jika ada
	if ($submitInfo) {
		echo "<div class=\"alert alert-success\">\n";
		echo "<span class=\"glyphicon glyphicon glyphicon-ok-sign\"></span> ".$submitInfo."\n";
		echo "</div>\n";
	}
	
	if ($showForm) { //============================== FORM DITAMPILKAN === ?>

<form action="<?php echo htmlspecialchars($formActionUrl); ?>" method="POST">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php
			if ($isEditing) {
				echo "<span class=\"glyphicon glyphicon-pencil\"></span>\n";
				echo "Edit Catatan Dokumen Perencanaan";
			} else {
				echo "<span class=\"glyphicon glyphicon-plus\"></span>\n";
				echo "Tambah Dokumen Perencanaan";
			}
			?></h3></div>
			<div class="panel-body">
<?php
	if (!empty($submitError)) {
		echo "<div class=\"alert alert-danger\">\n";
		foreach ($submitError as $itemError) {
			echo "<div><span class=\"glyphicon glyphicon-alert\"></span> ".$itemError."</div>\n";
		}
		echo "</div>\n";
	}
?>
				<table class="siz-table-detail-input">
					<tr>
						<td><label for="dok-tahun">Tahun Dokumen</label></td>
						<td>
	<?php if ($isEditing) {echo $dokTahun;}
			else {//===================== ?>
							<select name="dok-tahun" id="dok-tahun" data-placeholder="- Pilih Tahun -"
								class="form-control" required>
								<option value="">- Pilih Tahun -</option>
						<?php
							foreach ($availableYear as $pilTahun) {
								echo "<option value=\"{$pilTahun}\" ";
								if ($pilTahun == $dokTahun) echo "selected";
								echo ">".$pilTahun."</option>\n";
							}
						?>
							</select>
	<?php } //== END IF ===========?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<label for="dok-catatan">Catatan Dokumen Perencanaan:</label>
							<textarea class="siz-desc-container" id="dok-catatan"
								placeholder="Catatan Dokumen Perencanaan" name="dok-catatan"><?php
								echo htmlspecialchars($dokCatatan);
							?></textarea>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- End Panel -->
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<input type="hidden" name="siz_submit" value="<?php echo "siz-".date("Ymd-His");?>" />
					<a href="<?php echo htmlspecialchars($backUrl); ?>">
						<span class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali</a> - 
					<button type="submit" class="btn btn-primary"><?php
						if ($isEditing) {
							echo "<span class=\"glyphicon glyphicon-pencil\"></span> Simpan Catatan\n";
						} else {
							echo "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Dokumen\n";
						} ?></button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php } else { //=============== FORM TIDAK DITAMPILKAN ============ ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<a href="<?php echo htmlspecialchars($backUrl); ?>">
				<span class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali</a>
		</div>
	</div>
<?php } //====================== END IF ============================ ?>