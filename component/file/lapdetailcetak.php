<?php 
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: attachment; filename=Laporan_Detail_Bulan_".$month1[$_GET['bln']]."_$_GET[th].xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<h4><center>DETAIL LAPORAN TRANSAKSI BULAN <?php echo $month1[$_GET['bln']]." $_GET[th]";?></center> </h4>
<table class='table table-bordered table-striped table-hover' border='1'>
<thead>
	<tr>
		<th width='5%'>No</th>
		<th width='15%'>Tanggal</th>
		<th width='18%'>Jenis Transaksi</th>
		<th width='18%'>Terima Dari</th>
		<th width='29%'>Keterangan</th>
		<th width='15%'>Jumlah (Rp)</th>
	</tr>
</thead>
	<?php
		include "../config/koneksi.php";
		
		$sql = mysql_query("SELECT p.tanggal, p.id_akun, a.namaakun, p.jumlah, u.nama, p.keterangan
							FROM penerimaan p 
							LEFT JOIN akun a 
								ON p.id_akun = a.kode 
							LEFT JOIN user u
								ON p.id_donatur = u.id_user
							WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__'");
		$i = $totalMasuk = 0;
		while($f = mysql_fetch_array($sql)){
			$i++;
			$totalMasuk  = $totalMasuk + $f['jumlah'];
			$tanggal = explode('-',$f['tanggal']);
			$tanggal = $tanggal[2].' '.$month1[$tanggal[1]].' '.$tanggal[0];
			echo "<tr>";
				echo "<td>$i</td>";
				echo "<td>$tanggal</td>";
				echo "<td>$f[id_akun] $f[namaakun]</td>";
				echo "<td>$f[nama]</td>";
				echo "<td>$f[keterangan]</td>";
				echo "<td align='right'>".$f['jumlah']."</td>";
				
			echo "</tr>";
		}
	?>
	<tr>
		<td colspan='4'><h5>Total</h5></td>
		<td width='5%'>:</td>
		<td align='right'><?php echo ''.$totalMasuk; ?></td>
	</tr>
</table>
	<br />
	<br />
	<br />
	<h5>Penyaluran</h5>
	<table class='table table-bordered table-striped table-hover' border='1'>
					<thead>
						<tr>
							<th width='5%'>No</th>
							<th width='15%'>Tanggal</th>
							<th width='18%'>Jenis Transaksi</th>
							<th width='29%'>Keterangan</th>
							<th width='15%'>Jumlah (Rp)</th>
						</tr>
					</thead>
	<?php
						
						$sql = mysql_query("SELECT p.tanggal, p.id_akun, a.namaakun as namaakun, p.jumlah, p.keterangan, l.namaakun as nama_akun
											FROM penyaluran p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode
											LEFT JOIN pengeluaran l
												ON p.id_akun = l.kode
											WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__'");
						$i = $totalKeluar = 0;
						while($f = mysql_fetch_array($sql)){
							$i++;
							$totalKeluar  = $totalKeluar + $f['jumlah'];
							$tanggal = explode('-',$f['tanggal']);
							$tanggal = $tanggal[2].' '.$month1[$tanggal[1]].' '.$tanggal[0];
							
							if($f['namaakun'] == ""){
								$xx = $f['nama_akun'];
							}else{
								$xx = $f['namaakun'];
							}
							
							echo "<tr>";
								echo "<td>$i</td>";
								echo "<td>$tanggal</td>";
								echo "<td>$f[id_akun] $xx</td>";
								echo "<td>$f[keterangan]</td>";
								echo "<td align='right'>".$f['jumlah']."</td>";
								
							echo "</tr>";
						}
					?>
					<tr>
						<td colspan='3'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td align='right'><?php echo ''.$totalKeluar; ?></td>
					</tr>
				</table>
			<h5 style='margin-left:10px;' >Penerimaan sebesar : Rp <?php echo $totalMasuk; ?></h5>
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo $totalKeluar; ?></h5>