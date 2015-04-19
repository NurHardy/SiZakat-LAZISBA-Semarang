<?php
	include "component/config/koneksi.php";
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	$sql = mysqli_query($mysqli, "SELECT * FROM penerimaan WHERE MID(tanggal,6,2) = '$_GET[bln]' AND MID(tanggal,1,4) = '$_GET[th]' ");
	$sql1 = mysqli_query($mysqli, "SELECT * FROM penyaluran WHERE MID(tanggal,6,2) = '$_GET[bln]' AND MID(tanggal,1,4) = '$_GET[th]' ");
	if((mysqli_num_rows($sql) <= 0) && (mysqli_num_rows($sql1) <= 0)){
		$_SESSION['error'] = "Tidak Ada Transaksi untuk bulan ".$month1[$_GET['bln']]." - $_GET[th]";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=lapbulanan\">";
	}
?>

<div class="col-12"><br/>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Laporan Bulan <?php echo $month1[$_GET['bln']]." ".$_GET['th'];?></h5>
		</div>
		<?php 
			/*SQL*/
			$sql = mysqli_query($mysqli, "SELECT SUM(saldo) as saldo FROM saldo_awal");
			$s = mysqli_fetch_array($sql);
			
			
			//Penerimaan
			$sql1 = mysqli_query($mysqli, "SELECT * FROM `penerimaan` WHERE tanggal <= '$_GET[th]-$_GET[bln]-01'");
			if(mysqli_num_rows($sql1) <= 0){
				$sql = mysqli_query($mysqli, "SELECT * FROM saldo_awal");
			$s = mysqli_fetch_array($sql);
			}
		?>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
				<table class="table table-bordered table-hover">
					<tr>
						<th style='width:30px;'>A.</th>
						<th colspan='3'>KAS</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
					<tr>
						<td></td>
						<td style='width:30px;'>I</td>
						<td colspan='2'>Saldo Awal</td>
						<td><?php echo $s['saldo'];?></td>
						<td><?php echo $s['saldo'];?></td>
					</tr>
					<tr>
						<td></td>
						<td>II</td>
						<td colspan='2'>Penerimaan</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style='width:30px;'>1.</td>
						<td>Zakat</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>2.</td>
						<td>Infaq</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td>20.000</td>
					</tr>
					
					<tr>
						<td></td>
						<td>III</td>
						<td colspan='2'>Pengeluaran</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style='width:30px;'>1.</td>
						<td>Zakat</td>
						<td>10.000</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td>10.000</td>
					</tr>
					<tr>
						<td></td>
						<td colspan='4'>SALDO KAS (I+II-III)</td>
						<td>210.000</td>
					</tr>
					<tr>
						<th style='width:30px;'>A.</th>
						<th colspan='3'>KAS</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>