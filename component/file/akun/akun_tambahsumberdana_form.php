<?php
	// Output: HTML
	// =======================
	// Untuk AM_SIZ_PERSMN_AKUN
	
	$idPengeluaran = intval($_POST['id']);
	
	// ID akun dicek dulu apakah ada atau tidak...
	$queryCekAkun = sprintf("SELECT * FROM akun WHERE idakun=%d", $idPengeluaran);
	$resultCekAkun = mysqli_query($mysqli, $queryCekAkun);
	if (mysqli_num_fields($resultCekAkun) == 0) {
		echo "Data akun tidak ditemukan!";
		return;
	}
	$dataAkun = mysqli_fetch_assoc($resultCekAkun);
	
	// Ambil kode pengeluarannya...
	$kodePengeluaran = mysqli_escape_string( $mysqli, $dataAkun['kode'] );
	
	$queryListAkun = sprintf ( "SELECT * FROM akun WHERE jenis=1 AND kode NOT IN (".
			"SELECT id_penerimaan FROM persamaan_akun ".
			"WHERE id_penyaluran='%s') AND idakun NOT IN (SELECT DISTINCT idparent FROM akun)",
			$kodePengeluaran );
	
	$resultListAkun = mysqli_query ( $mysqli, $queryListAkun );
	
	echo "
			<form action='#' onsubmit='return submitTambahSumberDana(this, ".$idPengeluaran.");'
					id='form_tambahsumber_".$idPengeluaran."'>
				<div>
				<select name='siz_select_akun' class='siz_select_akun' style='width: 100%;' data-placeholder='- Pilih Akun -' required>
					<option></option>
		";
	while ( $rowAkun = mysqli_fetch_array ( $resultListAkun ) ) {
		echo "<option value='{$rowAkun['kode']}'>".$rowAkun['kode']." ";
		echo htmlspecialchars($rowAkun['namaakun'])."</option>";
	}
	echo "
				</select></div>
				<input type='text' value='100.00' id='siz_persentase' name='siz_persentase' size='6'
					pattern='([1-9]|[1-9][0-9]|100)(.([0-9]|[0-9][0-9]))?' maxlength='6'/>% &nbsp;
				<label for='siz_prioritas_add_".$idPengeluaran."'>Prioritas:</label>
				<input type='text' value='0' id='siz_prioritas_add_".$idPengeluaran."' name='siz_prioritas'
					pattern='[0-9]' required size='2' maxlength='1' title='prioritas'/>
				<div style='text-align:right;'>
					<a href='#' onclick='return cancelFormTambah(".$idPengeluaran.");' 
							class='btn btn-sm btn-danger'>Batal</a>
					<input type='submit' value='Tambah' class='btn btn-sm btn-primary' />
				</div>
			</form>
		";
