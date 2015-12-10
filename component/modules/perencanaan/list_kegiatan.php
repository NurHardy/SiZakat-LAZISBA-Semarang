<?php
/*
 * list_kegiatan.php
 * ==> Halaman list master kegiatan
 *
 * AM_SIZ_RA_LISTKGT | List master Kegiatan
 * ------------------------------------------------------------------------
 */

	$SIZPageTitle = "List Master Kegiatan";
	$breadCrumbPath[] = array("Master Kegiatan",ra_gen_url("list"),true);

	// Cek privilege
	if (!ra_check_privilege()) exit;

	// List seluruh kegiatan yang diadakan oleh divisi
	
	// Init
	$idDivisi = intval($_SESSION['siz_divisi']);
	$isAdmin = ($idDivisi == RA_ID_ADMIN);
	$listMasterKegiatan = array();
	
	// Per divisi...
	foreach ($listValidDivisi as $idDivisi) {
		// Query list kegiatan master
		$queryListKegiatan = sprintf(
				"SELECT k.*, (".
				"SELECT SUM(jumlah_anggaran) FROM ra_rincian_awal AS a WHERE k.id_kegiatan=a.id_kegiatan".
				") AS jml_anggaran, n.idakun, n.namaakun ".
				"FROM ra_kegiatan AS k ".
				"LEFT JOIN akun AS n ON k.akun_pengeluaran=n.kode ".
				"WHERE k.divisi=".$idDivisi);
		$listResult = $mysqli->query($queryListKegiatan);
		$queryCount++;
		if ($listResult == null) {
			show_error_page( "Terjadi kesalahan internal: ".mysqli_error($mysqli) );
			return;
		}
		
		//== Simpan ke temporary array
		$listMasterKegiatan[$idDivisi] = array();
		while ($rowKegiatan = $listResult->fetch_array(MYSQLI_ASSOC)) {
			$listMasterKegiatan[$idDivisi][] = $rowKegiatan;
		}
	}
	
?>
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.min.js"></script>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
function hapus_master_kegiatan(idMastKegiatan) {
	var uResp = confirm("Hapus master kegiatan?");
	if (!uResp) return false;

	_ajax_send({
		act: "masterkgt.quickdelete",
		id: idMastKegiatan
	}, function(response){
		if (response.status=='ok') {
			$.gritter.add({
				title: 'Berhasil Dihapus',
				text: "Master kegiatan terpilih berhasil dihapus."
			});
			$("#siz_tkegiatan_"+idMastKegiatan).fadeOut(250,function(){
				$(this).remove();
			});
		} else if (response.status=='need-confirm') {
			window.location = response.url;
		} else {
			alert("Terjadi kesalahan: "+response.error);
		}
	}, "Sedang memproses...", AJAX_URL);
	return false;
}
</script>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-list"></i>									
			</span>
			<h5>List Kegiatan Master</h5>
		</div>
		<div class="widget-content">
			<p>Di sini Anda bisa melihat seluruh program kerja atau kegiatan yang dapat diagendakan
				dalam perencanaan.</p>
<?php foreach ($listValidDivisi as $ctrDivisi) { //---------------------- ?>
			<hr>
			<h4><span class="glyphicon glyphicon-triangle-right"></span>
				Divisi <?php echo $listDivisi[$ctrDivisi]; ?></h4>
			<table class="table table-bordered table-striped table-hover siz-operation-table">
				<thead>
				<tr>
					<th>Nama Kegiatan</th>
					<th style="width:75px">Prioritas</th>
					<th style="width:150px">Anggaran Awal</th>
					<th style="width:260px">Akun Pengeluaran</th>
					<th style="width:120px">Sifat</th>
					<th style="width:150px">Aksi</th>
				</tr>
				</thead>
				<tbody>
			<?php
			$currentDivisi = $ctrDivisi;
			foreach ($listMasterKegiatan[$ctrDivisi] as $rowKegiatan) {
				$idKegiatan = $rowKegiatan['id_kegiatan'];
				$linkKegiatan = ra_gen_url("master-kegiatan",null,"id=".$idKegiatan);
				echo "<tr id=\"siz_tkegiatan_{$idKegiatan}\">\n";
				echo "	<td><a href=\"".htmlspecialchars($linkKegiatan)."\">";
				echo htmlspecialchars($rowKegiatan['nama_kegiatan'])."</a></td>\n";
				echo "<td class=\"td-prioritas\">".$listPrioritasHTML[$rowKegiatan['prioritas']]."</td>\n";
				echo "	<td>".to_rupiah($rowKegiatan['jml_anggaran'])."</td>\n";
				echo "	<td><a href=\"".htmlspecialchars("main.php?s=akun&action=detail&id=".$rowKegiatan['idakun'])."\" target=\"_blank\">";
				echo $rowKegiatan['akun_pengeluaran']." ".$rowKegiatan['namaakun']."</a></td>\n";
				echo "	<td>".($rowKegiatan['jenis_kegiatan']==1?"Rutin Tahunan":"Eksklusif")."</td>\n";
				echo "	<td><a href=\"".htmlspecialchars($linkKegiatan)."\"><span class=\"glyphicon glyphicon-search\"></span> Detil</a>";
				echo "	- <a href=\"#hapus\" onclick=\"return hapus_master_kegiatan(".$idKegiatan.");\" class=\"red_link\"><span class=\"glyphicon glyphicon-trash\"></span> Hapus</a></td>\n";
				echo "</tr>\n";
			}
			?>
				</tbody>
			</table>
	<?php if ($isAdmin || ($idDivisi == $ctrDivisi)) {
			//------- Jika Admin atau divisi yang bersangkutan --------- ?>
			<a href="<?php echo htmlspecialchars(ra_gen_url("tambah-kegiatan-master",null,"div=".$ctrDivisi)); ?>"
				class="btn btn-primary btn-sm tip-right" title="Buat master kegiatan baru">
				<span class="glyphicon glyphicon-plus"></span> Tambah Master Kegiatan
			</a>
	<?php } //--------- END IF --------------- ?>
<?php } //------------- END FOREACH ------------------------------- ?>
		</div>
	</div><!-- End widget-box-->
</div><!-- End col-12 -->

<link rel="stylesheet" href="css/jquery.gritter.css" />
<script src="js/jquery.gritter.min.js"></script>