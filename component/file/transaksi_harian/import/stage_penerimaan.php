<?php
/*
 * stage_penerimaan.php
 * ==> Tampilan tabel stage impor penerimaan
 *
 * AM_SIZ_STG_PENERIMAAN | Tampilan stage penerimaan
 * ------------------------------------------------------------------------
 */
	$SIZPageTitle = "Stage Penerimaan";

	require_once COMPONENT_PATH."/file/transaksi_harian/helper_transaksi.php";
	
	$queryGetStage =  sprintf(
			"SELECT s.*, a.namaakun, u.nama AS nama_mapdonatur, u.alamat AS alamat_mapdonatur ".
			"FROM stage_penerimaan AS s ".
			"LEFT JOIN akun AS a ON s.kode_akun=a.kode ".
			"LEFT JOIN user AS u ON s.id_donatur=u.id_user ".
			"ORDER BY tanggal");
	$resultGetStage = mysqli_query($mysqli, $queryGetStage);
	
	if (mysqli_num_rows($resultGetStage) == 0) {
		show_error_page("Stage kosong. Silakan impor dari Excel terlebih dahulu.");
		return;
	}
	$jmlNominal = 0;
	$jmlTrx = 0;
	$jmlTrxValid = 0;
	
	$unmappedAkun = 0;
	$unmappedAmilin = 0;
	$unmappedDonatur = 0;
	$unmappedBank = 0;
	//== Dimasukkan ke variabel dulu untuk dihitung...
	$dumpTrxPenerimaan = array();
	$dumpIdx = 0;
	while ($rowTransaction = mysqli_fetch_assoc($resultGetStage)) {
		$dumpTrxPenerimaan[] = $rowTransaction;
		$jmlNominal += $rowTransaction['jumlah'];
		$jmlTrx++;
		if (($rowTransaction['kode_akun'] != '0') &&
			($rowTransaction['id_donatur'] != 0) &&
			($rowTransaction['id_teller'] != 0) &&
			($rowTransaction['id_bank'] != -1)) {
				$jmlTrxValid++;
		} else {
			if ($rowTransaction['kode_akun'] == '0') $unmappedAkun++;
			if ($rowTransaction['id_donatur'] == 0) $unmappedDonatur++;
			if ($rowTransaction['id_teller'] == 0) $unmappedAmilin++;
			if ($rowTransaction['id_bank'] == -1) $unmappedBank++;
		}
	}
	$progressPercentage = round(($jmlTrxValid/$jmlTrx)*100, 2);
?>
<script>
var AJAX_URL = "main.php?s=ajax&m=transaksi";

