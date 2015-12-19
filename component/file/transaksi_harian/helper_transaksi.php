<?php

/**
 * Generate HTML baris pada tabel AM_SIZ_STG_PENERIMAAN
 * 
 * @param Array $rowTransaction Data array associative baris transaksi
 * @return string HTML baris untuk AM_SIZ_STG_PENERIMAAN
 */
function getHTMLRowTrxPenerimaan($rowTransaction) {
	$isOK = ($rowTransaction['id_donatur'] != 0) && ($rowTransaction['kode_akun'] != '0');
	$isOK &= ($rowTransaction['id_teller'] != 0) && ($rowTransaction['id_bank'] != -1);
	
	$txtDate = date("d/m/Y", strtotime($rowTransaction['tanggal']));
	$stageId = $rowTransaction['id_stage'];
	$mappingAkun = "";
	$mappingDonatur = "";
	// Jika akun sudah dimapping...
	if ($rowTransaction['namaakun'] != null) {
		$mappingAkun = "<span class='glyphicon glyphicon-circle-arrow-right'></span> ".
				htmlspecialchars($rowTransaction['namaakun']);
	} else {
		$mappingAkun = "<a href=\"#\">Set Akun</a>";
	}
	
	// Jika donatur sudah dimapping...
	if ($rowTransaction['nama_mapdonatur'] != null) {
		$mappingDonatur = "<span class='glyphicon glyphicon-circle-arrow-right'></span> ".
				htmlspecialchars($rowTransaction['nama_mapdonatur']);
	} else {
		$mappingDonatur = "<a href=\"#\">Set Donatur</a>";
	}
	$chkBoxId = "siz_check_item_".$stageId;
	$output = "
 			<td><input type='checkbox' name=\"".$chkBoxId."\" id=\"".$chkBoxId."\"
 					value=\"".$rowTransaction['id_stage']."\"/>&nbsp;".
 					($isOK?"<img src='images/icons/icon_check_16.png' alt='OK' />":
 						   "<img src='images/icons/icon_wrong_16.png' alt='-' />")
 					."</td>
 			<td><label for=\"".$chkBoxId."\">".($txtDate)."</label></td>
			<td>".htmlspecialchars($rowTransaction['no_nota'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['ket_akun'])."</div>
				<div>(".$mappingAkun.")</div></td>
			<td>".to_rupiah($rowTransaction['jumlah'])."</td>
			<td>".htmlspecialchars($rowTransaction['nama_amilin'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['nama_donatur'])."</div>
				<div><span class='glyphicon glyphicon-envelope'></span> ".
					htmlspecialchars($rowTransaction['alamat_donatur'])."
					</div><div>(".$mappingDonatur.")</div></td>
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
 		";
	return $output;
}

/**
 * Generate HTML baris pada tabel AM_SIZ_STG_PENGELUARAN
 *
 * @param Array $rowTransaction Data array associative baris transaksi
 * @return string HTML baris untuk AM_SIZ_STG_PENGELUARAN
 */
function getHTMLRowTrxPengeluaran($rowTransaction) {
	$isOK = ($rowTransaction['kode_akun'] != '0');
	$isOK &= ($rowTransaction['id_bank'] != -1);

	$txtDate = date("d/m/Y", strtotime($rowTransaction['tanggal']));
	$stageId = $rowTransaction['id_stage'];
	$mappingAkun = "";
	$mappingDonatur = "";
	// Jika akun sudah dimapping...
	if ($rowTransaction['namaakun'] != null) {
		$mappingAkun = "<span class='glyphicon glyphicon-circle-arrow-right'></span> ".
				htmlspecialchars($rowTransaction['namaakun']);
	} else {
		$mappingAkun = "<a href=\"#\">Set Akun</a>";
	}

	// Jika donatur sudah dimapping...
	if ($rowTransaction['nama_mapdonatur'] != null) {
		$mappingDonatur = "<span class='glyphicon glyphicon-circle-arrow-right'></span> ".
				htmlspecialchars($rowTransaction['nama_mapdonatur']);
	} else {
		$mappingDonatur = "-";
	}
	$chkBoxId = "siz_check_item_".$stageId;
	$output = "
 			<td><input type='checkbox' name=\"".$chkBoxId."\" id=\"".$chkBoxId."\"
 					value=\"".$rowTransaction['id_stage']."\"/>&nbsp;".
 					($isOK?"<img src='images/icons/icon_check_16.png' alt='OK' />":
 							"<img src='images/icons/icon_wrong_16.png' alt='-' />")
 							."</td>
 			<td><label for=\"".$chkBoxId."\">".($txtDate)."</label></td>
			<td>".htmlspecialchars($rowTransaction['no_nota'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['ket_akun'])."</div>
				<div>(".$mappingAkun.")</div></td>
			<td>".to_rupiah($rowTransaction['jumlah'])."</td>
			<td>".htmlspecialchars($rowTransaction['nama_amilin'])."</td>
			<td><div>".htmlspecialchars($rowTransaction['nama_donatur'])."</div>
				<div>(".$mappingDonatur.")</div></td>
			<td>".nl2br(htmlspecialchars($rowTransaction['keterangan']))."</td>
			<td>
				<div class='control-box'>
				<a href=\"#edit-trx\" onclick=\"return edit_trx_pengeluaran(".$stageId.");\">
					<span class='glyphicon glyphicon-pencil'></span> Ubah</a><br>
				<a href=\"#hapus-trx\" onclick=\"return hapus_trx_pengeluaran(this,".$stageId.")\"
						class='red_link'><span class='glyphicon glyphicon-trash'></span> Hapus</a>
				</div>
				<div class='loading-circle'>
					<img src=\"images/loader.gif\" alt=\"Loading\" />
				</div>
			</td>
 		";
	return $output;
}

/**
 * Generate HTML untuk 
 * @param number $limitCount
 */
function generate_latest_trx_penerimaan($limitCount = 10) {
	global $mysqli;
	$output = "";
	
	$queryLatest = "SELECT * FROM penerimaan ORDER BY tanggal DESC LIMIT 10";
	$resultLatest = mysqli_query($mysqli, $queryLatest);
	while ($rowTrx = mysqli_fetch_assoc($resultLatest)) {
		$editLink = "main.php?s=transaksi&action=edit-in&id=".$rowTrx['id_penerimaan']."&ref=form-in";
		$output .= "<tr>
			<td>".$rowTrx['tanggal']."</td>
			<td>".$rowTrx['id_akun']."</td>
			<td>".$rowTrx['id_donatur']."</td>
			<td>".to_rupiah($rowTrx['jumlah'])."</td>
			<td><a href=\"".htmlspecialchars($editLink)."\"><i class=\"glyphicon glyphicon-pencil\"></i>&nbsp;Edit</a>-
				<a href=\"#hapus-trx\" class=\"red_link\"><i class=\"glyphicon glyphicon-trash\"></i>&nbsp;Hapus</a></td>
		</tr>\n";
	}
	return $output;
}