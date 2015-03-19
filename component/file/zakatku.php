<!--daftarmustahik.php-->
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Zakatku</h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:10px;'>
				<p>Berikut daftar zakat yang telah dilakukan oleh <b><?php echo $_SESSION['username']; ?></b> : </p>
				
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>No Transaksi</th>
							<th>Jenis Transaksi</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$k=0;
							$jml=0;
							$sql = mysql_query("SELECT * FROM penerimaan p LEFT JOIN akun a ON p.id_akun=a.kode WHERE p.id_donatur = '$_SESSION[iduser]'");
							while($s = mysql_fetch_array($sql)){
								$k++;
								$jml = $jml + $s['jumlah'];
								echo "
									<tr>
										<td>$k.</td>
										<td>$s[tanggal]</td>
										<td>$s[id_penerimaan]</td>
										<td>$s[kode] - $s[namaakun]</td>
										<td>Rp ".number_format($s['jumlah'] , 0 , ',' , '.' )."</td>
									</tr>
								";
							}
						?>
					</tbody>
				</table>
				<h5>Total Zakat  : Rp <?php echo number_format($jml , 0 , ',' , '.' );?></h5>
			</div>
		</div>
	</div>
</div>