// Fungsi templating untuk select2 donatur
function formatItemDonatur (elmtDonatur) {
  if (!elmtDonatur.id) { return elmtDonatur.text; }
  var userPrefix = "";
  if (elmtDonatur.type == 2) {
	  userPrefix = "[UKM]";
  }
  var elmtOutput = (
    '<div>' + userPrefix + " " + elmtDonatur.text + '</div>' +
    '<div style="font-size:0.8em;"><span class="glyphicon glyphicon-envelope"></span> ' + elmtDonatur.alamat + '</div>'
  );
  return $(elmtOutput);
};
function submit_trx_penerimaan(idStage) {
	var formFields = $("#siz_frm_stgpenerimaan_"+idStage+" form").serialize();

	_ajax_send("id="+idStage+"&"+formFields, function(data){
		if (data.status == "ok") {
			var rowElmt = $("#siz_check_item_"+idStage).closest("tr");
			$(rowElmt).html(data.html);
			// Reinit the iCheck plugin
			$(rowElmt).find('input[type=checkbox],input[type=radio]').iCheck({
		    	checkboxClass: 'icheckbox_flat-blue',
		    	radioClass: 'iradio_flat-blue'
			});
			close_form_trx_penerimaan(idStage);
		} else {
			alert(data.error);
		}
		
	}, "Memproses...", "main.php?s=ajax&m=transaksi");
	//alert(formFields);
	return false;
}
function close_form_trx_penerimaan(idStage) {
	$('#siz_frm_stgpenerimaan_'+idStage+' .editing-form-ctr').slideUp(250,function(){
		var rowElmt = $("#siz_check_item_"+idStage).closest("tr");
		$(rowElmt).show();
		$('#siz_frm_stgpenerimaan_'+idStage).remove();
	});
	return false;
}
function edit_trx_penerimaan(idStage) {
	var rowElmt = $("#siz_check_item_"+idStage).closest("tr");
	$.ajax({
		type: 'post',
		url: AJAX_URL,
		data: {
			act: 'get.stagepenerimaan.form',
			id: idStage
		},
		beforeSend: function( xhr ) {
			$(rowElmt).find('.control-box').hide();
			$(rowElmt).find('.loading-circle').show();
		},
		success: function(data){
			$(rowElmt).after(data);
			$(rowElmt).hide();

			//=== Init editing row ===
			$('#siz_frm_stgpenerimaan_'+idStage+' select.use_select2').select2({
				minimumResultsForSearch: 10,
			});
			$('#siz_frm_stgpenerimaan_'+idStage+' select#select2_muzakki').select2({
			    ajax: {
			        // The number of milliseconds to wait for the user to stop typing before
			        // issuing the ajax request.
			        type: 'post',
			        delay: 250,
			        dataType: 'json',
			        url: "main.php?s=ajax&m=user",
			        data: function (params) {
			          var queryParameters = {
			            q: params.term,
			            act: 'get.user.donatur'
			          };
			          return queryParameters;
			        },
			        // You can modify the results that are returned from the server, allowing you
			        // to make last-minute changes to the data, or find the correct part of the
			        // response to pass to Select2. Keep in mind that results should be passed as
			        // an array of objects.
			        //
			        // @param data The data as it is returned directly by jQuery.
			        // @returns An object containing the results data as well as any required
			        //   metadata that is used by plugins. The object should contain an array of
			        //   data objects as the `results` key.
			        processResults: function (data) {
			          return {
			            results: data
			          };
			        },
			      },
			      minimumInputLength: 3,
			      templateResult: formatItemDonatur
			});
			$('#siz_frm_stgpenerimaan_'+idStage+' .datepicker').datepicker({
				autoclose: true,
			});

			$('#siz_frm_stgpenerimaan_'+idStage+' .editing-form-ctr').slideDown(250);
		},
		error: function() {
			
		}
	}).always(function(){
		$(rowElmt).find('.control-box').show();
		$(rowElmt).find('.loading-circle').hide();
	});
	
	
	/*
	show_form_overlay("Test", "main.php?s=ajax&m=transaksi", {
		act: 'get.stagepenerimaan.form',
		id: idStage
	}, function(event) {
		var formFields = $("#siz_overlaydlg_form").serialize();
		alert(formFields);
		event.preventDefault();
	}, function() {
		// Init here...!
		$('#siz_overlaydlg_form select.use_select2').select2({
			minimumResultsForSearch: 10,
			dropdownParent: '#siz_overlaydlg_formctr'
		});
		$('#siz_overlaydlg_form select#select2_muzakki').select2({
			dropdownParent: '#siz_overlaydlg_formctr',
		    ajax: {
		        // The number of milliseconds to wait for the user to stop typing before
		        // issuing the ajax request.
		        type: 'post',
		        delay: 250,
		        dataType: 'json',
		        url: "main.php?s=ajax&m=user",
		        data: function (params) {
		          var queryParameters = {
		            q: params.term,
		            act: 'get.user.donatur'
		          };
		          return queryParameters;
		        },
		        // You can modify the results that are returned from the server, allowing you
		        // to make last-minute changes to the data, or find the correct part of the
		        // response to pass to Select2. Keep in mind that results should be passed as
		        // an array of objects.
		        //
		        // @param data The data as it is returned directly by jQuery.
		        // @returns An object containing the results data as well as any required
		        //   metadata that is used by plugins. The object should contain an array of
		        //   data objects as the `results` key.
		        processResults: function (data) {
		          return {
		            results: data
		          };
		        },
		      },
		      minimumInputLength: 3,
		      templateResult: formatItemDonatur
		});
		$('#siz_overlaydlg_form .datepicker').datepicker({
			autoclose: true,
			container: '#siz_overlaydlg_form'
		});
	});
	*/
	//$('#siz_overlaydlg_body').html("Showing form stage #"+idStage);
	
	return false;
}
function hapus_trx_penerimaan(elmt, idStage) {
	var userConfirm = confirm("Hapus transaksi yang Anda pilih?");
	if (!userConfirm) return false;
	// On success:
	var domElmt = $(elmt).closest("tr");
	$(domElmt).fadeOut(250,function(){$(this).remove();});
	return false;
}
</script>
<div class="col-md-12">
	<?php import_submodule_printinfo(); ?>
	<fieldset>
		<legend>Mapping</legend>
		<div class="btn-group" role="group">
			<a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=akun" class="btn btn-default btn-lg">
				<span class="glyphicon glyphicon-transfer"></span>&nbsp;Map Akad transaksi
					<?php if ($unmappedAkun > 0) echo "<span class=\"badge\">{$unmappedAkun}</span>"; ?></a>
			<a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=donatur" class="btn btn-default btn-lg">
				<span class="glyphicon glyphicon-transfer"></span>&nbsp;Map muzakki/donatur
					<?php if ($unmappedDonatur > 0) echo "<span class=\"badge\">{$unmappedDonatur}</span>"; ?></a>
			<a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=amilin" class="btn btn-default btn-lg">
				<span class="glyphicon glyphicon-transfer"></span>&nbsp;Map amilin
					<?php if ($unmappedAmilin > 0) echo "<span class=\"badge\">{$unmappedAmilin}</span>"; ?></a>
			<a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=bank" class="btn btn-default btn-lg">
				<span class="glyphicon glyphicon-transfer"></span>&nbsp;Map bank
					<?php if ($unmappedBank > 0) echo "<span class=\"badge\">{$unmappedBank}</span>"; ?></a>
		</div>
		
	</fieldset>
	<p>Stage valid : <?php
		echo sprintf("<b>%d</b> dari <b>%d</b> transaksi.", $jmlTrxValid, $jmlTrx);?>
	| <a href="main.php?s=transaksi&amp;action=import&amp;proc=load&amp;do=penerimaan"
		class="btn btn-success <?php if ($jmlTrxValid == 0) echo "disabled"; ?>">
		<span class="glyphicon glyphicon-ok"></span>&nbsp;Masukkan ke transaksi.</a></p>
	<div class="progress">
	  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
	  	aria-valuenow="<?php echo $jmlTrxValid; ?>" aria-valuemin="0"
	  	aria-valuemax="<?php echo $jmlTrx; ?>" style="width: <?php echo $progressPercentage; ?>%">
	    <span class="sr-only"><?php echo $progressPercentage;?>% Complete (success)</span>
	  </div>
	</div>
	<table class="table table-bordered table-striped table-hover siz-operation-table">
		<thead>
			<tr>
				<th><input type='checkbox' name='siz_checkall'/></th>
				<th>Tanggal</th>
				<th>No. Nota</th>
				<th>Akad/Akun Penerimaan
					<div><a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=akun">Map</a></div></th>
				<th style='min-width:140px;'>Nominal</th>
				<th>Amilin
					<div><a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=amilin">Map</a></div></th>
				<th>Donatur
					<div><a href="main.php?s=transaksi&amp;action=import&amp;proc=mapping-penerimaan&amp;col=donatur">Map</a></div></th>
				<th>Keterangan</th>
				<th style='min-width: 100px;'>Aksi</th>
			</tr>
		</thead>
		<tbody>
<?php 
	foreach ($dumpTrxPenerimaan as $rowTransaction) {
		$rowHtml = getHTMLRowTrxPenerimaan($rowTransaction);
		echo "<tr>".$rowHtml."</tr>\n";
	}
?>
		</tbody>
		<tfoot>
			<tr><td colspan='4'>&Sigma; Jumlah Penerimaan</td>
			<td><b><?php echo to_rupiah($jmlNominal)?></b></td>
			<td colspan='4'>&Sigma; Jumlah Transaksi: <b><?php echo $jmlTrx; ?></b></td>
			</tr>
		</tfoot>
	</table>
</div>


