<?php
	session_start();
	include "../config/koneksi.php";
	if(ISSET($_POST['login'])){
		$username = $_POST['username'];
		$password = sha1(sha1(md5($_POST['password'])));
		$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE username='$username' AND password = '$password'");
		$csql = mysqli_num_rows($sql);
		
		if($csql >= 1){
			$r = mysqli_fetch_array($sql);
			/*inisialisasi sesi*/
			$_SESSION['level']=$r['level'];
			$_SESSION['username']=$r['username'];
			$_SESSION['iduser']=$r['id_user'];
			if($r['level'] == '3'){
				$_SESSION['wil_bus']=$r['wilayah_bus'];
			}
			echo"<meta http-equiv=\"refresh\" content=\"0; url=../../main.php\">";
		}else{
			
		echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		echo"<meta http-equiv=\"refresh\" content=\"0; url=../../login.php\">";
		}
	}else{
		echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		echo"<meta http-equiv=\"refresh\" content=\"0; url=../../login.php\">";
	}
	

?>