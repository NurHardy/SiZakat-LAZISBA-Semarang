<?php
/*
 * user_setprivilege.php
 * ==> AJAX server untuk menyimpan divisi user
 *
 * Digunakan pada :
 * o AM_SIZ_RA_USERMGMT | Management user
 */

	// Constants
	$listDivisi = array("Amilin",
			"Keuangan",
			"Kantor",
			"Marketing",
			"Campaign",
			"Program",
			99 => "Administrator Perencanaan"
	);

	$errorDesc = null;
	$dataUser = null;
	$oldPrivilege = null;
	
	$divisiUser		= $_SESSION['siz_divisi'];
	$isAdmin		= ($divisiUser == 99);
	
	$paramComplete	= true;
	$idUser	= intval($_POST['id']);
	$newPriv= intval($_POST['priv']);
	
	// Cek input
	$paramComplete = $paramComplete && !empty($idUser);
	
	if (!$paramComplete) {
		$errorDesc = "Parameter tidak lengkap.";
	} else {
		if (!is_numeric($newPriv)) {
			$errorDesc = "Nilai pivilege harus berupa angka numerik.";
		} else {
			if (!key_exists($newPriv, $listDivisi)) {
				$errorDesc = "Nilai pivilege tidak valid.";
			}
		}
	}
	
	// Cek user apakah ada dalam database?
	if ($errorDesc == null) {
		$queryCekUser = sprintf(
				"SELECT * FROM user WHERE id_user=%d LIMIT 1",
				$idUser);
		$resultCekUser = mysqli_query($mysqli, $queryCekUser);
		
		if ($resultCekUser != null) {
			$dataUser	= mysqli_fetch_array($resultCekUser);
			if ($dataUser['level'] != 99) { // Yang diubah hanya user petugas
				$errorDesc		= "User harus merupakan petugas.";
			} else {
				$oldPrivilege = $dataUser['divisi'];
			}
		} else {
			$errorDesc		= "Data user tidak ditemukan dalam database.";
		}
	}
	
	// Laksanakan query dan keluarkan output
	if ($errorDesc == null) {
		$querySimpan  = sprintf("UPDATE user SET divisi=%d WHERE id_user=%d", $newPriv, $idUser);
		
		$resultSimpan = mysqli_query($mysqli, $querySimpan);
		if ($resultSimpan) {
			echo json_encode(array(
					'status'	=> 'ok',
					'old_priv'	=> $listDivisi[$oldPrivilege],
					'new_priv'	=> $listDivisi[$newPriv],
					'id_user'	=> $idUser
			));
		} else {
			echo json_encode(array(
					'status' => 'error',
					'error' => "Query error: ".mysqli_error($mysqli)
			));
		}
		
	} else {
		echo json_encode(array(
				'status' => 'error',
				'error' => $errorDesc
		));
	}