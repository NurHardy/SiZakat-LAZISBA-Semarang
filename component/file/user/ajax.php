<?php

$ajaxAct = $_POST['act'];
$errorDesc = null;

if ($ajaxAct == "get.user.donatur") {
	$jsonResult = array();
	if (isset($_POST['q'])) {
		$queryTerm = $_POST['q'];
		$queryTerm = mysqli_real_escape_string($mysqli, $queryTerm);
		$getQuery = ("SELECT * FROM user WHERE (level IN (1,2,3)) AND (nama LIKE '%".$queryTerm."%')");
		$resultQuery = mysqli_query($mysqli, $getQuery);
		
		while ($rowUser = mysqli_fetch_assoc($resultQuery)) {
			$jsonResult[] = array(
					'id' => $rowUser['id_user'],
					'text' => $rowUser['nama'],
					'alamat' => $rowUser['alamat']
			);
		}
	}
	echo json_encode($jsonResult);
} else if ($ajaxAct == "get.user.amilin") {
	
} else {
	echo json_encode(array(
			'status' => 'error',
			'error'	=> 'Unrecognized action.'
	));
}