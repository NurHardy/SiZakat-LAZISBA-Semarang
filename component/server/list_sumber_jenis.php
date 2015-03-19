<select name="sumber" class='sumber11' style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
<?php 
		include "../config/koneksi.php";
		
		$sql = mysqli_query($mysqli, "SELECT * FROM persamaan_akun p, akun a WHERE p.id_penyaluran='$_POST[jenis]' AND p.id_penerimaan = a.kode ORDER BY namaakun ASC");
		$i=0;
		while($s = mysqli_fetch_array($sql)){
			if($i == 0){
				echo "<option value='$s[id_persamaan]'>$s[namaakun]</option>";
			}else{
				echo "<option value='$s[id_persamaan]'>$s[namaakun]</option>";
			}
		}
?>
</option>