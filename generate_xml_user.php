<?php 
		header('Content-Type: text/xml');
		echo "<?xml version='1.0'?>";
		echo "		<contact>";
	
		include "component/config/koneksi.php";
		
		$sql= mysql_query("SELECT * FROM user WHERE hp='$_GET[hp]'");
		while($d = mysql_fetch_array($sql)){
			echo "
				<name id='$d[id_user]'>$d[nama]</name>
			";
		}
		echo "</contact>";
?>