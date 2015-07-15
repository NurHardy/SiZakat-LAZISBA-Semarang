<?php

	
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	// Set origin form
	$_SESSION["siz_origin_url"] = $_SERVER["REQUEST_URI"];
	
?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Detail Transaksi Untuk Bulan <?php echo $month1[$_GET['bln']]." ".$_GET['th'];?></h5>
		</div>
		<div class="widget-content">
				<form action="main.php" method="GET">
				<input type='hidden' name='s' value='transaksi' />
				<div class="form-row control-group row-fluid form-group">
					<select name='bln'>
					<?php 
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
					foreach($month1 as $m => $v){
						echo "<option value='$m'>$v</option>";
					}
					?>
					</select>
					<select name='th'>
					<?php 
						$d = date('Y');
						for($i=0;$i<5;$i++){
							echo "<option value='".($d-$i)."'>".($d-$i)."</option>";
						}
					?>
				  </select>
				<input type='submit' value='Tampilkan' class="btn btn-default"/>
				  
			  </div>
			  
			  </form>
					 <a href="main.php?s=transaksi&amp;action=import">
					 	<span class="glyphicon glyphicon-open-file"></span> Impor data transaksi
					 </a>
				<div class="widget-box">  
                            <div class="widget-title">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab1">Penerimaan</a></li>
                                    <li><a data-toggle="tab" href="#tab2">Penyaluran</a></li>
                                </ul>
                            </div>
                            <div class="widget-content tab-content">
                                <div id="tab1" class="tab-pane active">
									<table class='table table-bordered table-striped table-hover data-table'>
									<thead>
										<tr>
											<th width='10%'>Tanggal</th>
											<th width='15%'>Jenis Transaksi</th>
											<th width='15%'>Terima Dari</th>
											<th>Keterangan</th>
											<th width='10%'>Jumlah (Rp)</th>
											<th width='5%'>Aksi</th>
										</tr>
									</thead>
									<tbody>
					<?php
						$sql = $mysqli->query("SELECT p.id_penerimaan, p.tanggal, p.id_akun, p.no_nota, a.idakun, a.namaakun, p.jumlah, u.id_user, u.nama, p.keterangan
											FROM penerimaan p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode 
											LEFT JOIN user u
												ON p.id_donatur = u.id_user
											WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__' ORDER BY tanggal");
						$i = $totalMasuk = 0;
						while($f = $sql->fetch_array(MYSQLI_ASSOC)){
							$i++;
							$totalMasuk  = $totalMasuk + $f['jumlah'];
							$tanggal = explode('-',$f['tanggal']);
							$tanggal = $tanggal[2].' '.$month1[$tanggal[1]].' '.$tanggal[0];
							
							echo "<tr>";
								echo "<td>$tanggal</td>";
								echo "<td><a href=\"main.php?s=akun&amp;action=detail&amp;id={$f['idakun']}\">{$f[id_akun]} {$f[namaakun]}</a>";
								if (!empty($f['no_nota'])) {
									echo "<br><div class=\"siz-desc-nota\">Nomor Nota : ".htmlspecialchars($f['no_nota'])."</div>";
								}
								echo "</td>";
								echo "<td><a href=\"main.php?s=donatur&amp;id=".$f['id_user']."\">";
								echo "<span class=\"glyphicon glyphicon-user\"></span> ".$f['nama']."</a></td>";
								echo "<td>$f[keterangan]</td>";
								echo "<td align='right'>".to_rupiah($f['jumlah'])."</td>";
								echo "<td><a href=\"main.php?s=transaksi&amp;action=edit-in&amp;id=".$f['id_penerimaan']."\" ";
								echo "class=\"btn btn-primary btn-xs\">";
								echo "<i class=\"glyphicon glyphicon-pencil\"></i> Edit</a> &nbsp;";
								echo "<a href=\"#\" class=\"btn btn-danger btn-xs\">Hapus</a></td>";
								
							echo "</tr>\n";
						}
					?>
										</tbody>
										<tr>
											<td colspan='3'><h5>Total</h5></td>
											<td width='5%'>:</td>
											<td align='right'><b><?php echo to_rupiah($totalMasuk); ?></b></td>
											<td>&nbsp;</td>
										</tr>
									</table>
								</div> <!-- End tab container Pemasukan -->
                                <div id="tab2" class="tab-pane">
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
						
						$sql = $mysqli->query("SELECT p.tanggal, p.id_akun, a.namaakun as namaakun, p.jumlah, p.keterangan, l.namaakun as nama_akun
											FROM penyaluran p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode
											LEFT JOIN pengeluaran l
												ON p.id_akun = l.kode
											WHERE tanggal LIKE '$_GET[th]-$_GET[bln]-__'");
						$i = $totalKeluar = 0;
						while($f = $sql->fetch_array(MYSQLI_ASSOC)){
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
								echo "<td align='right'>".to_rupiah($f['jumlah'])."</td>";
								
							echo "</tr>\n";
						}
					?>
										<tr>
											<td colspan='3'><h5>Total</h5></td>
											<td width='5%'>:</td>
											<td align='right'><?php echo ''.number_format($totalKeluar , 0 , ',' , '.' ); ?></td>
										</tr>
									</table>
								</div> <!-- End tab container Pengeluaran -->
                            </div> <!-- End tab box -->       
                </div> <!-- /widget-box -->
			
			<h5 style='margin-left:10px;' >Penerimaan sebesar : Rp <?php echo number_format($totalMasuk, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo number_format($totalKeluar, 0 , ',' , '.' ); ?></h5>
		</div>
	</div>
</div>