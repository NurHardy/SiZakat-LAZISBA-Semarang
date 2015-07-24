<?php
// Output: JSON
//---------------------------
// Proses mengedit record stage transaksi penerimaan
// Note: field input sesuai dengan AM_SIZ_STG_PENERIMAAN

	$processError = null;
	
	// Ada data POST
	if (isset($_POST['id'])) {
		// Load library untuk validasi
		require_once COMPONENT_PATH."\\libraries\\validation.php";
		
		//== Inisialisasi, ambil data input lalu Validasi ==
		$stageId = intval($_POST['id']);
		$ketTrx		= null;
		$idBankTrx	= 0;
		$thRamadhan	= 0;
		$dataAkun	= null; // Row data akun penerimaan
		
		$tglTrx		= trim($_POST['tanggal']);
		$noNota		= trim($_POST['notrans']);
		$kodeAkunTrx = trim($_POST['trans']);
		$idDonatur	= trim($_POST['muzakki']);
		$idAmilin	= trim($_POST['amilin']);
		$nominalTrx	= trim($_POST['jumlah']);
		if (isset($_POST['keterangan']))
			$ketTrx		= trim($_POST['keterangan']);
		
		//== Cek record penerimaan
		if ($processError == null) {
			$queryCheck = sprintf("SELECT * FROM stage_penerimaan WHERE id_stage=%d", $stageId);
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
				// No nota harus berupa angka...
				if (!preg_match("/^[0-9-\s]+$/", $noNota)){
					$processError = "Nomor nota tidak boleh mengandung karakter selain angka, dash dan spasi.";
				
				// Kode akun harus berupa numerik dan titik
				} else if (!preg_match("/^[0-9.\s]+$/", $kodeAkunTrx)){
					$processError = "Kode akun tidak boleh mengandung karakter selain angka dan titik";
				
				// ID Aminilin harus berupa digit
				} else if (ctype_digit($idAmilin)) {
					$processError = "ID Amilin harus numerik";
				
				// Nominal harus numerik, jelas....
				} else if (ctype_digit($nominalTrx)) {
					$processError = "Nominal transaksi harus numerik";
				}
			}
		}
		
		//== Cek kode akun
		if ($processError == null) {
			$queryCekAkun = sprintf("SELECT * FROM akun WHERE kode='%s'",
					mysqli_real_escape_string($mysqli, $kodeAkunTrx));
			$resultCekAkun = mysqli_query($mysqli, $queryCekAkun);
	
			if (mysqli_num_rows($resultCekAkun) == 0) {
				$processError = "Data akun penerimaan tidak ditemukan.";
			} else {
				$dataAkun = mysqli_fetch_assoc($resultCekAkun);
				// Jika bukan akun penerimaan, maka dipermasalahkan...
				if ($dataAkun['jenis'] != 1) {
					$processError = "Akun penerimaan tidak valid.";
				}
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
		
		$tahunUkm	= trim($_POST['th_kubah']);
		
		//== Cek tahun kubah
		if (empty($processError)) {
			if (ctype_digit($tahunUkm)) {
				$tahunUkm = intval($tahunUkm);
			} else {
				$processError = "Tahun penyaluran KUBAH harus numerik.";
			}
		}
		
		
		//tanggal=2015-02-27&notrans=00210&trans=1.9.&muzakki=119&amilin=27&jumlah=1475000&keterangan=BUS
		
		
		
		// Tidak ada error, maka data dianggap telah valid
		if (empty($processError)) {
			
		}
	}
	
	if (!empty($processError)) {
		echo json_encode(array(
				'status' => 'error',
				'error'	=> $processError
		));
	} else {
		
	}