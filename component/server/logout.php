<?php
	error_reporting(0);
	/*memulai sesi*/
	//session_start();
	
	/*semua sesi akan di destroy*/
	if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
		session_destroy();
		
		/*mengalihkan ke halaman login lagi*/
		//echo "<meta http-equiv=\"refresh\" content=\"0; url=login.php\">";
	}
	else{
		session_destroy();
		/*mengalihkan ke halaman login  lagi*/
		//echo mysql_error();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=login.php\">";
	};

?>