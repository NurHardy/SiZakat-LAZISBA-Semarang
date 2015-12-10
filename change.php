<?php
/*
	include "component/config/koneksi.php";
	
	$sql = $mysqli->query("SELECT * FROM penyaluran");
	while ($d = $sql->fetch_array(MYSQLI_ASSOC)) {
		$t = $d['tanggal'];
		$t = explode('-',$t);
		$t = $t[2].'-'.$t[1].'-'.$t[0];
		
		$sql1 = $mysqli->query("UPDATE penyaluran SET tanggal='".$t."' WHERE id_penyaluran='".$d['id_penyaluran']."'");
		echo "Update Finish<br />";
	}
	
	
	echo "Update Finish";
*/
