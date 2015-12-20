<?php
/*
 * simpan_agenda_form.php
 * ==> Proses menyimpan agenda kegiatan dan menampilkannya berupa form
 *
 * AM_SIZ_RA_FRMAGENDA | Form Agenda Kegiatan
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) exit;
	
	$tahunDokumen	= $_GET['th'];
	if (empty($tahunDokumen)) {
		show_error_page("Argumen tidak lengkap.");
		return;
	}
	
	// Cek Dokumen
	if (!ra_cek_dokumen($tahunDokumen)) return;
	
	$formActionUrl	= $_SERVER['REQUEST_URI']; // Aksi ke script ini lagi.
	
	$showForm		= true;
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$minDate		= sprintf("%04d-01-01", $tahunDokumen);
	$maxDate		= sprintf("%04d-12-31", $tahunDokumen);
	
	// Cek ID agenda apabila tersedia...
	$idKegiatan		= -1;
	$idAgenda		= (isset($_GET['id'])?intval($_GET['id']):-1);
	$isEditing		= ($idAgenda > 0);
	$rowAgenda		= null;
	if ($isEditing) {
		$SIZPageTitle = "Edit Agenda Kegiatan";
		$queryAgenda = sprintf("SELECT * FROM ra_agenda WHERE (id_agenda=%d)",$idAgenda);
		$resultAgenda = mysqli_query($mysqli, $queryAgenda);
		$queryCount++;
		$rowAgenda	= mysqli_fetch_array($resultAgenda);
		
		if ($rowAgenda != null) {
			$idKegiatan		= $rowAgenda['id_kegiatan'];
		} else {
			show_error_page( "Data agenda kegiatan tidak ditemukan!" );
			return;
		}
	} else {
		$SIZPageTitle = "Tambah Agenda Kegiatan";
		$idKegiatan		= (isset($_GET['idk'])?intval($_GET['idk']):-1);
	}
	
	// Cek kegiatan yang akan diproses
	$queryKegiatan = sprintf(
			"SELECT k.*, a.namaakun FROM ra_kegiatan AS k, akun AS a ".
			"WHERE (id_kegiatan=%d) AND k.akun_pengeluaran=a.kode",
			$idKegiatan);
	$resultKegiatan = mysqli_query($mysqli, $queryKegiatan);
	$queryCount++;
	$rowKegiatan = mysqli_fetch_array($resultKegiatan);
	
	if ($rowKegiatan != null) {
		$kegiatanNama			= $rowKegiatan['nama_kegiatan'];
		if (!$isAdmin && ($rowKegiatan['divisi'] != $divisiUser)) {
			show_error_page( "Anda tidak berhak menambah/edit agenda kegiatan <b>".htmlspecialchars($kegiatanNama).
					"</b>. Silakan hubungi Administrator atau divisi terkait." ); return;
			return;
		}
	} else {
		show_error_page( "Data kegiatan tidak ditemukan dalam database." );
		return;
	}
	
	$backUrl		= ra_gen_url("kegiatan",$tahunDokumen,"id=".$idKegiatan);
	if (isset($_GET['ref'])) $backUrl = $_GET['ref'];
	
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Rekap Agenda",ra_gen_url("kegiatan",$tahunDokumen,"id=".$idKegiatan),false);
	$breadCrumbPath[] = array($SIZPageTitle,null,true);
	
	// Proses simpan kegiatan
	$submitError	= array();
	$submitInfo		= null;
	
	$agSelectBulan	= 0;
	$agTglMulai		= null;
	$agTglSelesai	= null;
	
	$agNamaRinc		= array();
	$agNilaiRinc	= array();
	$jumlahRincian	= 0;
	
	if (isset($_POST['siz_submit'])) {
		// Cek apakah kegiatan sudah ditambahkan ke dokumen perencanaan
		$dataCatatan = ra_cek_catatan_kegiatan($idKegiatan, $tahunDokumen);
		if ($dataCatatan==null) {
			$submitError[] = "Kegiatan belum ditambahkan ke dalam dokumen perencanaan.";
		}
		
		// Ambil parameter form
		$agPrioritas	= intval($_POST['ag-prioritas']);
		$agJenis		= intval($_POST['ag-jenis']); // Jenis pengadaan kegiatan		
		$agCatatan		= trim($_POST['ag-catatan']);
		
		if ($agJenis==1) {
			$agTglMulai		= trim($_POST['ag-1hari-tglmulai']);
			$agTglSelesai	= $agTglMulai;
		} else if ($agJenis==2) {
			$agTglMulai		= trim($_POST['ag-range-tglmulai']);
			$agTglSelesai	= trim($_POST['ag-range-tglselesai']);
		} else if ($agJenis==3) {
			$agSelectBulan	= intval($_POST['ag-select-bulan']);
			if (($agSelectBulan >= 1) && ($agSelectBulan <= 12)) {
				$agTglMulai		= sprintf("%04d-%02d-01", $tahunDokumen, $agSelectBulan);
				$agTglSelesai	= $agTglMulai;
			} else {
				$submitError[] = "Bulan tidak valid!.";
			}
		} else {
			$agJenis = 0;
			$submitError[] = "Silakan pilih jenis pelaksanaan agenda kegiatan.";
		}
		
		$agNamaRinc		= @(is_array($_POST['ag-n-rincian'])?$_POST['ag-n-rincian']:array());
		$agNilaiRinc	= @(is_array($_POST['ag-v-rincian'])?$_POST['ag-v-rincian']:array());

		// Validasi -------------------
		if (validate_date($agTglMulai, "Y-m-d")) {
			if (validate_date($agTglSelesai, "Y-m-d")) {
				if (strtotime($agTglMulai) <= strtotime($agTglSelesai)) {
					// Tanggal mulai = tanggal selesai?
					if ($agTglMulai == $agTglSelesai) {
						$agJenis = 1; // Anggap event satu hari
					}
					$tahunAgenda = date("Y",strtotime($agTglMulai));
					if ($tahunAgenda != $tahunDokumen) {
						$submitError[] = "Tahun Agenda kegiatan bukan ".$tahunDokumen.".";
					}
				} else {
					$submitError[] = "Range tanggal tidak valid!";
				}
			} else {
				$submitError[] = "Format tanggal selesai tidak valid!";
			}
		} else {
			$submitError[] = "Format tanggal tidak valid!";
		}
		
		if (!array_key_exists($agPrioritas, $listPrioritas)) {
			$submitError[] = "ID prioritas tidak valid.";
		}
		
		// Bangun query jika tidak ada error.
		if (empty($submitError)) {
			$tglSekarang = date("Y-m-d H:i:s");
			$mysqli->autocommit(FALSE);
			$dataAgendaKegiatan = array(
				"jenis_agenda"			=> $agJenis,
				"tgl_mulai"				=> $agTglMulai,
				"tgl_selesai"			=> $agTglSelesai,
				"prioritas_agenda"		=> $agPrioritas,
				"catatan"				=> $agCatatan
			);
			if (!$isEditing) {
				// Hitung jumlah anggaran...
				$jmlAnggaran = 0;
				foreach ($agNamaRinc as $idx => $itemRincian) {
					$jmlAnggaran += intval($agNilaiRinc[$idx]);
				}
				$dataAgendaKegiatan['jumlah_anggaran']	= $jmlAnggaran;
				$dataAgendaKegiatan['id_kegiatan']	= $idKegiatan;
				$dataAgendaKegiatan['tgl_ubah']	= $tglSekarang;
				$dataAgendaKegiatan['id_user']	= $_SESSION['iduser'];
			}
			require_once COMPONENT_PATH."/libraries/querybuilder.php";
			$querySimpan  = ($isEditing?"UPDATE":"INSERT INTO")." ra_agenda SET ";
			$querySimpan .= querybuilder_generate_set($dataAgendaKegiatan);
			if ($isEditing) $querySimpan .= " WHERE id_agenda=".$idAgenda;
			$qResult = $mysqli->query($querySimpan);
			$queryCount++;
			
			if ($qResult === true) {
				// Ambil semua array rincian
				if (!$isEditing && (!empty($_POST['ag-n-rincian']))) {
					$newIdAgenda = $mysqli->insert_id;
					if (is_array($_POST['ag-n-rincian'])) {
						// Generate query untuk insert rincian agenda
						$qRincian = "INSERT INTO ra_rincian_agenda (id_agenda, nama_rincian, jumlah_anggaran, tgl_tambah, id_user) ".
							"VALUES ";
						foreach ($agNamaRinc as $idx => $itemRincian) {
							$nilaiRincianAnggaran = intval($agNilaiRinc[$idx]);
							$qRincian .= sprintf("(%d,'%s',%d,'%s',%d),",
								$newIdAgenda,
								$mysqli->real_escape_string($itemRincian),
								$nilaiRincianAnggaran,
								$tglSekarang, $_SESSION['iduser']
							);
						}
						$qRincian = trim($qRincian, ","); // Hapus koma terakhir
						$qResRincian = $mysqli->query($qRincian);
						$queryCount++;
						if ($qResRincian === false) {
							$submitError[] = "Terjadi kesalahan internal saat memproses rincian. ".
								"Info: ".$mysqli->error;
							$mysqli->rollback();
						}
					}
				}
				if (empty($submitError)) {
					$qResult = $mysqli->commit();
					$submitInfo = "Agenda kegiatan <b>".htmlspecialchars($rowKegiatan['nama_kegiatan'])."</b> berhasil ".
						($isEditing?"diperbaharui.":"disimpan.");
					$showForm = false;
				}
			} else {
				$submitError[] = "Terjadi kesalahan internal. Info: ".$mysqli->error;
			}
		} // End if submit error
	} else { // Jika tidak ada data POST
		if ($isEditing) {
			$agPrioritas	= $rowAgenda['prioritas_agenda'];
			$agJenis		= $rowAgenda['jenis_agenda'];
			$agTglMulai		= $rowAgenda['tgl_mulai'];
			$agTglSelesai	= $rowAgenda['tgl_selesai'];
			$agCatatan		= $rowAgenda['catatan'];

			// Editing tidak menampilkan rincian
		} else { // Jika bukan edit (buat baru)
			$agPrioritas	= $rowKegiatan['prioritas']; // Default: sesuai kegiatan
			$agJenis		= 3; // Default: bulan
			$agTglMulai		= sprintf("%04d-%02d-01", $tahunDokumen, date("m"));
			$agTglSelesai	= sprintf("%04d-%02d-01", $tahunDokumen, date("m"));
			$agCatatan		= "";
			
			// Ambil rincian awal
			$queryGetRincian	= sprintf("SELECT * FROM ra_rincian_awal WHERE id_kegiatan=%d",$idKegiatan);
			$resultGetRincian	= mysqli_query($mysqli, $queryGetRincian);
			$queryCount++;
			while ($rowRincian = mysqli_fetch_assoc($resultGetRincian)) {
				$agNamaRinc[]	= $rowRincian['nama_rincian'];
				$agNilaiRinc[]	= $rowRincian['jumlah_anggaran'];
			}
		}
		$agSelectBulan	= date("m", strtotime($agTglMulai));
	}
	// Generate layout
	ra_print_status($namaDivisiUser);
	
	// Tampilkan notifikasi jika ada
	if ($submitInfo) {
		echo "<div class=\"alert alert-success\">\n";
		echo "<span class=\"glyphicon glyphicon glyphicon-ok-sign\"></span> ".$submitInfo."\n";
		echo "</div>\n";
	}
	
	$jumlahRincian 	= count($agNamaRinc); // Untuk ditampilkan di JavaScript
if ($showForm) { //============================== FORM DITAMPILKAN === ?>
<script>
var currentNewRowId = <?php echo count($agNamaRinc); ?>;
function update_input(selectorName, isEnabled) {
	if (isEnabled) {
		$(selectorName).removeAttr('disabled');
	} else {
		$(selectorName).attr('disabled','disabled');
	}
}
function update_input_jenis_agenda(idJenis) {
	update_input("#ag-select-bulan", (idJenis==1));
	update_input("#ag-1hari-tglmulai", (idJenis==2));
	update_input("#ag-range-tglmulai", (idJenis==3));
	update_input("#ag-range-tglselesai", (idJenis==3));
}
function tambah_rincian() {
	var newRowId = "siz_newrincian_"+currentNewRowId;
	var newRow = "<tr id=\""+newRowId+"\" style=\"display:none;\">";
	newRow += "<td><input class=\"siz-fullwidth\" type=\"text\" name=\"ag-n-rincian["+currentNewRowId+"]\" placeholder=\"Tulis nama rincian\" required/></td>";
	newRow += "<td><div class=\"input-group siz-input-anggaran\"><div class=\"input-group-addon\">Rp.</div>";
	newRow += "<input type=\"text\" name=\"ag-v-rincian["+currentNewRowId+"]\" placeholder=\"Tulis jumlah anggaran\" required/></div></td>";
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
function init_page() {
	$('#ag-1hari-tglmulai').datepicker({format: 'yyyy-mm-dd',autoclose:true,startDate:'<?php echo $minDate?>',endDate:'<?php echo $maxDate; ?>'});
	$('.input-daterange').datepicker({format: 'yyyy-mm-dd',autoclose:true,startDate:'<?php echo $minDate?>'});
	$('#siz_radio_bulan').on('ifChecked', function(event){
		update_input_jenis_agenda(1);
	});
	$('#siz_radio_1day').on('ifChecked', function(event){
		update_input_jenis_agenda(2);
	});
	$('#siz_radio_days').on('ifChecked', function(event){
		update_input_jenis_agenda(3);
	});
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
				echo "Edit Agenda Kegiatan";
			} else {
				echo "<span class=\"glyphicon glyphicon-plus\"></span>\n";
				echo "Tambah Agenda Kegiatan";
			}
			?></h3></div>
			<div class="panel-body">
				<fieldset>
					<legend>Kegiatan</legend>
					<table class="siz-table-detail">
						<tr>
							<td>Nama Kegiatan</td>
							<td><?php echo htmlspecialchars($rowKegiatan['nama_kegiatan']); ?></td>
						</tr>
						<tr>
							<td>Divisi</td>
							<td><?php echo $listDivisi[$rowKegiatan['divisi']]; ?></td>
						</tr>
						<tr>
							<td>Akun Pengeluaran</td>
							<td><?php
								
								echo "<a href=\"".htmlspecialchars("main.php?s=akun&action=detail&id=".$rowKegiatan['akun_pengeluaran'])."\">";
								echo $rowKegiatan['akun_pengeluaran']." ".$rowKegiatan['namaakun']."</a>";
							?></td>
						</tr>
					</table>
				</fieldset>
				<fieldset>
					<legend>Agenda</legend>
					<table class="siz-table-detail-input">
						<tr>
							<!-- ========== Agenda Sepanjang Bulan ============= -->
							<td><input type="radio" name="ag-jenis" value="3" id="siz_radio_bulan" 
								<?php if ($agJenis==3) echo "checked"; ?>/>
								<label for="siz_radio_bulan">Sepanjang Bulan</label></td>
							<td><select name="ag-select-bulan" id="ag-select-bulan" data-placeholder="- Pilih Bulan -"
									class="form-control" required <?php if ($agJenis!=3) echo "disabled=\"disabled\""; ?>>
								<option value="">- Pilih Bulan -</option>
							<?php
								for ($i=1;$i<=12;$i++) {
									echo "<option value=\"{$i}\" ";
									if ($agSelectBulan == $i) echo "selected";
									echo ">".$monthName[$i]."</option>\n";
								}
							?>
							</select></td>
						</tr>
						<tr>
							<!-- ========== Agenda 1 harian ============= -->
							<td><input type="radio" name="ag-jenis" value="1" id="siz_radio_1day"
								<?php if ($agJenis==1) echo "checked"; ?>/>
								<label for="siz_radio_1day">Satu hari</label></td>
							<td><input type="text" name="ag-1hari-tglmulai" id="ag-1hari-tglmulai"
								value="<?php echo $agTglMulai; ?>"
								<?php if ($agJenis!=1) echo "disabled=\"disabled\""; ?>
								  required class="form-control"/></td>
						</tr>
						<tr>
							<!-- ========== Agenda range hari ============= -->
							<td><input type="radio" name="ag-jenis" value="2" id="siz_radio_days"
								<?php if ($agJenis==2) echo "checked"; ?>/>
								<label for="siz_radio_days">Range hari</label></td>
							<td>
								<div class="input-group input-daterange">
								    <input type="text" class="input-xsmall" style="width:100%;" name="ag-range-tglmulai"
								    	 required id="ag-range-tglmulai" class="form-control"
								    	 <?php if ($agJenis!=2) echo "disabled=\"disabled\""; ?>
								    	  value="<?php echo $agTglMulai; ?>"/>
								    <span class="input-group-addon">sampai</span>
								    <input type="text" class="input-xsmall" style="width:100%;" name="ag-range-tglselesai"
								    	 required id="ag-range-tglselesai" class="form-control"
								    	 <?php if ($agJenis!=2) echo "disabled=\"disabled\""; ?>
								    	  value="<?php echo $agTglSelesai; ?>"/>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for="ag-prioritas">Prioritas Agenda</label></td>
							<td><select name="ag-prioritas" id="ag-prioritas" data-placeholder="- Pilih Prioritas -"
									class="form-control" required>
							<?php
								foreach ($listPrioritas as $idx => $itemPrioritas) {
									echo "<option value=\"{$idx}\" ";
									if ($agPrioritas == $idx) echo "selected";
									echo ">".$itemPrioritas."</option>\n";
								}
							?>
							</select></td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="ag-catatan">Keterangan Pelaksanaan Agenda:</label>
								<textarea class="siz-desc-container" id="ag-catatan"
									placeholder="Catatan Pelaksanaan Agenda" name="ag-catatan"><?php
									echo htmlspecialchars($agCatatan);
								?></textarea>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
		</div> <!-- End panel informasi kegiatan -->
	</div>
	<div class="col-lg-6">
	<?php if (!$isEditing) { //=========== Form rincian tidak ditampilkan saat editing === ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span>
					Rincian Anggaran Kegiatan</h3>
			</div>
			<div class="panel-body">
				<span class="glyphicon glyphicon-info-sign"></span>&nbsp;Tuliskan jumlah anggaran
					tanpa pemisah ribuan.
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Nama Rincian</th>
							<th style="width:160px;">Jumlah</th>
							<th style="width:75px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
	<?php
	if (!empty($agNamaRinc)) {
		$ctrIdRinc = 0;
		foreach ($agNamaRinc as $idx => $itemRincian) {
			$jumlahAnggaran = intval($agNilaiRinc[$idx]);
			$cRowId = "siz_newrincian_".$ctrIdRinc;
			echo "<tr id=\"".$cRowId."\">";
			echo "<td><input class=\"siz-fullwidth\" type=\"text\" name=\"ag-n-rincian[".$ctrIdRinc."]\" value=\"".
					htmlspecialchars($itemRincian)."\" placeholder=\"Tulis nama rincian\" required/></td>";
			echo "<td><div class=\"input-group siz-input-anggaran\"><div class=\"input-group-addon\">Rp.</div>";
   			echo "<input type=\"text\" name=\"ag-v-rincian[".$ctrIdRinc."]\" value=\"".
					$jumlahAnggaran."\" placeholder=\"Tulis jumlah anggaran\" required/></div></td>";
			echo "<td><a href=\"#\" onclick=\"return hapus_rincian(".$ctrIdRinc.");\" ";
			echo "class=\"btn btn-danger btn-xs\">Hapus</a></td></tr>\n";
			$ctrIdRinc++;
		}
	} // End if empty Rincian
	?>
						<tr id="siz_row_tambah_rincian">
							<td><a href="#" class="btn btn-xs btn-primary"
								onclick="return tambah_rincian();">
								<span class="glyphicon glyphicon-plus"></span>&nbsp;Tambah</a></td>
							<td>&nbsp;</td><td>&nbsp;</td>
						</tr>
					</tbody>
				</table>	
			</div>
		</div> <!-- End panel -->
	
	<?php } // ================= END isEditing ======================= ?>	
	</div><!-- End row content -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<input type="hidden" name="siz_submit" value="<?php echo "siz-".date("Ymd-His");?>" />
					<a href="<?php echo htmlspecialchars($backUrl); ?>">
						<span class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali</a> - 
					<button type="submit" class="btn btn-primary"><?php
						if ($isEditing) {
							echo "<span class=\"glyphicon glyphicon-pencil\"></span> Simpan Agenda Kegiatan\n";
						} else {
							echo "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Agenda Kegiatan\n";
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