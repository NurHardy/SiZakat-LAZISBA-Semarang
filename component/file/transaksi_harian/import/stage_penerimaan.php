<?php
/*
 * stage_penerimaan.php
 * ==> Tampilan tabel stage impor penerimaan
 *
 * AM_SIZ_STG_PENERIMAAN | Tampilan stage penerimaan
 * ------------------------------------------------------------------------
 */

	require_once COMPONENT_PATH."\\file\\transaksi_harian\\helper_transaksi.php";
	
	$queryGetStage =  sprintf(
			"SELECT s.*, a.namaakun FROM stage_penerimaan AS s ".
			"LEFT JOIN akun AS a ON s.kode_akun=a.kode ".
			"ORDER BY tanggal");
	$resultGetStage = mysqli_query($mysqli, $queryGetStage);
	
?>
<script>
var AJAX_URL = "main.php?s=ajax&m=transaksi";

// Fungsi templating untuk select2 donatur
function formatItemDonatur (elmtDonatur) {
  if (!elmtDonatur.id) { return elmtDonatur.text; }
  var elmtOutput = (
    '<div>' + elmtDonatur.text + '</div>' +
    '<div style="font-size:0.8em;"><span class="glyphicon glyphicon-envelope"></span> ' + elmtDonatur.alamat + '</div>'
  );
  return $(elmtOutput);
};
function submit_trx_penerimaan(idStage) {
	var formFields = $("#siz_frm_stgpenerimaan_"+idStage+" form").serialize();
	alert(formFields);
	return false;
}
function close_form_trx_penerimaan(idStage) {
	$('#siz_frm_stgpenerimaan_'+idStage+' .editing-form-ctr').slideUp(250,function(){
		var rowElmt = $("#siz_check_item_"+idStage).closest("tr");
		$(rowElmt).show();
		$('#siz_frm_stgpenerimaan_'+idStage).remove();
	});
	
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
	<table class="table table-bordered table-striped table-hover siz-operation-table">
		<thead>
			<tr>
				<th><input type='checkbox' name='siz_checkall'/></th>
				<th>Tanggal</th>
				<th>No. Nota<div><a href="#">Map</a></div></th>
				<th>Akad/Akun Penerimaan</th>
				<th style='min-width:140px;'>Nominal</th>
				<th>Amilin</th>
				<th>Donatur</th>
				<th>Keterangan</th>
				<th style='min-width: 100px;'>Aksi</th>
			</tr>
		</thead>
		<tbody>
<?php 
	while ($rowTransaction = mysqli_fetch_assoc($resultGetStage)) {
		$rowHtml = getHTMLRowTrxPenerimaan($rowTransaction);
		echo $rowHtml;
		$jmlNominal += $rowTransaction['jumlah'];
		$jmlTrx++;
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
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>
</div>


