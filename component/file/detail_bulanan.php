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
							<th>Muzakki</th>
							<th>Jenis Transaksi</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						$query = mysqli_query($mysqli, "SELECT p.*, m.nama as namamuzakki, a.namaakun FROM penerimaan p left join user m ON p.id_donatur = m.id_user left join akun a on p.id_akun = a.kode WHERE p.tanggal like '%-$_GET[bulan]-%'");
						while($parse=mysqli_fetch_array($query)){
							echo "<tr class='grade'>
								<td>$parse[id_penerimaan]</td>
								<td>$parse[tanggal]</td>
								<td>$parse[keterangan]</td>
								<td>$parse[namamuzakki]</td>
								<td>$parse[id_akun] - $parse[namaakun]</td>
								<td>".number_format($parse['jumlah'],0,',','.')."</td>
								
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
