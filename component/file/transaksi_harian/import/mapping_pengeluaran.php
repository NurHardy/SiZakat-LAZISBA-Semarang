<?php
/*
 * mapping_pengeluaran.php
 * ==> Tampilan untuk mengatur memapping akun, penerima, penanggung-jawab, bank, dsb.
 *
 * AM_SIZ_MAP_PENGELUARAN | Tampilan mapping pengeluaran
 * ------------------------------------------------------------------------
 */

$colName = $_GET ['col'];

$tblSourceLabel = "Label pada Excel";
$tblDestLabel = "Label Tujuan";

if ($colName == 'akun') {
	$currentColumn = "akun";
	$ajaxSuggestUrl = "main.php?s=ajax&m=akun";
	$ajaxSuggestAct = "get.akun.pengeluaran";
	$templateFuncName = "formatItemAkun";
	
	$queryGetMapSource =
		"SELECT ket_akun, NULL AS meta_info, COUNT(ket_akun) AS jumlah FROM stage_pengeluaran " .
		"WHERE kode_akun='0' GROUP BY ket_akun ";
	
	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else if ($colName == 'penerima') {
	$currentColumn = "penerima";
	$ajaxSuggestUrl = "main.php?s=ajax&m=user";
	$ajaxSuggestAct = "get.user.donatur";
	$templateFuncName = "formatItemDonatur";
	
	$queryGetMapSource =
		"SELECT nama_penerima AS ket_akun, NULL AS meta_info, COUNT(nama_penerima) AS jumlah FROM stage_pengeluaran " .
		"WHERE id_penerima='0' AND nama_penerima<>'' GROUP BY nama_penerima ";
	
	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else if ($colName == 'pj') {
	$currentColumn = "pj";
	$ajaxSuggestUrl = "main.php?s=ajax&m=user";
	$ajaxSuggestAct = "get.user.amilin";
	$templateFuncName = "formatItemDonatur";

	$queryGetMapSource =
	"SELECT nama_pj AS ket_akun, NULL AS meta_info, COUNT(nama_pj) AS jumlah FROM stage_pengeluaran " .
	"WHERE id_pj='0' AND nama_pj<>'' GROUP BY nama_pj ";

	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else if ($colName == 'bank') {
	$currentColumn = "bank";
	$ajaxSuggestUrl = "main.php?s=ajax&m=transaksi";
	$ajaxSuggestAct = "get.bank.suggest";
	$templateFuncName = "formatItemBank";

	$queryGetMapSource =
		"SELECT nama_bank AS ket_akun, NULL AS meta_info, COUNT(nama_bank) AS jumlah FROM stage_pengeluaran " .
		"WHERE (id_bank=-1) AND (nama_bank <> '') GROUP BY nama_bank ";

	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else {
	show_error_page("Invalid argument.");
	return;
}

?>
<script src="js/sizakat/select2_formatter.js"></script>
<script>
var AJAX_URL = "main.php?s=ajax&m=transaksi";
var ACTIVE_COLUMN = "<?php echo $currentColumn; ?>";
var AJAX_SUGGEST_URL = "<?php echo $ajaxSuggestUrl; ?>";
var AJAX_SUGGEST_ACT = "<?php echo $ajaxSuggestAct; ?>";

function init_page() {
	$('select.select2_akun').select2({
	    ajax: {
	        // The number of milliseconds to wait for the user to stop typing before
	        // issuing the ajax request.
	        type: 'post',
	        delay: 250,
	        dataType: 'json',
	        url: AJAX_SUGGEST_URL,
	        data: function (params) {
	          var queryParameters = {
	            q: params.term,
	            act: AJAX_SUGGEST_ACT
	          };
	          return queryParameters;
	        },
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	      },
	      minimumInputLength: 3,
	      templateResult: <?php echo $templateFuncName; ?>
	});
}

function submit_map_pengeluaran(mapId) {
	var formFields = $("form#mapper_id_"+mapId).serialize();

	_ajax_send("act=map.stagepengeluaran&col="+ACTIVE_COLUMN+"&"+formFields, function(data){
		var rowElmt = $("form#mapper_id_"+mapId).closest("td");
		$(rowElmt).html("<img src='images/icons/icon_check_16.png' alt='ok-' />&nbsp;"+data.message);
	}, "Memproses...", AJAX_URL);
	return false;
}
</script>

<div class="col-md-8">
	<?php import_submodule_printinfo(); ?>
	<div class="well well-sm">
		<a href="javascript:history.back();">
			<span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
	</div>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon"> <i class="glyphicon glyphicon-log-in"></i>
			</span>
			<h5>Mapping Jenis Akun Pengeluaran</h5>
		</div>
		<div class="widget-content">
<?php if (mysqli_num_rows($resultGetMapSource) > 0) { //=== Jika ada yang dimapping.. == ?>
			<table
				class="table table-bordered table-striped table-hover siz-operation-table">
				<thead>
					<tr>
						<th><?php echo $tblSourceLabel; ?></th>
						<th><?php echo $tblDestLabel; ?></th>
						<th>Jumlah Transaksi</th>
					</tr>
				</thead>
				<tbody>
<?php
$jmlTrx = 0;
$mapId = 1;
while ( $rowMapSource = mysqli_fetch_assoc ( $resultGetMapSource ) ) {
	echo "
		<tr>
			<td>" . htmlspecialchars ( $rowMapSource ['ket_akun'] ) . 
			"<div class='siz_import_map_metainfo'>".$rowMapSource ['meta_info']."</div>". "</td>		
			<td style='width:300px;'><div>";
	echo "<form action=\"#save\" method=\"post\" id=\"mapper_id_".$mapId."\"
				onsubmit=\"return submit_map_pengeluaran(".$mapId.");\">\n";
	echo "<input type=\"hidden\" name=\"map_source\" value=\"".htmlspecialchars($rowMapSource ['ket_akun'])."\" />";
	echo "<select name='map_dest' class=\"select2_akun\" style='width: 100%;'
				data-placeholder=\"-- Pilih --\" required>";
	echo "</select>\n";
	echo "<div><button type='submit' class='btn btn-primary btn-xs'>
			<span class='glyphicon glyphicon-ok'></span>&nbsp;Apply</button></div>";
	echo "</div></form></td>
			<td>" . $rowMapSource ['jumlah'] . "</td>
		</tr>
		";
	$jmlTrx+= $rowMapSource ['jumlah'];
	$mapId++;
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2'>Jumlah Transaksi:</td>
						<td><b><?php echo $jmlTrx; ?></b></td>
					</tr>
				</tfoot>
			</table>
<?php } else { // ==== Jika tidak ada yang dimapping.. ========== ?>
	<div class="alert alert-success">
		<span class="glyphicon glyphicon-ok"></span>&nbsp;Semua nilai pada kolom sudah dimapping.
	</div>
<?php } // =========== End if ========= ?>
			<div>
				<a href="javascript:history.back();">
					<span class="glyphicon glyphicon-chevron-left"></span> Kembali</a>
			</div>
		</div>
	</div>
</div>
