<?php

/**
 * Generate HTML baris pada tabel AM_SIZ_STG_PENERIMAAN
 * 
 * @param Array $rowTransaction Data array associative baris transaksi
 * @return string HTML baris untuk AM_SIZ_STG_PENERIMAAN
 */
function getHTMLRowTrxPenerimaan($rowTransaction) {
	$txtDate = date("d/m/Y", strtotime($rowTransaction['tanggal']));
	$stageId = $rowTransaction['id_stage'];
	$mappingAkun = "";
	if ($rowTransaction['namaakun'] != null) {
		$mappingAkun = "<span class='glyphicon glyphicon-circle-arrow-right'></span> ".
				htmlspecialchars($rowTransaction['namaakun']);
	} else {
		$mappingAkun = "<a href=\"#\">Set Akun</a>";
	}
	$chkBoxId = "siz_check_item_".$stageId;
	$output = "
 		<tr>
 			<td><input type='checkbox' name=\"".$chkBoxId."\" id=\"".$chkBoxId."\"
 					value=\"".$rowTransaction['id_stage']."\"/></td>
 			<td><label for=\"".$chkBoxId."\">".($txtDate)."</label></td>
			<td>".htmlspecialchars($rowTransaction['no_nota'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['ket_akun'])."</div>
				<div>(".$mappingAkun.")</div></td>
			<td>".to_rupiah($rowTransaction['jumlah'])."</td>
			<td>".htmlspecialchars($rowTransaction['nama_amilin'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['nama_donatur'])."</div>
				<div><span class='glyphicon glyphicon-envelope'></span> ".
					htmlspecialchars($rowTransaction['alamat_donatur'])."</div></td>
			<td>".nl2br(htmlspecialchars($rowTransaction['keterangan']))."</td>
			<td>
				<div class='control-box'>
				<a href=\"#edit-trx\" onclick=\"return edit_trx_penerimaan(".$stageId.");\">
					<span class='glyphicon glyphicon-pencil'></span> Ubah</a><br>
				<a href=\"#hapus-trx\" onclick=\"return hapus_trx_penerimaan(this,".$stageId.")\"
						class='red_link'><span class='glyphicon glyphicon-trash'></span> Hapus</a>
				</div>
				<div class='loading-circle'>
					<img src=\"images/loader.gif\" alt=\"Loading\" />
				</div>
			</td>
 		</tr>
 		";
	return $output;
}