<?php

	function cek_user_id($idUser) {
		global $mysqli, $queryCount;
		
		$queryCekUser = sprintf("SELECT * FROM user WHERE id_user=%d", $idUser);
		$resultCekUser = mysqli_query($mysqli, $queryCekUser);
		$queryCount++;
		
		$dataUser = mysqli_fetch_assoc($resultCekUser);
		return $dataUser;
	}