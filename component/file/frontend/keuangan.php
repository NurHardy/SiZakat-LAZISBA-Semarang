<?php
	$bulan = date('m');
	$tahun = date('Y');
	
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	$bln = $bulan;
	$th = $tahun;
	if($bln == 1){
		$blnm = 12;
		$thm = $th-1;
	}else{
		$blnm = $bln-1;
		$thm = $th;
	}
	
	$sql = $mysqli->query("SELECT * FROM penerimaan WHERE tanggal < '".$th."-".$bln."-01'");
	$sql1 = $mysqli->query("SELECT * FROM penyaluran WHERE tanggal < '".$th."-".$bln."-01'");
	$error = 0;
	if (($sql->num_rows > 0) || ($sql1->num_rows > 0)){
		//jika ada transaksi sebelum bulan yang dipilih
		
		//ambil saldo awal
		$sqla = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$saldo_awal = $d['saldo']; //saldo awal;
		
		//ambil penerimaan
		$sqla = $mysqli->query("SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE tanggal < '$th-$bln-01'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$penerimaan = $d['jumlah'];
		
		//ambil penyaluran
		$sqla = $mysqli->query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE tanggal < '$th-$bln-01'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$penyaluran = $d['jumlah'];
		
		$saldo_bulan_lalu = ($saldo_awal + $penerimaan) - $penyaluran;
	}else{
		//jika tidak ada transaksi sebelum bulan yang dipilih
		$sqla = $mysqli->query("SELECT * FROM opsi WHERE name='bln_th_saldo'");
		$d = $sqla->fetch_array(MYSQLI_ASSOC);
		$d = explode('#',$d['value']);
		if(($d[0] <= $bln) && ($d[1] <= $th)){
			//ambil saldo awal
			$sqla = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
			$d = $sqla->fetch_array(MYSQLI_ASSOC);
			$saldo_bulan_lalu = $d['saldo']; //saldo awal;
		}else{
			$error = 1;
			$_SESSION['error'] = "Tidak Ada Transaksi untuk bulan ".$month1[$bulan]." - ".$_GET['th'];
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=lapbulanan\">";
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
			<h5>Laporan Bulan <?php echo $month1[$bulan]." ".$tahun;?></h5>
		</div>
		<?php 
			/*SQL*/
			$sql = $mysqli->query("SELECT SUM(saldo) as saldo FROM saldo_awal");
			$s = $sql->fetch_array(MYSQLI_ASSOC);
			
			
			//Penerimaan
			$sql1 = $mysqli->query("SELECT * FROM `penerimaan` WHERE tanggal <= '".$tahun."-".$bulan."-01'");
			if($sql1->num_rows <= 0) {
				$sql = $mysqli->query("SELECT * FROM saldo_awal");
				$s = $sql->fetch_array(MYSQLI_ASSOC);
			}
		?>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
			<?php if($_SESSION['level']==99){?>
			<?php }; ?>
				
				<br />
				<br />
				<style>
					.table, .table th, .table td{
						border-collapse:collapse;
						border:1px solid #000;
						padding:10px;
						color:#000;
						font-size:13px;
					}
					
					.table th{
						background:#333;
						color:#FFF;
					}
					
				</style>
				<table class="table table-bordered table-hover" width='100%'>
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
						<td  style='text-align:right;'><?php echo 'Rp. '.number_format($saldo_bulan_lalu,0,'','.');?></td>
						<td  style='text-align:right;'><?php echo 'Rp. '.number_format($saldo_bulan_lalu,0,'','.');?></td>
					</tr>
					<tr>
						<td></td>
						<td>II</td>
						<td colspan='2'>Penerimaan</td>
						<td></td>
						<td></td>
					</tr>
					<?php 
						$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penerimaan p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND id_akun LIKE '1.%' GROUP BY p.id_akun");
						$i=0;
						$total_masuk = 0;
						while($q = $sqls->fetch_array(MYSQLI_ASSOC)){
							$i++;
							$total_masuk = $total_masuk + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td  style='text-align:right;'><?php echo  'Rp. '.number_format($q['jumlah'],0,'','.');?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td  style='text-align:right;'><?php echo  'Rp. '.number_format($total_masuk,0,'','.');?></td>
					</tr>
					
					<tr>
						<td></td>
						<td>III</td>
						<td colspan='2'>Pengeluaran</td>
						<td></td>
						<td></td>
					</tr>
					<?php 
						$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '2.%') GROUP BY p.id_akun");
						$i=0;
						$total_keluar = 0;
						while($q = $sqls->fetch_array(MYSQLI_ASSOC)){
							$i++;
							$total_keluar = $total_keluar + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td  style='text-align:right;'><?php echo  'Rp. '.number_format($q['jumlah'],0,'','.');?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					
					<?php 
						$sqls = $mysqli->query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, pengeluaran a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '4.%') GROUP BY p.id_akun");
						$total_keluar1 = 0;
						echo $mysqli->error;
						while($q = $sqls->fetch_array(MYSQLI_ASSOC)){
							$i++;
							$total_keluar1 = $total_keluar1 + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td  style='text-align:right;'><?php echo  'Rp. '.number_format($q['jumlah'],0,'','.');?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td  style='text-align:right;'><?php echo  'Rp. '.number_format(($total_keluar+$total_keluar1),0,'','.');?></td>
					</tr>
					<tr style='background:#DDD;'>
						<td></td>
						<td colspan='4'>SALDO KAS (I+II-III)</td>
						<td style='text-align:right;'><?php echo 'Rp. '.number_format(($saldo_bulan_lalu+$total_masuk-$total_keluar-$total_keluar1),0,'','.');?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 
}else{
	echo $_SESSION['error'];
}
?>