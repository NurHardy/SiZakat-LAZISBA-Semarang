<?php 
	$q1 = mysql_query("SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
	$sql = mysql_query("TRUNCATE TABLE persamaan_akun");
	while($p1 = mysql_fetch_array($q1)){
		$id = $p1['idakun'];
		
		$pers_akun = $_POST['akun_'.$id];
		if(count($pers_akun > 0)){
			$sql = mysql_query("INSERT INTO persamaan_akun (id_penerimaan,id_penyaluran) VALUES ('$p1[kode]','$pers_akun[]')");
		}
	}
?>