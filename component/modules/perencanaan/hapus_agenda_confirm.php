<?php
/*
 * hapus_agenda_confirm.php
 * ==> Halaman konfirmasi hapus agenda
 *
 * AM_SIZ_RA_CONFHPSAGENDA | Konfirmasi hapus Agenda
 * ------------------------------------------------------------------------
 */

	// Cek privilege
	if (!ra_check_privilege(RA_ID_ADMIN)) exit;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == RA_ID_ADMIN);
	$backUrl		= "javascript:backAway();";
	$tahunDokumen	= $_GET['th'];
	
	$detachFromDocument = false;
	
	$listAgenda = array();
	
	require_once COMPONENT_PATH."/libraries/querybuilder.php";
	
	// Dapatkan list id-agenda yang akan dihapus
	$listIdAgenda = (isset($_POST['id'])?$_POST['id']:$_GET['id']);
	
	// Jika masukan adalah id kegiatan...
	$idKegiatan = $_GET['idk'];
	$rowKegiatan	= null;
	if ($idKegiatan) {
		// Menghapus ID kegiatan berarti menghapus kegiatan dari dokumen
		$detachFromDocument = true;
		
		$queryCekKegiatan = sprintf("SELECT * FROM ra_kegiatan WHERE id_kegiatan=%d", $idKegiatan);
		$resultKegiatan = mysqli_query($mysqli, $queryCekKegiatan);
		$rowKegiatan = mysqli_fetch_assoc($resultKegiatan);
		
		if ($rowKegiatan) {
			$listIdAgenda = array();
			$queryListAgenda = sprintf(
					"SELECT * FROM ra_agenda WHERE id_kegiatan=%d AND YEAR(tgl_mulai)=%d",
					$idKegiatan, $tahunDokumen);
			$resultListAgenda = mysqli_query($mysqli, $queryListAgenda);
			while ($rowAgenda = mysqli_fetch_assoc($resultListAgenda)) {
				$listIdAgenda[] = $rowAgenda['id_agenda'];
			}
		} else {
			show_error_page( "Data master kegiatan tidak ditemukan." );
			return;
		}
		
	}
	
	if (!is_array($listIdAgenda)) {
		if (is_numeric($listIdAgenda)) {
			$listIdAgenda = array($listIdAgenda);
		} else {
			$listIdAgenda = array();
		}
	}
	if (!check_array_id($listIdAgenda)) {
		show_error_page( "Format argumen tidak valid." );
		return;
	}
	if (empty($listIdAgenda) && empty($rowKegiatan)) {
		show_error_page( "Argumen tidak lengkap." );
		return;
	}
	
	if (!empty($listIdAgenda)) {
		$listIdAgendaQuery = implode(',', $listIdAgenda);
		$listIdAgendaQuery = trim($listIdAgendaQuery, ','); // Hapus koma terakhir
		
		$queryGetAgenda = sprintf(
				"SELECT a.*, k.nama_kegiatan, k.divisi FROM ra_agenda AS a, ra_kegiatan AS k ".
				"WHERE k.id_kegiatan=a.id_kegiatan AND (a.id_agenda IN (%s))",
				$listIdAgendaQuery);
		$resultGetAgenda = mysqli_query($mysqli, $queryGetAgenda);
		if ($resultGetAgenda == null) {
			show_error_page( "Terjadi kesalahan internal: ".mysqli_error($mysqli) );
			return;
		}
		
		// Pengecekan tiap item agenda
		$idx = 0;
		$isAuthorized = true;
		while ($rowAgenda = mysqli_fetch_array($resultGetAgenda)) {
			if (!$isAdmin) {
				if ($rowAgenda['divisi'] != $divisiUser) {
					$isAuthorized = false;
					break;
				}
			}
			$listAgenda[$idx] = $rowAgenda;
			$idx++;
		}
		if (!$isAuthorized) {
			show_error_page( "Terdapat item agenda yang tidak berhak Anda hapus." );
			return;
		}
	}
