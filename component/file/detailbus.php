<div class="col-12">
	<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="glyphicon glyphicon-th-list"></i>
		</span>
		<h5>Detail Data</h5>
		
	</div>
	<div class="widget-content">
			<div class="invoice-content">
				<div class="invoice-head">
					<h4><b>Daftar Penerima Zakat Wilayah</b></h4>
					<table style='width:50%;'>
						<?php
							$id = clear_injection($_GET['id']);
							$q1 = mysqli_query($mysqli, "SELECT * FROM penerima_bus WHERE id_penerima = '$id'");
							$p1 = mysqli_fetch_array($q1);
							$ket = nl2br($p1['keterangan']);
							$pres = explode('<br />',$ket); 
							if($p1['jenjang'] == '1'){
								$pend = "SD";
							}else if($p1['jenjang'] == '2'){
								$pend = "SMP";
							}else if($p1['jenjang'] == '3'){
								$pend = "SMA";
							}
						?>
						<tr>
							<td>Nama </td>
							<td>: <?php echo $p1['nama']; ?></td>
						</tr>
						
						<tr>
							<td>Tangal Lahir</td>
							<td>: <?php echo $p1['tanggal_lahir']; ?></td>
						</tr>
						
						<tr>
							<td>Pendidikan</td>
							<td>: <?php echo $pend ?></td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>: <?php echo $p1['alamat']; ?></td>
						</tr>
						<tr>
							<td>Hobi</td>
							<td>: <?php echo $p1['hobi']; ?></td>
						</tr>
						<tr>
							<td>Nama Ayah</td>
							<td>: <?php echo $p1['ayah']; ?></td>
						</tr>
						<tr>
							<td>Nama Ibu</td>
							<td>: <?php echo $p1['ibu']; ?></td>
						</tr>
						<tr>
							<td>Alamat Orang Tua</td>
							
							<td>: <?php echo $p1['alamatortu']; ?></td></tr>
						<tr>
							<td>Pekerjaan Ayah</td>
							<td>: <?php echo $p1['pekayah']; ?></td>
						</tr>
						<tr>
							<td>Pekerjaan Ibu</td>
							<td>: <?php echo $p1['pekibu']; ?></td>
						</tr>
						<tr>
							<td>Penghasilan</td>
							<td>: <?php echo $p1['penghasilan']; ?></td>
						</tr>
						<tr>
							<td>Status</td>
							<td>: <?php echo $p1['status']; ?></td>
						</tr>
					</table>
				</div>
				<div>
					<h4><b>Detail Prestasi</b></h4>
					<table class="table table-bordered table-striped table-hover data-table">
					<tr>
						<td colspan="3" align="center"><b>HAFALAN</b></td>
						<td colspan="3" align="center"><b>MINAT dan BAKAT</b></td>
						<td colspan="3" align="center"><b>RAPOR</b></td>
					
					</tr>
					
					<tr>
						<td width="10px"><b>No.</b></td>
						<td><b>Al-Qur'an</b></td>
						<td><b>Doa</b></td>
						
						
						<td width="10px"><b>No.</b></td>
						<td><b>Nama Lomba</b></td>
						<td><b>Keterangan</b></td>
						
						
						<td><b>Rata-Rata</b></td>
						<td><b>Peringkat</b></td>
					</tr>
					
					<tr>
						
					
					</tr>
						<?php 
							$id = clear_injection($_GET['id']);
							$q1 = mysqli_query($mysqli, "SELECT * FROM prestasi WHERE id_penerima = '$id'");
							$p1 = mysqli_fetch_array($q1);
							
							$a = nl2br($p1['alquran']);
							$a1 = explode('<br />',$a);
							
							$b = nl2br($p1['doa']);
							$b1 = explode('<br />',$b);
							
							$c = nl2br($p1['minat']);
							$c1 = explode('<br />',$c);
							
							
							$d = nl2br($p1['rapor']);
							$d1 = explode('<br />',$d);
							
							$max = 10;
							
							$max = max(array(count($a1),count($b1),count($c1),count($d1)));
							
							for($i=0;$i<$max;$i++){
							
								if(isset($a1[$i])){
									$aa = $a1[$i];
								}else{
									$aa = "";
								}
								
								if(isset($b1[$i])){
									$bb = $b1[$i];
								}else{
									$bb = "";
								}
								
								if(isset($c1[$i])){
								
									$c = explode('|',$c1[$i]);
									
									
										$cc1 = isset($c[0]);
									
									
										$cc2 = isset($c[1]);
									
									
								}else{
									$cc1 = "";
									$cc2 = "";
								}
								
								if(isset($d1[$i])){
								
									$d = explode('|',$d1[$i]);
									
										$dd1 = isset($d[0]);
								
										$dd2 = isset($d[1]);
									
									
								}else{
									$dd1 = "";
									$dd2 = "";
								}
								
								echo"<tr>
									<td>".($i+1)."</td>
									<td>".$aa."</td>
									<td >".$bb."</td>
									<td>".($i+1)."</td>
									<td>".$cc1."</td>
									<td>".$cc2."</td>
									<td>".$dd1."</td>
									<td>".$dd2."</td>
								
								</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>