<?php
// Output: TODO
//---------------------------
// Proses menyimpan data transaksi penerimaan
// Note: field input sesuai dengan AM_SIZ_FRMPENERIMAAN

	$processError	= null;
	$newDataTrx		= null;
	
	// Ada data POST
	if (isset($_POST['submit'])) {
		// Load library untuk validasi
		require_once COMPONENT_PATH."/libraries/validation.php";
		require_once COMPONENT_PATH."/libraries/helper_akun.php";
		require_once COMPONENT_PATH."/libraries/helper_user.php";
		
		//== Inisialisasi, ambil data input lalu Validasi ==
		$trxId = intval($_POST['id']);
		$isEditing = ($trxId > 0);
		
		$ketTrx			= null;
		$idBankTrx		= 0;
		$tahunRamadhan	= 0;
		$dataAkun		= null; // Row data akun penerimaan
		
		$tglTrx		= trim($_POST['tanggal']);
		$noNota		= trim($_POST['notrans']);
		$kodeAkunTrx = trim($_POST['trans']);
		$idDonatur	= trim($_POST['muzakki']);
		$idAmilin	= trim($_POST['amilin']);
		$nominalTrx	= trim($_POST['jumlah']);
		if (isset($_POST['keterangan']))
			$ketTrx		= trim($_POST['keterangan']);
		
		//== Cek record penerimaan
		if ($isEditing && ($processError == null)) {
			$queryCheck = sprintf("SELECT * FROM penerimaan WHERE id_penerimaan=%d", $trxId);
			$resultCheck = mysqli_query($mysqli, $queryCheck);
			
			// Record tidak ditemukan
			if (mysqli_num_rows($resultCheck) == 0) {
				$processError = "Data transaksi tidak ditemukan dalam database!";
			}
		}
		
		if ($processError == null) {
			//== Cek field-field utama...
			$errMsg = check_required_fields(array(
					'Tanggal Transaksi' => $tglTrx,
					'Nomor Nota' => $noNota,
					'Akun Penerimaan' => $kodeAkunTrx,
					'Teller/Amilin' => $idAmilin,
					'Nominal' => $nominalTrx
			));
				
			if ($errMsg) {
				$processError = $errMsg;
			} else {
				// Validasi isi POST di sini...
				// Tanggal harus sesuai format (yyyy-mm-dd)
				if (!preg_match("/[0-9]{4}-([0][1-9]|[1][0-2])-([0][1-9]|[1][0-9]|[2][0-9]|[3](0|1))/", $tglTrx)) {
					$processError = "Format tanggal harus yyyy-mm-dd";
					
				// No nota harus berupa angka, spasi atau dash
				} else if (!preg_match("/^[0-9-\s]+$/", $noNota)){
					$processError = "Nomor nota tidak boleh mengandung karakter selain angka, dash dan spasi.";
				
				// Kode akun harus berupa numerik dan titik
				} else if (!preg_match("/^[0-9.\s]+$/", $kodeAkunTrx)){
					$processError = "Kode akun tidak boleh mengandung karakter selain angka dan titik";
				
				// ID Aminilin harus berupa digit
				} else if (!ctype_digit($idAmilin)) {
					$processError = "ID Amilin harus numerik";
				
				// Nominal harus numerik, jelas....
				} else if (!ctype_digit($nominalTrx)) {
					$processError = "Nominal transaksi harus numerik";
				}
			}
		}
		
		//== Cek kode akun
		if ($processError == null) {
			$dataAkun = cek_kode_akun($kodeAkunTrx);
			// Jika bukan akun penerimaan, maka dipermasalahkan...
			if ($dataAkun['jenis'] != 1) {
				$processError = "Akun penerimaan tidak valid.";
			}
		}
		
		//== Cek amilin
		if ($processError == null) {
			$dataAmilin = cek_user($idAmilin);
			if ($dataAmilin != null) {
				if ($dataAmilin['level'] != 99) {
					$processError = "ID Amilin/teller tidak valid.";
				}
			} else {
				$processError = "Data amilin tidak ditemukan.";
			}
		}
		
		//== Cek Muzakki
		if ($processError == null) {
			$dataMuzakki = cek_user($idDonatur);
			if ($dataMuzakki != null) {
				if ($dataMuzakki['level'] != 1) {
					$processError = "ID donatur tidak valid.";
				}
			} else {
				$processError = "Data muzakki tidak ditemukan.";
			}
		}
		//== Cek ID akun bank, jika merupakan transaksi perbankan
		if ($processError == null) {
			//== Jika merupakan transaksi bank
			if (!empty($_POST['bank'])) {
				$idBankTrx	= intval($_POST['bank']);
				
				$queryCekBank = sprintf("SELECT * FROM bank WHERE id_bank=%d", $idBankTrx);
				$resultCekBank = mysqli_query($mysqli, $queryCekBank);
				
				if (mysqli_num_rows($resultCekBank) == 0) {
					$processError = "Data akun bank tidak ditemukan.";
				}
			}
		}
		
		//$tahunUkm	= trim($_POST['th_kubah']);
		
		//== Cek tahun kubah
		//if (empty($processError)) {
		//	if (ctype_digit($tahunUkm)) {
		//		$tahunUkm = intval($tahunUkm);
		//	} else {
		//		$processError = "Tahun penyaluran KUBAH harus numerik.";
		//	}
		//}
		
		// Tidak ada error, maka data dianggap telah valid
		if (empty($processError)) {
			require_once COMPONENT_PATH."/libraries/querybuilder.php";
			
			$updateFields = array(
				'no_nota'	=> $noNota, 
				'tanggal'	=> $tglTrx,
				'id_donatur'	=> $idDonatur,
				'id_teller'		=> $idAmilin,
				'jumlah'	=> $nominalTrx,
				'id_akun'	=> $kodeAkunTrx,
				'id_bank'	=> $idBankTrx
			);
			$querySet = querybuilder_generate_set($updateFields);
			// Jalankan query
			if ($isEditing) {
				$querySimpan = "UPDATE penerimaan SET ".$querySet." WHERE id_penerimaan=".$trxId;
			} else {
				$querySimpan = "INSERT INTO penerimaan SET ".$querySet;
			}
			 
			$resultSimpan = true; //mysqli_query($mysqli, $querySimpan);
			if ($resultSimpan == null) {
				$processError = "Terjadi kesalahan internal: ".mysqli_error($mysqli);
			} else {
				// Get the new record
				//if (!$isEditing) $trxId = mysqli_insert_id($mysqli);
				//$resultReFetch = mysqli_query($mysqli,
				//		"SELECT s.*, a.namaakun ".
				//		"FROM (SELECT * FROM penerimaan WHERE id_penerimaan=".$trxId.") AS s ".
				//		"LEFT JOIN akun AS a ON s.id_akun=a.kode");
				//$newDataTrx = mysqli_fetch_assoc($resultReFetch);
			}
		} // End if (empty($processError))
	} else { // End if (isset($_POST['submit']))
		$processError = "Argumen tidak lengkap.";
	}
	