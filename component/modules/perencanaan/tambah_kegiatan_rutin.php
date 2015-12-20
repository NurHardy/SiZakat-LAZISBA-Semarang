<?php
/*
 * tambah_kegiatan_rutin.php
 * ==> Menambah kegiatan rutin ke dalam dokumen perencanaan
 *
 * AM_SIZ_RA_ADDKGTRUTIN | Form tambah kegiatan rutin
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege()) return;
	
	// Init
	$divisiUser = intval($_SESSION['siz_divisi']);
	$isAdmin = ($divisiUser == RA_ID_ADMIN);
	
	$formActionUrl = $_SERVER['REQUEST_URI']; // Aksi ke script ini lagi.
	$backUrl = ra_gen_url("home");
	
	$submitError	= array();
	
	$showForm		= true;
	$tahunDokumen	= -1;
	$isEditing		= (intval($_GET['th']));
	$rowDokumen		= null;
	
	$tahunDokumen = intval($_GET['th']);
	$rowDokumen = ra_cek_dokumen($tahunDokumen);
	
	$backUrl = ra_gen_url("rekap",$tahunDokumen);
	$SIZPageTitle = "Tambah Kegiatan Rutin";
	$breadCrumbPath[] = array("Tahun ".$tahunDokumen,ra_gen_url("rekap",$tahunDokumen),false);
	$breadCrumbPath[] = array("Tambah Kegiatan",null,true);
	
	if (!$rowDokumen) return; // Pesan error sudah dihandle oleh ra_cek_dokumen
	
	//====== LIST kegiatan rutin yang belum ditambahkan ke dokumen perencanaan
	$divRestrict = (!$isAdmin ? "AND divisi=".$divisiUser : "");
	$queryList = sprintf(
		"SELECT k.id_kegiatan AS idk , k.* FROM ra_kegiatan AS k ".
		"LEFT JOIN ra_catatan_kegiatan AS c ".
		"ON c.id_kegiatan=k.id_kegiatan AND c.tahun=%d ".
		"WHERE k.jenis_kegiatan=1 AND c.id_kegiatan IS NULL %s",
		$tahunDokumen, $divRestrict
	);
	$resultList = mysqli_query($mysqli, $queryList);
	if (!$resultList) {
		show_error_page("Terjadi kesalahan internal: ".mysqli_error($mysqli));
		return;
	}
	
	if (isset($_POST['siz_submit'])) {
		// Validasi
		require_once COMPONENT_PATH."/libraries/querybuilder.php";
		
		$listIdKegiatan = $_POST['idk'];
		$listKegiatan = array();
		
		if (!is_array($listIdKegiatan)) {
			if (is_numeric($listIdKegiatan)) {
				$listIdKegiatan = array($listIdKegiatan);
			} else {
				$listIdKegiatan = array();
			}
		}
		if (!check_array_id($listIdKegiatan)) {
			$submitError[] = "Format input tidak valid!";
		}
		if (empty($listIdKegiatan)) {
			$submitError[] = "Tidak ada kegiatan yang diinputkan.";
		} else {
			// Terdapat agenda yang dihapus...
			$listIdKegiatanQuery = implode(',', $listIdKegiatan);
			$listIdKegiatanQuery = trim($listIdKegiatanQuery, ','); // Hapus koma terakhir
		
			$queryGetKegiatan = sprintf(
					"SELECT k.id_kegiatan, k.nama_kegiatan, k.divisi FROM ra_kegiatan AS k ".
					"WHERE k.id_kegiatan IN (%s)",
					$listIdKegiatanQuery);
			$resultGetKegiatan = mysqli_query($mysqli, $queryGetKegiatan);
			if ($resultGetKegiatan == null) {
				$submitError[] =  "Terjadi kesalahan internal: ".mysqli_error($mysqli);
			}
		
			if (empty($submitError)) {
				// Pengecekan tiap item kegiatan
				$idx = 0;
				$isAuthorized = true;
				while ($rowKegiatan = mysqli_fetch_array($resultGetKegiatan)) {
					if (!$isAdmin) {
						if ($rowKegiatan['divisi'] != $divisiUser) {
							$isAuthorized = false;
							break;
						}
					}
					$listKegiatan[$idx] = $rowKegiatan;
					$idx++;
				}
				if (!$isAuthorized) {
					$submitError[] = "Anda mempunyai otorisasi untuk menambahkan kegiatan.";
				}
			} // End if
			
		} // End else if empty($listIdKegiatan)
		
		if (empty($submitError)) {
			// Bangun dan laksanakan query jika tidak ada error
			$runSuccess = true;
			$jmlKegiatan = 0;
			mysqli_autocommit($mysqli, false);
			
			foreach ($listKegiatan as $rowKegiatan) {
				$tglSekarang = date("Y-m-d H:i:s");
				$queryInsert = sprintf(
						"INSERT IGNORE INTO ra_catatan_kegiatan SET id_kegiatan=%d, ".
						"tahun=%d, tgl_tambah='%s'",
						$rowKegiatan['id_kegiatan'], $tahunDokumen, $tglSekarang
				);
				$qResult = mysqli_query($mysqli, $queryInsert);
				if ($qResult != true) {
					$runSuccess = false;
					$submitError[] = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
					break;
				}
				$jmlKegiatan++;
			}
			
			if ($runSuccess) {
				mysqli_commit($mysqli);
				$submitInfo = "{$jmlKegiatan} kegiatan berhasil ditambahkan ke dokumen.";
				$showForm = false;
			} else {
				mysqli_rollback($mysqli);
			}
		}
	} else {
		if (mysqli_num_rows($resultList) == 0) {
			$showForm = false;
			$submitInfo = "Semua kegiatan rutin telah ditambahkan ke dokumen.";
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
<script>
function setcheck_all(checkStatus) {
	var checkbox = $('#siz-list-select').find('tr td:first-child input:checkbox');
	checkbox.each(function() {
		if (checkStatus) {
			$(this).iCheck('check');
		} else {
			$(this).iCheck('uncheck');
		}
	});
	return false;
}
</script>
<form action="<?php echo htmlspecialchars($formActionUrl); ?>" method="POST">
	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php
				echo "<span class=\"glyphicon glyphicon-plus\"></span>\n";
				echo "Tambah Kegiatan Rutin";
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
				<a href="#checkall" onclick="return setcheck_all(true);">
					<span class="glyphicon glyphicon-ok"></span> Pilih Semua
				</a> |
				<a href="#uncheckall" onclick="return setcheck_all(false);">
					<span class="glyphicon glyphicon-unchecked"></span> Kosongkan
				</a>
				<table class="table table-bordered table-hover siz-operation-table"
					id="siz-list-select">
					<thead>
						<tr><th style="width:48px;">Cek</th><th>Kegiatan</th></tr>
					</thead>
					<tbody>
<?php
	while ($rowKegiatan = mysqli_fetch_assoc($resultList)) {
		$idKgt = $rowKegiatan['idk'];
		echo "<tr><td><input type='checkbox' name='idk[]' value='{$idKgt}' id='siz-idk-{$idKgt}' /></td>";
		echo "<td><label for='siz-idk-{$idKgt}'>".htmlspecialchars($rowKegiatan['nama_kegiatan'])."</label>";
		echo " (Divisi {$listDivisi[$rowKegiatan['divisi']]})</td></tr>\n";
	}
?>
					</tbody>
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
						echo "<span class=\"glyphicon glyphicon-plus\"></span> Tambah Kegiatan Terpilih\n";
					?></button>
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