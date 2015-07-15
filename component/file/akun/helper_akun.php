<?php

// Disesuaikan dengan AM_SIZ_PERSMN_AKUN
function getHTMLSumberDana($kodePengeluaran) {
	global $mysqli, $queryCount;
	
	$htmlResult = "";
	$resultListSumberDana = mysqli_query ( $mysqli,
			"SELECT p.*, a.namaakun, a.kode ".
			"FROM persamaan_akun AS p, akun AS a ".
			"WHERE p.id_penyaluran='" . $kodePengeluaran . "' AND p.id_penerimaan=a.kode ".
			"ORDER BY p.prioritas DESC"
	);
	$queryCount++;
	if ($resultListSumberDana != null) {
		while ( $rowSumberDana = mysqli_fetch_array ( $resultListSumberDana ) ) {
			$htmlResult .= "<div class='siz_item_sumber' id='siz_pers_".$rowSumberDana['id_persamaan']."' ".
					"data-percentage='".$rowSumberDana['persentase']."' data-priority='".$rowSumberDana['prioritas']."'>\n".
					"<b>{$rowSumberDana['id_penerimaan']}</b> ".htmlspecialchars($rowSumberDana['namaakun']).
					" ({$rowSumberDana['persentase']}%)".
					"<div class='meta'>Prioritas: ".$rowSumberDana['prioritas']."</div>".
					"<div class='control_akun'>".
						"<a href=\"#\" title='Edit' onclick=\"return editSumberDana(".$rowSumberDana['id_persamaan'].");\">".
						"<span class='glyphicon glyphicon-pencil'></span></a> ".
						"<a href=\"#\" title='Hapus' onclick=\"return hapusSumberDana(".$rowSumberDana['id_persamaan'].");\">".
						"<span class='glyphicon glyphicon-remove'></span></a></div>".
					"</div>\n";
		}
	} else {
		$htmlResult = "Query error: ".mysqli_error($mysqli);
	}
	
	return $htmlResult;
}

function getAccountArray($jenisAkun, $idParent) {
	global $mysqli, $queryCount;
	
	// ----- Proses list akun pengeluaran
	$resultListAkun = mysqli_query ( $mysqli, "SELECT * FROM akun WHERE jenis={$jenisAkun} AND idParent={$idParent}" );
	$queryCount++;
	
	$childArray = array();
	
	while ( $rowAkun = mysqli_fetch_array ( $resultListAkun ) ) {
		$grandChildArray = null;
		$grandChildTotal = 0;
		// Menghindaru forever loop
		if ($rowAkun['idakun'] != $idParent) {
			$grandChildArray = getAccountArray($jenisAkun, $rowAkun['idakun']);
		}
		$grandChildTotal = count($grandChildArray);
		
		$childArray[] = array(
			'id'		=> $rowAkun['idakun'],
			'code'		=> $rowAkun['kode'],
			'label'		=> $rowAkun['namaakun'],
			'childs'	=> $grandChildTotal,
			'data'		=> $grandChildArray
		);
	}
	return $childArray;
}