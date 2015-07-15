<?php 
/*
 * dokumen_perencanaan.php
 * ==> Lihat list lengkap perencanaan tahunan
 *
 * AM_SIZ_RA_DOKPERENC | Dokumen Perencanaan
 * ------------------------------------------------------------------------
 */
	// Cek privilege
	if (!ra_check_privilege()) return;
?>
<script>
function appendRincian(idAgenda, dataRincian) {
	try {
		var newRowElement = "";
		var banyakRincian = dataRincian.data.length;
		var c = 0;
		for (c=0;c<banyakRincian;c++) {
			newRowElement += "<tr class=\"siz_rincian_a_"+idAgenda+"\" style=\"display:none;\">";
			newRowElement += "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			newRowElement += "<td>&bull; "+dataRincian.data[c].nama+"</td>";
			newRowElement += "<td>"+dataRincian.data[c].txt_jumlah+"</td>";
			newRowElement += "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
		}
		$(".siz_agenda_"+idAgenda).after(newRowElement);
		$(".siz_rincian_a_"+idAgenda).fadeIn(250);
	} catch (e) {
		alert("Terjadi kesalahan internal...");
		alert(e);
	}
}
function surpriseMe(nodeId) {
	//var currentRow = $(domElmt).closest("tr");
	//var newRowElement = "<tr class=\"siz_node_"+nodeId+"\" style=\"display:none;\"><td colspan=\"8\">Hello, world!</td></tr>";
	//$(currentRow).after(newRowElement);
	//$(".siz_node_"+nodeId).fadeIn(250);
	
	
	$.ajax({
		type: "POST",
		url: "<?php echo RA_AJAX_URL; ?>",
		data: {
			'act': 'get.rincian',
			'id' : nodeId
		},
		dataType: 'json',
		beforeSend: function( xhr ) {
			$(".siz_agenda_"+nodeId+" a.lihat_rincian").
				after("<span class=\"span_loading\">Loading...</span>").hide();
		},
		success: function(response){
			if (response.status == 'ok') {
				appendRincian(nodeId, response);
			} else {
				$(".siz_agenda_"+nodeId+" a.lihat_rincian").show();
				alert("Reponse error: "+response.error);
			}
		},
		error: function(xhr){
			//alert("Terjadi kesalahan: "+xhr.status + " " + xhr.statusText);
		}
	}).always(function() {
		$(".siz_agenda_"+nodeId+" span.span_loading").hide();
	});
	return false;
}
</script>
<div class="col-12">
	<?php ra_print_status($namaDivisiUser); ?>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Perencanaan Tahun <?php echo $tahunDokumen;?></h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
				<table class="table table-bordered table-hover">
					<tr>
						<th rowspan="2" style='width:30px;'>No.</th>
						<th rowspan="2">Bulan</th>
						<th rowspan="2">Tanggal</th>
						<th colspan="3">Agenda</th>
						<th rowspan="2">Anggaran Per bulan</th>
						<th rowspan="2">Aksi</th>
					</tr>
					<tr>
						<th>Kegiatan</th>
						<th>Rincian Anggaran</th>
						<th>Per Kegiatan</th>
					</tr>
					<?php
		$listQuery =	"SELECT a.*, MONTH(a.tgl_mulai) AS bulan, DAY(a.tgl_mulai) AS tanggal, k.nama_kegiatan AS kegiatan ".
						"FROM ra_agenda AS a, ra_kegiatan AS k ".
						"WHERE YEAR(a.tgl_mulai)=".$tahunDokumen." AND k.id_kegiatan=a.id_kegiatan ORDER BY a.tgl_mulai";
		
		$listResult		= mysqli_query($mysqli, $listQuery);
		$counterAgenda	= 0;
		$totalAnggaran	= 0;
		$anggaranBulan	= 0;
		
		$bulanSekarang	= 0;
		$tampilNamaBulan = false;;
		while($rowAgenda = mysqli_fetch_array($listResult)){
			$counterAgenda++;
			$totalAnggaran += $rowAgenda['jumlah_anggaran'];
			$anggaranBulan += $rowAgenda['jumlah_anggaran'];
			
			if ($rowAgenda['bulan'] != $bulanSekarang) {
				if ($bulanSekarang != 0) {
					echo "<tr><td colspan=\"6\">&nbsp;</td><td>".to_rupiah($anggaranBulan)."</td>";
					echo "<td>&nbsp;</td></tr>\n";
				}
				$bulanSekarang = $rowAgenda['bulan'];
				echo "<tr><td colspan=\"8\"><i class=\"glyphicon glyphicon-calendar\"></i>\n";
				echo " <b>Bulan ".$monthName[$bulanSekarang]."</b></td></tr>\n";
				$anggaranBulan = 0;
				
				$tampilNamaBulan = true;
			} else {
				$tampilNamaBulan = false;
			}
			echo "<tr class=\"siz_agenda_".$rowAgenda['id_agenda']."\">\n";
			if ($tampilNamaBulan) {
				echo "	<td>".$bulanSekarang."</td>\n";
				echo "	<td>".$monthName[$bulanSekarang]."</td>\n";
			} else {
				echo "  <td>&nbsp;</td><td>&nbsp;</td>\n";
			}
			echo "	<td>".$rowAgenda['tanggal']."</td>\n";
			echo "	<td><a href=\"".ra_gen_url('kegiatan',$tahunDokumen,"id=".$rowAgenda['id_kegiatan']);
			echo "\">".$rowAgenda['kegiatan']."</a></td>\n";
			echo "	<td><a href=\"#\" onclick=\"return surpriseMe(".$rowAgenda['id_agenda'].");\" ";
			echo "		class=\"lihat_rincian\">";
			echo "			<span class=\"glyphicon glyphicon-search\"></span> Lihat Rincian";
			echo "		</a></td>\n";
			echo "	<td>".to_rupiah($rowAgenda['jumlah_anggaran'])."</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "</tr>\n";
		}
			?>
					<tr>
						<td colspan="6">
							<i class="glyphicon glyphicon-list-alt"></i>
							<b>Jumlah Anggaran</b></td>
						<td><?php echo to_rupiah($totalAnggaran);?></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>