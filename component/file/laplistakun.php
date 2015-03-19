<?php
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	include "component/config/koneksi.php";
	
	$bln = $_GET['bln'];
	$th = $_GET['th'];
	if($bln == 1){
		$blnm = 12;
		$thm = $th-1;
	}else{
		$blnm = $bln-1;
		$thm = $th;
	}
	
	
	$sql = mysqli_query($mysqli, "SELECT * FROM penerimaan WHERE tanggal < '$th-$bln-01'");
	$sql1 = mysqli_query($mysqli, "SELECT * FROM penyaluran WHERE tanggal < '$th-$bln-01'");
	$error = 0;
	if((mysqli_num_rows($sql) > 0) || (mysqli_num_rows($sql1) > 0)){
		//jika ada transaksi sebelum bulan yang dipilih
		
	}else{
		//jika tidak ada transaksi sebelum bulan yang dipilih
		$sqla = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name='bln_th_saldo'");
		$d = mysqli_fetch_array($sqla);
		$d = explode('#',$d['value']);
		if(($d[0] <= $bln) && ($d[1] <= $th)){
			//ambil saldo awal
			
		}else{
			$error = 1;
			$_SESSION['error'] = "Tidak Ada Transaksi untuk bulan ".$month1[$_GET['bln']]." - $_GET[th]";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=lapakun\">";
		}
		
	}
	
	if($error == 0){
?>

<div class="col-12"><br/>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Laporan Per Akun Bulan <?php echo $month1[$_GET['bln']]." ".$_GET['th'];?></h5>
		</div>
		<div class="widget-content nopadding">
			<a href="component/file/cetaktransaksiperakun.php?bln=<?php echo $_GET['bln'];?>&th=<?php echo $_GET['th'];?>" class='btn btn-warning btn-mini' style='margin:10px;'>Cetak Transaksi Masuk per Akun</a>
			<div style='padding:10px;'>
				<table class="table table-bordered table-hover">
					<tr>
						<th>No</th>
						<th>Nama Akun</th>
						<th>Saldo Bulan Lalu</th>
						<th>Penerimaan</th>
						<th>Penyaluran</th>
						<th>Saldo</th>
					</tr>
					
					<?php 
						$sql = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
						$i=0;
						
						while($f = mysqli_fetch_array($sql)){
							$i++;
							//saldo awal
							$sql1 = mysqli_query($mysqli, "SELECT * FROM saldo_awal WHERE id_akun='$f[kode]'");
							$s = mysqli_fetch_array($sql1);
							$saldo_awal = $s['saldo'];
							
							
							//penerimaan Bulan Lalu
							$sql1 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE id_akun='$f[kode]' AND tanggal < '$_GET[th]-$_GET[bln]-01'");
							$s = mysqli_fetch_array($sql1);
							$penerimaan_bln_lalu = $s['jumlah'];
							
							
							
							//penyaluran bulan lalu
							$salur = 0;
							$sql2 = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penerimaan='$f[kode]'");
							while($d = mysqli_fetch_array($sql2)){
								$sql3 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun LIKE '$d[id_penyaluran]' AND tanggal < '$_GET[th]-$_GET[bln]-01'");
								$g = mysqli_fetch_array($sql3);
								$salur = $salur + $g['jumlah'];
							}
							
							
							$saldo_bulan_lalu = ($saldo_awal + $penerimaan_bln_lalu) - $salur;
							
							
							
							$sql22 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE id_akun LIKE '$f[kode]%' AND MONTH(tanggal) = '$_GET[bln]' AND YEAR(tanggal) = '$_GET[th]'");
							$w = mysqli_fetch_array($sql22);
							
							if($w['jumlah'] == ""){
								$jml_masuk = 0;
							}else{
								$jml_masuk = $w['jumlah'];
							}
							
							
							$jml_keluar = 0;
							$sql2 = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penerimaan='$f[kode]'");
							while($d = mysqli_fetch_array($sql2)){
								$sql3 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun LIKE '$d[id_penyaluran]' AND MONTH(tanggal) = '$_GET[bln]' AND YEAR(tanggal) = '$_GET[th]'");
								$g = mysqli_fetch_array($sql3);
								$jml_keluar = $jml_keluar + $g['jumlah'];
							}

							?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?php echo $f['kode']." ".$f['namaakun'];?></td>
									<td><?php echo $saldo_bulan_lalu;?></td>
									<td><?php echo $jml_masuk;?></td>
									<td><?php echo $jml_keluar;?></td>
									<td><?php echo $saldo_bulan_lalu+$jml_masuk-$jml_keluar;?></td>
								</tr>
							<?php
						}
					
					?>
				</table>
			</div>
		</div>
	</div>
</div>

<?php }; ?>