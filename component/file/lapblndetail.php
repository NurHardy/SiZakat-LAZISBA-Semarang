<?php 
	include "component/config/koneksi.php";

	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');

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
?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Detail Transaksi Untuk Bulan <?php echo $month1[$_GET['bln']]." ".$_GET['th'];?></h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
				<div class="widget-box">
                            <div class="widget-content tab-content">
                                <div id="tab1" class="tab-pane active">
									<table class='table table-bordered table-striped table-hover'>
									<thead>
										<tr>
											<th rowspan='2'>No</th>
											<th rowspan='2'>Tanggal</th>
											<th rowspan='2'>No Nota</th>
											<th colspan='5'>Penerimaan</th>
											<th colspan='4'>Pengeluaran</th>
											<th rowspan='2'>Saldo</th>
										</tr>
										<tr>
											<th>Keterangan</th>
											<th>Debet</th>
											<th>Nama Amil</th>
											<th>Alamat</th>
											<th>Nama Donatur</th>
											<th>Keterangan</th>
											<th>Kredit</th>
											<th>Keterangan Rinci</th>
											<th>Amil Yang Bertanggungjawab</th>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Saldo Akhir</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Rp <?php echo number_format($saldo_bulan_lalu, 2 , ',' , '.'); ?></td>
										</tr>
									</thead>
					<?php
						$queryLaporanKas = "SELECT * FROM (SELECT no_nota, tanggal, jumlah, keterangan, '1' AS tran, 'Nama Akun' AS namaakun, 'Donatur' AS nama, 'Alamat' AS alamat, 'Amiliin' AS amilin FROM penerimaan 
						UNION ALL 
						SELECT '', tanggal,  jumlah, keterangan, '0', (SELECT namaakun FROM akun WHERE penyaluran.id_akun = akun.kode), '', '', (SELECT nama FROM user WHERE penyaluran.id_teller=user.id_user) FROM penyaluran) AS laporan 
						WHERE ";
						$queryLaporanDebet = $queryLaporanKas."tran=1 AND ";
						$queryLaporanKredit = $queryLaporanKas."tran=0 AND ";
						$queryLaporanKas2 = sprintf("tanggal LIKE '%04d-%02d-__' ORDER BY tanggal", intval($_GET['th']), intval($_GET['bln']));
						
						$queryLaporanDebet .= $queryLaporanKas2;
						$queryLaporanKredit .= $queryLaporanKas2;
						$queryLaporanKas .= $queryLaporanKas2;
						
						//echo $queryLaporanKas;
						$sql = mysqli_query($mysqli, $queryLaporanKas);
						if (!$sql) echo $mysqli->error;
						$i = $totalMasuk = 0;
						$selisih_saldo = $saldo_bulan_lalu;
						while($f = mysqli_fetch_array($sql)){
							$i++;
							//$totalMasuk  = $totalMasuk + $f['jumlah'];
							$tanggal = explode('-',$f['tanggal']);
							$tanggal = $tanggal[2].' '.$month1[$tanggal[1]].' '.$tanggal[0];
							$selisih_saldo = (($f['tran'] == 1) ? ($selisih_saldo + $f['jumlah']) : ($selisih_saldo - $f['jumlah']));
							$saldo = number_format($selisih_saldo, 2 , ',' , '.');
							$normal_jumlah = number_format($f['jumlah'], 2 , ',' , '.');
							echo "<tr>";
								echo "<td>$i</td>";
								echo "<td>$f[tanggal]</td>";
								echo "<td>$f[no_nota]</td>";
								echo "<td>".($f['tran'] == 1 ? $f['namaakun'] : '')."</td>";
								echo "<td>".($f['tran'] == 1 ? "Rp ".$normal_jumlah : '')."</td>";
								echo "<td>".($f['tran'] == 1 ? $f['amilin'] : '')."</td>";
								echo "<td>$f[alamat]</td>";
								echo "<td>$f[nama]</td>";
								echo "<td>".($f['tran'] == 0 ? $f['namaakun'] : '')."</td>";
								echo "<td>".($f['tran'] == 0 ? "Rp ".$normal_jumlah : '')."</td>";
								echo "<td>$f[keterangan]</td>";
								echo "<td>".($f['tran'] == 0 ? $f['amilin'] : '')."</td>";
								echo "<td>Rp $saldo</td>";
								
							echo "</tr>";
						}
					?>
					<!--<tr>
						<td colspan='4'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td align='right'><?php //echo ''.number_format($totalMasuk , 0 , ',' , '.' ); ?></td>
					</tr>-->
									</table>
								
								
								</div>
                                <!--<div id="tab2" class="tab-pane"> 
								<table class='table table-bordered table-striped table-hover'>
					<table class='table table-bordered table-striped table-hover'>
									<thead>
										<tr>
											<th width='5%'>No</th>
											<th width='15%'>Tanggal</th>
											<th width='18%'>Jenis Transaksi</th>
											<th width='29%'>Keterangan</th>
											<th width='15%'>Jumlah (Rp)</th>
										</tr>
									</thead> -->
 					<?php
						//hitung total penerimaan (debet)
 						$sql2 = mysqli_query($mysqli, $queryLaporanDebet);
						$i = $totalMasuk = 0;
						while($f = mysqli_fetch_array($sql2)){
							$i++;
							$totalMasuk  = $totalMasuk + $f['jumlah'];
						} 
						
						// hitung total penyaluran (kredit)
						$sql3 = mysqli_query($mysqli, $queryLaporanKredit);
						$i = $totalKeluar = 0;
						while($f = mysqli_fetch_array($sql3)){
							$i++;
							$totalKeluar  = $totalKeluar + $f['jumlah'];
						}
/*							$tanggal = explode('-',$f['tanggal']);
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
								echo "<td align='right'>".number_format($f['jumlah'] , 0 , ',' , '.' )."</td>";
								
							echo "</tr>"; */
						 
					?> 
					<!--<tr>
						<td colspan='3'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td align='right'><?php /* echo ''.number_format($totalKeluar , 0 , ',' , '.' ); */?></td>
					</tr>
				</table>
								</div> -->
                            </div>                            
                </div>
			</div>
			
			<h5 style='margin-left:10px;' >Penerimaan sebesar : Rp <?php echo number_format($totalMasuk, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo number_format($totalKeluar, 0 , ',' , '.' ); ?></h5>
		</div>
	</div>
</div>