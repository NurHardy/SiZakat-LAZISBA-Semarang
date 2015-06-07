<?php
	session_start();
	if(ISSET($_POST)){
		include "../config/koneksi.php";
		
		if (isset($_POST['aset']) == 1){
			$query = sprintf("INSERT INTO aset SET barang='%s', id_wujud=%d, harga_satuan=%d",
								$mysqli->real_escape_string($_POST['namabrg']),
								$_POST['wujud'],
								$_POST['harga_satuan']);
		}else{
			$query = sprintf("INSERT INTO penerimaan SET no_nota=%d, id_teller=%d, id_akun='%s'", 
									$_POST['notrans'],
									$_POST['amilin'],
									$_POST['trans']);
			$queryFilter = "";
			$queryFilter .= ", id_bank=".(isset($_POST['transfer']) ? ($_POST['bank']) : 0);

			$query .= $queryFilter;
		}
		// keterangan, jumlah, tanggal, id donatur
		
		$queryFilter2 = sprintf(", tanggal='%s', keterangan='%s', jumlah=%d, id_donatur=%d",
									$_POST['tanggal'],
									$mysqli->real_escape_string($_POST['keterangan']),
									$_POST['jumlah'],
									$_POST['muzakki']);
		$query .= $queryFilter2;
		$result = $mysqli->query($query);
		
		if($result){
			$_SESSION['success'] = "Proses memasukkan informasi ketakmiran berhasil";
			echo "<meta http-equiv=\"refresh\" content=\"3; url=../../main.php?s=form_penerimaan\">";
		}
		else{
			$_SESSION['error'] = $query."Proses memasukkan informasi ketakmiran gagal, karena ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_penerimaan\">";
	}


?>