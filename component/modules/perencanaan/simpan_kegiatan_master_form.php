<?php
/*
 * simpan_kegiatan_master_form.php
 * ==> Proses menyimpan kegiatan master dan menampilkannya berupa form
 *
 * AM_SIZ_RA_FRMMASTKGT | Form Master Kegiatan
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	ra_check_privilege();
	
	// Proses simpan kegiatan
	$idKegiatan		= (isset($_GET['id'])?intval($_GET['id']):-1);
	$isEditing		= ($idKegiatan > 0);
	$formActionUrl	= $_SERVER['REQUEST_URI']; // Aksi ke script ini lagi.
	
	$showForm		= true;
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	
	$backUrl		= ($isEditing?ra_gen_url("master-kegiatan",null,"id=".$idKegiatan):ra_gen_url("list"));
	if (isset($_GET['ref'])) $backUrl = $_GET['ref'];
	
	$submitError	= array();
	$submitInfo		= null;
	
	$jumlahRincian	= 0;
	
	if (isset($_POST['siz_submit'])) {
		$mKegiatanNama			= trim($_POST['mast-kg-nama']);
		$mKegiatanDivisi		= intval($_POST['mast-kg-divisi']);
		$mKegiatanAkun			= trim($_POST['mast-kg-akun']);
		$mKegiatanJenis			= intval($_POST['mast-kg-jenis']);
		$mKegiatanPrioritas		= intval($_POST['mast-kg-prioritas']);
		$mKegiatanKeterangan	= trim($_POST['mast-kg-keterangan']);
		$mKegiatanNamaRinc		= @(is_array($_POST['mast-kg-n-rincian'])?$_POST['mast-kg-n-rincian']:array());
		$mKegiatanNilaiRinc		= @(is_array($_POST['mast-kg-v-rincian'])?$_POST['mast-kg-v-rincian']:array());
		
		$jumlahRincian 			= count($mKegiatanNamaRinc);
		// Validasi -------------------
		if (empty($mKegiatanNama)) $submitError[] = "Harap isi nama kegiatan.";
		if (empty($mKegiatanDivisi)) {
			$submitError[] = "Harap pilih divisi.";
		} else {
			if ($isAdmin) {
				if (!array_key_exists($mKegiatanDivisi, $listDivisi))
					$submitError[] = "ID Divisi tidak valid.";
			} else {
				if ($mKegiatanDivisi != $divisiUser)
					$submitError[] = "ID divisi tidak diperkenankan.";	
			}
		}
		if (empty($mKegiatanAkun)) {
			$submitError[] = "Harap pilih akun pengeluaran.";
		} else {
			// WARNING: tidak ada pengecekan akun dalam database
			
		}
		if (!array_key_exists($mKegiatanJenis, $listJenisKegiatan)) {
			$submitError[] = "ID Jenis kegiatan tidak valid.";
		}
		if (!array_key_exists($mKegiatanPrioritas, $listPrioritas)) {
			$submitError[] = "ID prioritas tidak valid.";
		}
		
		// Bangun query jika tidak ada error.
		if (empty($submitError)) {
			$mysqli->autocommit(FALSE);
			$dataMasterKegiatan = array(
				"nama_kegiatan"			=> $mKegiatanNama,
				"keterangan_kegiatan"	=> $mKegiatanKeterangan,
				"jenis_kegiatan"		=> $mKegiatanJenis,
				"akun_pengeluaran"		=> $mKegiatanAkun,
				"divisi"				=> $mKegiatanDivisi,
				"prioritas"				=> $mKegiatanPrioritas
			);
			require_once COMPONENT_PATH."\libraries\querybuilder.php";
			$querySimpan  = ($isEditing?"UPDATE ":"INSERT INTO ")." ra_kegiatan SET ";
			$querySimpan .= querybuilder_generate_set($dataMasterKegiatan);
			if ($isEditing) $querySimpan .= " WHERE id_kegiatan=".$idKegiatan;
			$qResult = $mysqli->query($querySimpan);
			
			// Jika query update/insert berhasil dilakukan...
			if ($qResult === true) {
				// Ambil semua array rincian
				// Rincian hanya diproses jika membuat master kegiatan baru...
				if (!$isEditing && (!empty($_POST['mast-kg-n-rincian']))) {
					$newIdKegiatan = $mysqli->insert_id;
					if (is_array($_POST['mast-kg-n-rincian'])) {
						$tglSekarang = date("Y-m-d H:i:s");
						$qRincian = "INSERT INTO ra_rincian_awal (id_kegiatan, nama_rincian, jumlah_anggaran, tgl_tambah, id_user) ".
							"VALUES ";
						// Bangun query sebanyak jumlah rincian yang disubmit.
						foreach ($mKegiatanNamaRinc as $idx => $itemRincian) {
							$jumlahAnggaran = intval($mKegiatanNilaiRinc[$idx]);
							$qRincian .= sprintf("(%d,'%s',%d,'%s',%d),",
								$newIdKegiatan,
								$mysqli->real_escape_string($itemRincian),
								$jumlahAnggaran,
								$tglSekarang, $_SESSION['iduser']
							);
						}
						$qRincian = trim($qRincian, ","); // Hapus koma terakhir
						$qResRincian = $mysqli->query($qRincian);
						if ($qResRincian === false) {
							// Jika error query insert rincian, maka insert kegiatan dibatalkan (rollback)
							$submitError[] = "Terjadi kesalahan internal saat memproses rincian. ".
								"Info: ".$mysqli->error;
							$mysqli->rollback();
						}
					}
				}
				if (empty($submitError)) {
					$qResult = $mysqli->commit();
					$submitInfo = "Master Kegiatan <b>".htmlspecialchars($mKegiatanNama)."</b> berhasil ".
						($isEditing?"diperbaharui.":"disimpan.");
					$showForm = false;
				}
			} else {
				$submitError[] = "Terjadi kesalahan internal. Info: ".$mysqli->error;
			}
		} // End if submit error
	} else { // Jika tidak ada data POST
		if ($isEditing) {
			$queryKegiatan =
				"SELECT * FROM ra_kegiatan WHERE (id_kegiatan={$idKegiatan})";
			$resultKegiatan = mysqli_query($mysqli, $queryKegiatan);
			$rowKegiatan = mysqli_fetch_array($resultKegiatan);
			
			if ($rowKegiatan != null) {
				if (!$isAdmin && ($rowKegiatan['divisi'] != $divisiUser)) {
					show_error_page( "Anda tidak berhak mengedit kegiatan ini. Silakan hubungi Administrator." ); return;
					return;
				}
				
				$mKegiatanNama			= $rowKegiatan['nama_kegiatan'];
				$mKegiatanDivisi		= $rowKegiatan['divisi'];
				$mKegiatanAkun			= $rowKegiatan['akun_pengeluaran'];
				$mKegiatanPrioritas		= $rowKegiatan['prioritas'];
				$mKegiatanKeterangan	= $rowKegiatan['keterangan_kegiatan'];
				$mKegiatanJenis			= $rowKegiatan['jenis_kegiatan'];
			} else {
				show_error_page( "Data kegiatan tidak ditemukan dalam database." );
				return;
			}
		} else { // Jika bukan edit (buat baru)
			$mKegiatanJenis		= 1; // Default adalah kegiatan rutin
		}
	}
	
	// Get akun pengeluaran
	$queryGetAkun = "SELECT * FROM akun WHERE (Jenis = '2') AND (idakun NOT IN (SELECT idParent FROM akun))";
	$resultGekAkun = $mysqli->query($queryGetAkun);

	// Generate layout
	ra_print_status($namaDivisiUser);
	
	// Tampilkan notifikasi jika ada
	if ($submitInfo) {
		echo "<div class=\"alert alert-success\">\n";
		echo "<span class=\"glyphicon glyphicon glyphicon-ok-sign\"></span> ".$submitInfo."\n";
		echo "</div>\n";
	}
	
if ($showForm) { //============================== FORM DITAMPILKAN === ?>
<script>
var currentNewRowId = <?php echo $jumlahRincian; ?>;
function tambah_rincian() {
	var newRowId = "siz_newrincian_"+currentNewRowId;
	var newRow = "<tr id=\""+newRowId+"\" style=\"display:none;\">";
	newRow += "<td><input type=\"text\" name=\"mast-kg-n-rincian["+currentNewRowId+"]\" placeholder=\"Tulis nama rincian\" class=\"form-control\" required/></td>";
	newRow += "<td><input type=\"text\" name=\"mast-kg-v-rincian["+currentNewRowId+"]\" placeholder=\"Tulis jumlah anggaran\" class=\"form-control\" required/></td>";
	newRow += "<td><a href=\"#\" onclick=\"return hapus_rincian("+currentNewRowId+");\" ";
	newRow += "class=\"btn btn-danger btn-xs\">Hapus</a></td></tr>";
	$("#siz_row_tambah_rincian").before(newRow);
	$("#"+newRowId).fadeIn(200);
	currentNewRowId++;
	return false;
}
function hapus_rincian(idRincian) {
	var uResp = confirm("Hapus item rincian?");
	if (uResp) {
		var rowId = "siz_newrincian_"+idRincian;
		$("#"+rowId).fadeOut(200,function(){
			$(this).remove();
		});
	}
	return false;
}
</script>
<?php
	if (!empty($submitError)) {
		echo "<div class=\"alert alert-danger\">\n";
		foreach ($submitError as $itemError) {
			echo $itemError."<br>\n";
		}
		echo "</div>\n";
	}
?>
	<form action="<?php echo htmlspecialchars($formActionUrl); ?>" method="POST">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading"><h3 class="panel-title"><?php
			if ($isEditing) {
				echo "<span class=\"glyphicon glyphicon-pencil\"></span>\n";
				echo "Edit Informasi Master Kegiatan";
			} else {
				echo "<span class=\"glyphicon glyphicon-plus\"></span>\n";
				echo "Tambah Master Kegiatan";
			}
			?></h3></div>
			<div class="panel-body">
				<div class="row">
				  <div class="col-md-12">
					<table class="siz-table-detail-input">
						<tr>
							<td><label for="mast-kg-nama">Nama Kegiatan</label></td>
							<td><input type="text" name="mast-kg-nama" placeholder="Nama Kegiatan"
								value="<?php echo htmlspecialchars($mKegiatanNama); ?>"
								required class="form-control" id="mast-kg-nama"/></td>
						</tr>
						<tr>
							<td><label for="mast-kg-divisi">Divisi</label></td>
							<td>
<?php if ($isAdmin) { // =========== Jika Admin ===== ?>
							<select name="mast-kg-divisi" id="mast-kg-divisi" style="width:100%;" required
									data-placeholder="- Pilih Divisi -">
								<option></option>
							<?php
								$ctrDivisi = 1;
								$maxDivisi = count($listDivisi)-2;
								for ($ctrDivisi=1;$ctrDivisi<=$maxDivisi;$ctrDivisi++) {
									echo "<option value=\"{$ctrDivisi}\" ";
									if ($mKegiatanDivisi == $ctrDivisi) echo "selected";
									echo ">{$listDivisi[$ctrDivisi]}</option>\n";
								}
							?></select>
<?php } else { //==================== Jika bukan Admin ===== ?>
								<?php echo $listDivisi[$divisiUser]; ?>
								<input type="hidden" name="mast-kg-divisi" value="<?php echo $divisiUser; ?>" />
<?php } //=========================== End If Admin ========= ?>
							</td>
						</tr>
						<tr>
							<td><label for="mast-kg-akun">Akun Pengeluaran</label></td>
							<td><select name="mast-kg-akun" id="mast-kg-akun" style="width:100%;" required
									data-placeholder="- Pilih Akun -">
								<option></option>
							<?php
								while ($rowAkun = $resultGekAkun->fetch_array(MYSQLI_ASSOC)) {
									echo "<option value=\"{$rowAkun['kode']}\" ";
									if ($mKegiatanAkun == $rowAkun['kode']) echo "selected";
									echo ">{$rowAkun['kode']} {$rowAkun['namaakun']}</option>\n";
								}
							?></select></td>
						</tr>
						<tr>
							<td><label for="mast-kg-jenis">Jenis</label></td>
							<td><select name="mast-kg-jenis" id="mast-kg-jenis" style="width:100%;" required
									data-placeholder="- Pilih Jenis -">
							<?php
								foreach ($listJenisKegiatan as $idxJenis => $lblJenis) {
									echo "<option value=\"{$idxJenis}\" ";
									if ($mKegiatanJenis == $idxJenis) echo "selected";
									echo ">{$lblJenis}</option>\n";
								}
							?></select></td>
						</tr>
						<tr>
							<td><label for="mast-kg-prioritas">Prioritas</label> <a href="<?php echo htmlspecialchars(ra_gen_url("documentation")); ?>">
									<span class="glyphicon glyphicon-question-sign"></span></a></td>
							<td><select name="mast-kg-prioritas" id="mast-kg-prioritas" style="width:100%;" required
									data-placeholder="- Pilih Prioritas -">
								<option></option>
							<?php
								foreach ($listPrioritas as $idxPrioritas => $lblPrioritas) {
									echo "<option value=\"{$idxPrioritas}\" ";
									if ($mKegiatanPrioritas == $idxPrioritas) echo "selected";
									echo ">{$lblPrioritas}</option>\n";
								}
							?></select>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="mast-kg-keterangan">Keterangan/Deskripsi:</label>
								<textarea class="siz-desc-container" id="mast-kg-keterangan"
									placeholder="Keterangan Kegiatan" name="mast-kg-keterangan"><?php
									echo htmlspecialchars($mKegiatanKeterangan);
								?></textarea>
							</td>
						</tr>
						
					</table>
				  </div>
				</div> <!-- End row detil -->
			</div>
		</div> <!-- End panel informasi kegiatan -->
	</div>
	<?php if (!$isEditing) { //=========== Form rincian tidak ditampilkan saat editing === ?>
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span>
					Rincian Awal Kegiatan</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th style="width:200px;">Nama Rincian</th>
							<th>Jumlah</th>
							<th style="width:75px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
	<?php
	if (!empty($mKegiatanNamaRinc)) {
		$ctrIdRinc = 0;
		foreach ($mKegiatanNamaRinc as $idx => $itemRincian) {
			$jumlahAnggaran = intval($mKegiatanNilaiRinc[$idx]);
			$cRowId = "siz_newrincian_".$ctrIdRinc;
			echo "<tr id=\"".$cRowId."\">";
			echo "<td><input type=\"text\" name=\"mast-kg-n-rincian[".$ctrIdRinc."]\" value=\"".
					htmlspecialchars($itemRincian)."\" placeholder=\"Tulis nama rincian\" ".
					"class=\"form-control\" required/></td>";
			echo "<td><input type=\"text\" name=\"mast-kg-v-rincian[".$ctrIdRinc."]\" value=\"".
					$jumlahAnggaran."\" placeholder=\"Tulis jumlah anggaran\" ".
					"class=\"form-control\" required/></td>";
			echo "<td><a href=\"#\" onclick=\"return hapus_rincian(".$ctrIdRinc.");\" ";
			echo "class=\"btn btn-danger btn-xs\">Hapus</a></td></tr>\n";
			$ctrIdRinc++;
		}
	} // End if empty Rincian
	?>
						<tr id="siz_row_tambah_rincian">
							<td><a href="#" class="btn btn-xs btn-primary"
								onclick="return tambah_rincian();">
								<span class="glyphicon glyphicon-plus"></span> Tambah</a></td>
							<td>&nbsp;</td><td>&nbsp;</td>
						</tr>
					</tbody>
				</table>	
			</div>
		</div> <!-- End panel -->
	</div><!-- End row content -->
	<?php } // ================= END isEditing ======================= ?>	
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