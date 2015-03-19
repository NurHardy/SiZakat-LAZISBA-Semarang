<?php
	session_start();
	include "../config/koneksi.php";
	if(ISSET($_POST['login'])){
		$username = $_POST['username'];
		$password = sha1(sha1(md5($_POST['password'])));
		$sql = mysql_query("SELECT * FROM user WHERE username='$username' AND password = '$password'");
		$csql = mysql_num_rows($sql);
		
		if($csql >= 1){
			$r = mysql_fetch_array($sql);
			/*inisialisasi sesi*/
			$_SESSION['level']=$r['level'];
			$_SESSION['username']=$r['username'];
			$_SESSION['iduser']=$r['id_user'];
			if($r['level'] == '3'){
				$_SESSION['wil_bus']=$r['wilayah_bus'];
			}
			echo"<meta http-equiv=\"refresh\" content=\"0; url=../../main.php\">";
		}else{
			
		echo mysql_error();
		echo"<meta http-equiv=\"refresh\" content=\"0; url=../../login.php\">";
		}
	}else{
		echo mysql_error();
		echo"<meta http-equiv=\"refresh\" content=\"0; url=../../login.php\">";
	}
	

?>