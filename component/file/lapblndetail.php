<?php 
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
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
                            <div class="widget-title">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab1">Penerimaan</a></li>
                                    <li><a data-toggle="tab" href="#tab2">Penyaluran</a></li>
                                </ul>
                            </div>
                            <div class="widget-content tab-content">
                                <div id="tab1" class="tab-pane active">
									<table class='table table-bordered table-striped table-hover'>
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
						include "component/config/koneksi.php";
						
						$sql = mysqli_query($mysqli, "SELECT p.tanggal, p.id_akun, a.namaakun, p.jumlah, u.nama, p.keterangan
											FROM penerimaan p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode 
											LEFT JOIN user u
												ON p.id_donatur = u.id_user
											WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__'");
											echo "";
						$i = $totalMasuk = 0;
						while($f = mysqli_fetch_array($sql)){
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
								echo "<td align='right'>".number_format($f['jumlah'], 0 , ',' , '.' )."</td>";
								
							echo "</tr>";
						}
					?>
					<tr>
						<td colspan='4'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td align='right'><?php echo ''.number_format($totalMasuk , 0 , ',' , '.' ); ?></td>
					</tr>
				</table>
								
								
								</div>
                                <div id="tab2" class="tab-pane">
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
									</thead>
					<?php
						
						$sql = mysqli_query($mysqli, "SELECT p.tanggal, p.id_akun, a.namaakun as namaakun, p.jumlah, p.keterangan, l.namaakun as nama_akun
											FROM penyaluran p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode
											LEFT JOIN pengeluaran l
												ON p.id_akun = l.kode
											WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__'");
						$i = $totalKeluar = 0;
						while($f = mysqli_fetch_array($sql)){
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
								echo "<td align='right'>".number_format($f['jumlah'] , 0 , ',' , '.' )."</td>";
								
							echo "</tr>";
						}
					?>
					<tr>
						<td colspan='3'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td align='right'><?php echo ''.number_format($totalKeluar , 0 , ',' , '.' ); ?></td>
					</tr>
				</table>
								</div>
                            </div>                            
                        </div>
			</div>
			
			<h5 style='margin-left:10px;' >Penerimaan sebesar : Rp <?php echo number_format($totalMasuk, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo number_format($totalKeluar, 0 , ',' , '.' ); ?></h5>
		</div>
	</div>
</div>