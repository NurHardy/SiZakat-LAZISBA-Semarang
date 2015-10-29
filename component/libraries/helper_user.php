<?php

	function cek_user($idUser, $field = 'id') {
		global $mysqli, $queryCount;
		
		$fieldName = "id_user";
		$fieldValue = intval($idUser);
		if ($field == 'username') {
			$fieldName = "username";
			$fieldValue = "'".mysqli_escape_string($mysqli, $idUser)."'";
		} else if ($field == 'email') {
			$fieldName = "email";
			$fieldValue = "'".mysqli_escape_string($mysqli, $idUser)."'";
		}
		$queryCekUser = sprintf("SELECT * FROM user WHERE %s=%s", $fieldName, $fieldValue);
		$resultCekUser = mysqli_query($mysqli, $queryCekUser);
		$queryCount++;
		
		$dataUser = mysqli_fetch_assoc($resultCekUser);
		return $dataUser;
	}
	
	function cek_user_password($inputPass, $hashedPass) {
		return (sha1(sha1(md5($inputPass)))==$hashedPass);
	}
	// Return bukan NULL berhasil
	function set_user_password($idUser, $newPass) {
		global $mysqli, $queryCount;
		
		$newPasswordHash = sha1(sha1(md5($newPass)));
		$queryChangePass = sprintf("UPDATE user SET password='%s' WHERE id_user=%d",
				$newPasswordHash, $idUser);
		
		$resultQuery = mysqli_query($mysqli, $queryChangePass);
		$queryCount++;
		
		return $resultQuery;
	}