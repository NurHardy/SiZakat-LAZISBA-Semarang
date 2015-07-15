<?php 
	$q1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
	$sql = mysqli_query($mysqli, "TRUNCATE TABLE persamaan_akun");
	while($p1 = mysqli_fetch_array($q1)){
		$id = $p1['idakun'];
		
		$pers_akun = $_POST['akun_'.$id];
		if(count($pers_akun > 0)){
			$sql = mysqli_query($mysqli, "INSERT INTO persamaan_akun (id_penerimaan,id_penyaluran) VALUES ('{$p1[kode]}','{$pers_akun[0]}')");
		}
	}
