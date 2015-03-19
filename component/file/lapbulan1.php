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
		
		//ambil saldo awal
		$sqla = mysqli_query($mysqli, "SELECT SUM(saldo) as saldo FROM saldo_awal");
		$d = mysqli_fetch_array($sqla);
		$saldo_awal = $d['saldo']; //saldo awal;
		
		//ambil penerimaan
		$sqla = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE tanggal < '$th-$bln-01'");
		$d = mysqli_fetch_array($sqla);
		$penerimaan = $d['jumlah'];
		
		//ambil penyaluran
		$sqla = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE tanggal < '$th-$bln-01'");
		$d = mysqli_fetch_array($sqla);
		$penyaluran = $d['jumlah'];
		
		$saldo_bulan_lalu = ($saldo_awal + $penerimaan) - $penyaluran;
	}else{
		//jika tidak ada transaksi sebelum bulan yang dipilih
		$sqla = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name='bln_th_saldo'");
		$d = mysqli_fetch_array($sqla);
		$d = explode('#',$d['value']);
		if(($d[0] <= $bln) && ($d[1] <= $th)){
			//ambil saldo awal
			$sqla = mysqli_query($mysqli, "SELECT SUM(saldo) as saldo FROM saldo_awal");
			$d = mysqli_fetch_array($sqla);
			$saldo_bulan_lalu = $d['saldo']; //saldo awal;
		}else{
			$error = 1;
			$_SESSION['error'] = "Tidak Ada Transaksi untuk bulan ".$month1[$_GET['bln']]." - $_GET[th]";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=lapbulanan\">";
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
			<?php if($_SESSION['level']==99){?>
			<a href='component/file/lapblncetak.php?bln=<?php echo $_GET['bln']?>&th=<?php echo $_GET['th'];?>' class='btn btn-warning btn-mini'>Cetak Lap. Bulan <?php echo $month1[$_GET['bln']];?></a>
			<?php } ?>
				<a href='main.php?s=lapblndetail&bln=<?php echo $_GET['bln']?>&th=<?php echo $_GET['th'];?>' class='btn btn-info btn-mini'>Lap. Detail Bulan <?php echo $month1[$_GET['bln']];?></a>
			
			<?php if($_SESSION['level']==99){?>
				<a href='component/file/lapdetailcetak.php?bln=<?php echo $_GET['bln']?>&th=<?php echo $_GET['th'];?>' class='btn btn-info btn-mini'>Cetak Lap. Detail Bulan <?php echo $month1[$_GET['bln']];?></a>
			<?php }; ?>
				
				<br />
				<br />
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
						<td><?php echo $saldo_bulan_lalu;?></td>
						<td><?php echo $saldo_bulan_lalu;?></td>
					</tr>
					<tr>
						<td></td>
						<td>II</td>
						<td colspan='2'>Penerimaan</td>
						<td></td>
						<td></td>
					</tr>
					<?php 
						$sqls = mysqli_query($mysqli, "SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penerimaan p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND id_akun LIKE '1.%' GROUP BY p.id_akun");
						$i=0;
						$total_masuk = 0;
						while($q = mysqli_fetch_array($sqls)){
							$i++;
							$total_masuk = $total_masuk + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td><?php echo $q['jumlah']?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td><?php echo $total_masuk;?></td>
					</tr>
					
					<tr>
						<td></td>
						<td>III</td>
						<td colspan='2'>Pengeluaran</td>
						<td></td>
						<td></td>
					</tr>
					<?php 
						$sqls = mysqli_query($mysqli, "SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, akun a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '2.%') GROUP BY p.id_akun");
						$i=0;
						$total_keluar = 0;
						while($q = mysqli_fetch_array($sqls)){
							$i++;
							$total_keluar = $total_keluar + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td><?php echo $q['jumlah']?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					
					<?php 
						$sqls = mysqli_query($mysqli, "SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, pengeluaran a WHERE p.id_akun=a.kode AND tanggal LIKE '$th-$bln-__' AND (id_akun LIKE '4.%') GROUP BY p.id_akun");
						$total_keluar1 = 0;
						echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
						while($q = mysqli_fetch_array($sqls)){
							$i++;
							$total_keluar1 = $total_keluar1 + $q['jumlah'];
							
							?>
							<tr>
								<td></td>
								<td></td>
								<td style='width:30px;'><?php echo $i;?>.</td>
								<td><?php echo $q['namaakun']?></td>
								<td><?php echo $q['jumlah']?></td>
								<td></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td><?php echo ($total_keluar+$total_keluar1);?></td>
					</tr>
					<tr>
						<td></td>
						<td colspan='4'>SALDO KAS (I+II-III)</td>
						<td><?php echo ($saldo_bulan_lalu+$total_masuk-$total_keluar-$total_keluar1);?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 
}
?>