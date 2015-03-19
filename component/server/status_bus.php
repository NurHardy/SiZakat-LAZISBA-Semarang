<?php
	include "component/config/koneksi.php";
?>
	<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Informasi Umum Bus</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th rowspan='2'>No</th>
							<th rowspan='2'>Wilayah</th>
							<th colspan='4'>Rekap Peserta</th>
							<th colspan='2'>Data Penyaluran</th>
						</tr>
						<tr>
							<th>SD</th>
							<th>SMP</th>
							<th>SMA</th>
							<th>Total</th>
							<th>1</th>
							<th>2</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$wil = mysqli_query($mysqli, "SELECT * from akun WHERE idParent='12'");
						$harga = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name='dana_bus_jenjang'");
						$val = mysqli_fetch_array($harga);
						
						$hr = explode('#',$val['value']);
						$k=0;
						while($w=mysqli_fetch_array($wil)){
							$k++;
							
							
							echo "<tr class='grade'>
								<td width=\"100px\">$k</td>
								<td>Bus Wilayah ".($k)."</td>";
								$biaya = 0;
								for($o=0;$o<3;$o++){
									$sql1 = mysqli_query($mysqli, "SELECT jenjang, count(jenjang) as jumlah_jenjang FROM penerima_bus WHERE wilayah='$k' AND jenjang='".($o+1)."' GROUP BY jenjang");
									$cd = mysqli_fetch_array($sql1);
									$biaya = $biaya + ($cd['jumlah_jenjang'] * $hr[$o]);
									if(mysqli_num_rows($sql1) > 0){
										echo "<td>$cd[jumlah_jenjang]</td>";
									}else{
										echo "<td></td>";
									}
								}
								echo "<td>$biaya</td>";
								
								$sqq = mysqli_query($mysqli, "SELECT * FROM penyaluran WHERE id_akun='$w[kode]' ORDER BY tanggal DESC LIMIT 0,2");
								
								$st = 1;
								$da = array("<td></td>","<td></td>");
								while($df = mysqli_fetch_array($sqq)){
									$da[$st] = "<td>$df[tanggal] / $df[jumlah]</td>";
									$st = $st-1;
								};
								
									for($x=0;$x<count($da);$x++){
										echo $da[$x];
									}
								
							
							echo "
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
