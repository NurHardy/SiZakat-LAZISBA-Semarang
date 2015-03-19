<?php 
include "../config/koneksi.php";
$sql = mysql_query("SELECT * FROM opsi WHERE name = 'ramadhan' ");
$opsi_ramadhan = mysql_fetch_array($sql);

if($opsi_ramadhan['value'] != 1){
	echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
}else{

	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: attachment; filename=Laporan_Bulan_RAMADHAN_$_GET[th].xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo "<h3>Laporan Transaksi Bulan Ramadhan $_GET[th]</h3>
<br />
<br />";

	
	$th = $_GET['th'];
	
	$sql = mysql_query("SELECT * FROM penerimaan WHERE thn_ramadhan < '$th'");
	$sql1 = mysql_query("SELECT * FROM penyaluran WHERE thn_ramadhan < '$th'");
	$error = 0;
	if((mysql_num_rows($sql) > 0) || (mysql_num_rows($sql1) > 0)){
		//jika ada transaksi sebelum bulan yang dipilih
		
		//ambil saldo awal
		$sqla = mysql_query("SELECT SUM(saldo) as saldo FROM saldo_awal");
		$d = mysql_fetch_array($sqla);
		$saldo_awal = $d['saldo']; //saldo awal;
		
		//ambil penerimaan
		$sqla = mysql_query("SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE thn_ramadhan < '$th'");
		$d = mysql_fetch_array($sqla);
		$penerimaan = $d['jumlah'];
		
		//ambil penyaluran
		$sqla = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE thn_ramadhan < '$th'");
		$d = mysql_fetch_array($sqla);
		$penyaluran = $d['jumlah'];
		
		$saldo_bulan_lalu = ($saldo_awal + $penerimaan) - $penyaluran;
	}else{
		//jika tidak ada transaksi sebelum bulan yang dipilih
		$sqla = mysql_query("SELECT * FROM opsi WHERE name='bln_th_saldo'");
		$d = mysql_fetch_array($sqla);
		$d = explode('#',$d['value']);
		if(($d[0] <= $bln) && ($d[1] <= $th)){
			//ambil saldo awal
			$sqla = mysql_query("SELECT SUM(saldo) as saldo FROM saldo_awal");
			$d = mysql_fetch_array($sqla);
			$saldo_bulan_lalu = $d['saldo']; //saldo awal;
		}else{
			$error = 1;
			$_SESSION['error'] = "Tidak Ada Transaksi untuk bulan Ramadhan - $_GET[th]";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=lapbulanan_ramadhan\">";
		}
		
	}
	
?>

<table class="table table-bordered table-hover" border='1'>
					<tr>
						<th style='width:30px;'>A.</th>
						<th colspan='3'>KAS</th>
						<th>Jumlah</th>
						<th>Total</th>
					</tr>
					<tr>
						<td></td>
						<td>II</td>
						<td colspan='2'>Penerimaan</td>
						<td></td>
						<td></td>
					</tr>
					<?php 
						$sqls = mysql_query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penerimaan p, akun a WHERE p.id_akun=a.kode AND thn_ramadhan LIKE '$th' AND id_akun LIKE '3.1%' GROUP BY p.id_akun");
						$i=0;
						$total_masuk = 0;
						while($q = mysql_fetch_array($sqls)){
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
						$sqls = mysql_query("SELECT p.id_akun,a.namaakun,SUM(p.jumlah) as jumlah FROM penyaluran p, akun a WHERE p.id_akun=a.kode AND thn_ramadhan LIKE '$th' AND id_akun LIKE '3.2%' GROUP BY p.id_akun");
						$i=0;
						$total_keluar = 0;
						while($q = mysql_fetch_array($sqls)){
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
					<tr>
						<td></td>
						<td></td>
						<td colspan='3'>Total</td>
						<td><?php echo ($total_keluar);?></td>
					</tr>
					<tr>
						<td></td>
						<td colspan='4'>SALDO KAS (I+II-III)</td>
						<td><?php echo ($total_masuk-$total_keluar);?></td>
					</tr>
				</table>
				
				<?php } ?>