?>
<script>
var AJAX_URL = "<?php echo RA_AJAX_URL; ?>";
var itemLeft = <?php echo count($listAgenda); ?>;
function show_error(errorText) {
	$("#siz-alert-infos").hide();
	$("#siz-alert-errors").hide().html(errorText).fadeIn(250);
}
function show_info(infoText) {
	$("#siz-alert-errors").hide();
	$("#siz-alert-infos").hide().html(infoText).fadeIn(250);
}
function submit_delete() {
	var serializedForm = $("#siz-form-chk-hpsagenda").serialize();
	_ajax_send(
		serializedForm
	, function(response){
		if (response.status=='ok') {
			itemLeft -= response.item_deleted;
			show_info("<span class='glyphicon glyphicon-ok'></span> <b>"+response.item_deleted+"</b> agenda berhasil dihapus.");
			if (itemLeft <= 0) {
				$("#siz-alert-warning").fadeOut(250);
				$("#siz-list-select").fadeOut(250,function(){$(this).remove();});
			} else {
				delete_selected();
			}
			$("#siz-btn-submit").prop('disabled','disabled');
		} else {
			show_error("<b>Terjadi kesalahan</b>: "+response.error);
		}
	}, "Sedang memproses...", AJAX_URL);
	return false;
}
function check_checks() {
	var checkedChecboxes = $('#siz-list-select tr td:first-child input[type=checkbox]:checked');
	if (checkedChecboxes.length == 0) {
		$("#siz-btn-submit").prop('disabled','disabled');
	} else {
		$("#siz-btn-submit").removeAttr('disabled');
	}
}
function on_check_all() {
	var checkbox = $(this).parents('#siz-list-select').find('tr td:first-child input:checkbox');
	var checkStatus = this.checked;
	checkbox.each(function() {
		if (checkStatus) {
			$(this).iCheck('check');
		} else {
			$(this).iCheck('uncheck');
		}
	});
}
function on_check() {
	if (this.checked) {
		$(this).closest('tr').addClass('siz-row-highlight-fordelete');
	} else {
		$(this).closest('tr').removeClass('siz-row-highlight-fordelete');
	}
	check_checks();
}
function init_page() {
	$("th input:checkbox").on('ifChecked || ifUnchecked',on_check_all);
	$("td input:checkbox").on('ifChecked || ifUnchecked',on_check);
}
function delete_selected() {
	var checkbox = $("#siz-list-select").find('tr td:first-child input[type=checkbox]:checked');
	checkbox.each(function() {
		$(this).closest('tr').fadeOut(250,function(){
			$(this).remove();
		});
	});
}
function backAway(){
    //if it was the first page
    if(history.length === 1){
        window.location = "main.php?s=perencanaan";
    } else {
        history.back();
    }
}
</script>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
	<form action="#" method="post" onsubmit="return submit_delete();" id="siz-form-chk-hpsagenda">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-list"></i>									
			</span>
			<h5>Hapus Agenda</h5>
		</div>
		<div class="widget-content">
			<div class="alert alert-warning alert-icon alert-icon-warning" id="siz-alert-warning">
				<b>Anda akan menghapus agenda berikut.</b><br>
				Jika Anda yakin dan mengerti apa yang Anda dilakukan, klik 'Hapus Agenda'.
				Data yang telah dihapus tidak dapat dikembalikan.
			</div>
			<div class="alert alert-danger" id="siz-alert-errors" style="display:none;"></div>
			<div class="alert alert-success" id="siz-alert-infos" style="display:none;"></div>
<?php if (!empty($listAgenda)) { //--------- IF not EMPTY ---------------- ?>
			<table class="table table-bordered table-striped table-hover"
				id="siz-list-select">
				<thead>
				<tr>
					<th>Kegiatan</th>
					<th>Pelaksanaan</th>
					<th>Anggaran</th>
				</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($listAgenda as $itemAgenda) {
						$idAgenda = $itemAgenda['id_agenda'];
						$tglMulai = tanggal_indonesia(date('d M Y', strtotime($itemAgenda['tgl_mulai'])));

						echo "<tr>";
						echo "<td>".htmlspecialchars($itemAgenda['nama_kegiatan'])."\n";
						echo "<input type='hidden' name='id[]' value='".$idAgenda."' /></td>";
						echo "<td>".$tglMulai."</td>";
						echo "<td>".to_rupiah($itemAgenda['jumlah_anggaran'])."</td>";
						echo "</tr>\n";
					}
					?>
				</tbody>
			</table>
<?php } //------------ END IF ------------------------- ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<input type="hidden" name="siz_submit" value="<?php echo "siz-".date("Ymd-His");?>" />
					<a href="<?php echo htmlspecialchars($backUrl); ?>">
						<span class="glyphicon glyphicon glyphicon-chevron-left"></span> Kembali</a> - 
					<button type="submit" class="btn btn-danger" id="siz-btn-submit">
						<span class="glyphicon glyphicon-trash"></span> Hapus Agenda</button>
				</div>
			</div>
		</div>
	</div>
	<?php if ($detachFromDocument) { //--------- ?>
		<input type="hidden" name="idk" value="<?php echo htmlspecialchars($idKegiatan); ?>" />
		<input type="hidden" name="th" value="<?php echo htmlspecialchars($tahunDokumen); ?>" />
	<?php } //------------------------ END IF -- ?>
		<input type="hidden" name="act" value="agenda.hapus" />
	</form>
	</div>
	</div>
</div>