<?php
	include "component/config/koneksi.php";
?>
	<div class="col-12"><br/>
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Detal Bulanan</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							
							<th>Keterangan</th>
							<th>Jenis Transaksi</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						$query = mysql_query("SELECT p.*, a.namaakun FROM penyaluran p left join akun a on p.id_akun = a.kode WHERE p.tanggal like '%-$_GET[bulan]-%'");
						while($parse=mysql_fetch_array($query)){
							echo "<tr class='grade'>
								<td>$parse[id_penyaluran]</td>
								<td>$parse[tanggal]</td>
								
								<td>$parse[keterangan]</td>
								<td>$parse[id_akun] - $parse[namaakun]</td>
								<td>".number_format($parse['jumlah'], 0, ',' , '.')."</td>
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
