<?php 
	include "../config/koneksi.php";
	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: attachment; filename=Laporan_Transaksi_Akun_".$month1[$_GET['bln']]."_$_GET[th].xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$sql = mysql_query("SELECT DISTINCT a.namaakun, p.id_akun FROM penerimaan p, akun a WHERE p.id_akun=a.kode AND MONTH(tanggal) = '$_GET[bln]' AND YEAR(tanggal) = '$_GET[th]'");
	
?>

<h3>Laporan Transaksi Per Akun Bulan <?php echo $month1[$_GET['bln']]." $_GET[th]";?></h3>

<?php 
	while($d = mysql_fetch_array($sql)){
		
?>
<h4><?php echo $d['namaakun'];?></h4>
<table border='1'>
	<tr>
		<th>Tanggal</th>
		<th>No. Nota</th>
		<th>Muzakki</th>
		<th>Nominal</th>
		<th>PJ</th>
	</tr>
	<?php 
		$sql1 = mysql_query("SELECT p.tanggal, p.no_nota, u.nama as donatur, p.jumlah, v.nama as pj
							FROM penerimaan p 
							LEFT JOIN user u 
							ON p.id_donatur = u.id_user
							LEFT JOIN user v
							ON p.id_donatur = v.id_user
							WHERE  p.id_akun='$d[id_akun]' 
							AND MONTH(p.tanggal) = '$_GET[bln]' 
							AND YEAR(p.tanggal) = '$_GET[th]'");
		$total = 0;
		while($r = mysql_fetch_array($sql1)){
			echo "<tr>
					<td>$r[tanggal]</td>
					<td>$r[no_nota]</td>
					<td>$r[donatur]</td>
					<td>$r[jumlah]</td>
					<td>$r[pj]</td>
				</tr>";
				$total = $total +$r['jumlah'];
		};
	?>
	<tr>
		<td colspan='2'>Total</td>
		<td>:</td>
		<td><?php echo $total;?></td>
		<td></td>
	</tr>
</table>

<?php }?>