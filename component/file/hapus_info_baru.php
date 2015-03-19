<?php
	include "component/config/koneksi.php";
	
	$get_hapus = $_GET['id'];
	mysql_query ("DELETE FROM informasi WHERE id_informasi = '$get_hapus'");
	
	echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=daftar_info\">";
?>