<!--daftarmustahik.php-->
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Rekapitulasi Zakat <?php echo date('Y');?></h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
				<p>Berikut Rekapitulasi Penerimaan dan Penyaluran Zakat. Rekapitulasi ini diberikan untuk penerimaan dan penyaluran zakat periode <?php echo date('Y');?> (mulai 01 Januari <?php echo date('Y');?>).</p>
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
					<?php
						include "component/config/koneksi.php";
						$sql = mysql_query("SELECT * FROM akun WHERE jenis='2' AND idParent <> '0' AND idakun NOT IN (SELECT idParent FROM akun)");
						$k=0;
						$totalMasuk = 0;
						while ($d = mysql_fetch_array($sql)){
							$k++;
							$dd = mysql_query("SELECT sum(jumlah) as jumlah FROM penerimaan WHERE idakun='$d[kode]'");
							$ddq = mysql_fetch_array($dd);
							if($ddq['jumlah'] == ""){
								$jml = 0;
							}else{
								$jml = $ddq['jumlah'];
							}
							echo "<tr>
								<td width='5%'>$k.</td>
								<td width='8%'>$d[kode]</td>
								<td width='23%'>$d[namaakun]</td>
								<td width='5%'>:</td>
								<td>Rp ".number_format($jml , 0 , ',' , '.' )."</td>
							</tr>";
							$totalMasuk = $totalMasuk + $jml;
						}
					?>
					<tr>
						<td colspan='3'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td><?php echo 'Rp '.number_format($totalMasuk , 0 , ',' , '.' ); ?></td>
					</tr>
				</table>
								
								
								</div>
                                <div id="tab2" class="tab-pane">
								<table class='table table-bordered table-striped table-hover'>
					<?php
						include "component/config/koneksi.php";
						$sql = mysql_query("SELECT * FROM akun WHERE jenis='3' AND idParent <> '0' AND idakun NOT IN (SELECT idParent FROM akun)");
						$k=0;
						$totalKeluar = 0;
						while ($d = mysql_fetch_array($sql)){
							$k++;
							$dd = mysql_query("SELECT sum(jumlah) as jumlah FROM penyaluran WHERE idakun='$d[kode]'");
							$ddq = mysql_fetch_array($dd);
							if($ddq['jumlah'] == ""){
								$jml = 0;
							}else{
								$jml = $ddq['jumlah'];
							}
							echo "<tr>
								<td width='5%'>$k.</td>
								<td width='8%'>$d[kode]</td>
								<td width='23%'>$d[namaakun]</td>
								<td width='5%'>:</td>
								<td>Rp ".number_format($jml , 0 , ',' , '.' )."</td>
							</tr>";
							$totalKeluar = $totalKeluar + $jml;
						}
					?>
					<tr>
						<td colspan='3'><h5>Total</h5></td>
						<td width='5%'>:</td>
						<td><?php echo 'Rp '.number_format($totalKeluar , 0 , ',' , '.' ); ?></td>
					</tr>
				</table>
								</div>
                            </div>                            
                        </div>
			</div>
			
			<h5 style='margin-left:10px;' >Penerimaan sebesar : Rp <?php echo number_format($totalMasuk, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo number_format($totalKeluar, 0 , ',' , '.' ); ?></h5>
			<h4 style='margin-left:10px;margin-bottom:50px;' >Saldo s.d <?php echo date('d M y');?> sebesar : Rp <?php echo number_format($totalMasuk-$totalKeluar, 0 , ',' , '.' ); ?></h4>
		</div>
	</div>
</div>