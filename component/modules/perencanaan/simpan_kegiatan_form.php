<?php
/*
 * simpan_kegiatan_form.php
 * ==> Proses menyimpan kegiatan ke dalam perencanaan dan menampilkannya berupa form
 *
 * AM_SIZ_RA_FRMKGT | Form Kegiatan
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) exit;
	
	// Proses simpan kegiatan
	$tahunDokumen	= intval((isset($_GET['th'])?$_GET['th']:date("Y")));
	$formActionUrl	= $_SERVER['REQUEST_URI']; // Aksi ke script ini lagi.
	
	$showForm		= true;
	$idKegiatan		= (isset($_GET['id'])?intval($_GET['id']):-1);
	$isEditing		= ($idKegiatan > 0);
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	// Jika filter divisi diset
	if (!$isEditing && isset($_GET['div'])) {
		$isAdmin = false;
		$divisiUser = intval($_GET['div']);
	}
	$backUrl		= (empty($_GET["ref"])?ra_gen_url("rekap", $tahunDokumen):$_GET["ref"]);
	$submitError	= array();
	$submitInfo		= null;
	
	$jumlahRincian	= 0;
	
	$kegiatanNama	= "";
	$rowKegiatan	= null;
	
	if ($isEditing) { // Jika sedang edit, cek idKegiatan
		$queryCatatan =
			"SELECT c.*, k.nama_kegiatan FROM ra_catatan_kegiatan AS c, ra_kegiatan AS k ".
			"WHERE (k.id_kegiatan=c.id_kegiatan) AND (k.id_kegiatan={$idKegiatan}) AND (tahun={$tahunDokumen})";
		
		$resultCatatan = mysqli_query($mysqli, $queryCatatan);
		$rowKegiatan = mysqli_fetch_array($resultCatatan);
		
		if ($rowKegiatan != null) {
			if (!$isAdmin && ($rowKegiatan['divisi'] != $divisiUser)) {
				show_error_page( "Anda tidak berhak mengedit kegiatan ini. Silakan hubungi Administrator." ); return;
				return;
			}
			
			$kegiatanNama			= $rowKegiatan['nama_kegiatan'];
			$kegiatanCatatan		= $rowKegiatan['catatan'];
		} else {
			show_error_page( "Data kegiatan tidak ditemukan dalam database." );
			return;
		}
	}
	
	if (isset($_POST['siz_submit'])) {
		$kegiatanId			= intval($_POST['kg-id']);
		$kegiatanCatatan	= trim($_POST['kg-catatan']);
		
		// Validasi -------------------
		
		
		// Bangun query jika tidak ada error.
		if (empty($submitError)) {
			$querySimpan  = sprintf(
				"INSERT INTO ra_catatan_kegiatan SET id_kegiatan=%d, tahun=%d, catatan='%s'",
				$kegiatanId, $tahunDokumen, $mysqli->real_escape_string($kegiatanCatatan)
			);
			$qResult = $mysqli->query($querySimpan);
			
			if ($qResult === true) {
				$submitInfo = "Kegiatan <b>".htmlspecialchars($mKegiatanNama)."</b> berhasil ".
					"ditambahkan ke dokumen perencanaan ".$tahunDokumen;
				$showForm = false;
			} else {
				$submitError[] = "Terjadi kesalahan internal. Info: ".$mysqli->error;
			}
		}
	} else {
		if ($isEditing) {
			
		} else { // Jika bukan edit (buat baru)
			$kegiatanNama		= "";
			$kegiatanCatatan	= "";
		}
	}
	
	if (!$isEditing && $showForm) {
		// Get list kegiatan sesuai privilege
		$queryGetKegiatan = "SELECT * FROM ra_kegiatan ".
			"WHERE ".($isAdmin?"":"divisi=".$divisiUser." AND ")."id_kegiatan NOT IN ".
				"(SELECT id_kegiatan FROM ra_catatan_kegiatan WHERE tahun=".$tahunDokumen.")".
			"ORDER BY divisi";
		$resultGetKegiatan = $mysqli->query($queryGetKegiatan);
		if ($resultGetKegiatan->num_rows == 0) {
			$showForm = false;
			$ketDivisi = ($divisiUser>0?" untuk divisi <b>".$listDivisi[$divisiUser]."</b>":"");
			$submitError[] = "<span class=\"glyphicon glyphicon-warning-sign\"></span> ".
				"Oops, tidak ada lagi kegiatan yang bisa ditambahkan".$ketDivisi.".";
		}
	}

	// Generate layout
	ra_print_status($namaDivisiUser);
	
	// Tampilkan notifikasi jika ada
	if (!empty($submitError)) {
		echo "<div class=\"alert alert-danger\">\n";
		foreach ($submitError as $itemError) {
			echo $itemError."<br>\n";
		}
		echo "</div>\n";
	}
	if ($submitInfo) {
		echo "<div class=\"alert alert-success\">\n";
		echo "<span class=\"glyphicon glyphicon glyphicon-ok-sign\"></span> ".$submitInfo."\n";
		echo "</div>\n";
	}
	

if ($showForm) { //============================== FORM DITAMPILKAN === ?>
	<form action="<?php echo htmlspecialchars($formActionUrl); ?>" method="POST">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title">
				<span class="glyphicon glyphicon-plus"></span>
				Tambah Kegiatan Ke Perencanaan</h3></div>
			<div class="panel-body">
				<div class="row">
				  <div class="col-md-12">
					<table class="siz-table-detail-input">
						<tr>
							<td>Tahun Pelaksanaan:</td>
							<td><strong>: <?php echo $tahunDokumen; ?></strong></td>
						</tr>
						<tr>
							<td><label for="kg-id">Kegiatan</label></td>
							<td>
	<?php if ($isEditing) { //================= Jika sedang edit ====== ?>
			<strong><?php echo $kegiatanNama; ?></strong>
	<?php } else { //========================== Jika buat baru ======== ?>
			<select name="kg-id" id="mast-kg-id" style="width:100%;" required
				data-placeholder="- Pilih Kegiatan -">
			<option></option>
		<?php
			$currentDivisi = -1;
			while ($rowKegiatan = $resultGetKegiatan->fetch_array(MYSQLI_ASSOC)) {
				$idDivisi = $rowKegiatan['divisi'];
				if ($idDivisi != $currentDivisi) {
					if ($currentDivisi != -1) echo "</optgroup>\n";
					echo "<optgroup label=\"".$listDivisi[$idDivisi]."\">";
					$currentDivisi = $idDivisi;
				}
				echo "<option value=\"{$rowKegiatan['id_kegiatan']}\" ";
				if ($kegiatanId == $rowKegiatan['id_kegiatan']) echo "selected";
				echo ">".htmlspecialchars($rowKegiatan['nama_kegiatan'])."</option>\n";
			}
			if ($currentDivisi != -1) echo "</optgroup>";
		?></select>
	<?php } //========================== End If ======== ?></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<a href="<?php
								echo htmlspecialchars(ra_gen_url("tambah-kegiatan-master",null,"ref=".urlencode($_SERVER['REQUEST_URI'])));
								?>"><span class="glyphicon glyphicon-plus"></span> Buat Kegiatan Master Baru</a>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="kg-catatan">Catatan pelaksanaan kegiatan:</label>
								<textarea class="siz-desc-container" id="kg-catatan"
									placeholder="Keterangan Kegiatan" name="kg-catatan"><?php
									echo htmlspecialchars($kegiatanCatatan);
								?></textarea>
							</td>
						</tr>
						
					</table>
				  </div>
				</div> <!-- End row detil -->
			</div>
		</div> <!-- End panel informasi kegiatan -->
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
							echo "<span class=\"glyphicon glyphicon-pencil\"></span> Simpan Master Kegiatan\n";
						} else {
							echo "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Master Kegiatan\n";
						} ?></button>
				</div>
			</div>
		</div>
	</div>
	</form>
<?php } else { //======= Jika Form tidak ditampilkan ================= ?>
<div class="panel panel-default">
	<div class="panel-body">
		<a href="<?php echo htmlspecialchars($backUrl); ?>">
			<span class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali</a>
	</div>
</div>
<?php } //============== END IF form ditampilkan =====================