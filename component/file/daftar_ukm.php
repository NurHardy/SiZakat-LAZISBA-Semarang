<?php
	include "component/config/koneksi.php";
?>
	<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar UKM</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama UKM</th>
							<th>Penanggung Jawab</th>
							<th>Alamat</th>
							<th>No.Telepon</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($mysqli, "SELECT * FROM user WHERE level = '2'");
						$k=0;
						while($parse=mysqli_fetch_array($query)){
							$k++;
							echo "
								<tr class='grade'>
									<td width=\"100px\">$k</td>
									<td>$parse[nama]</td>
									<td>$parse[pj]</td>
									<td>$parse[alamat]</td>
									<td>$parse[hp]</td>
									<td>
										<a href='main.php?s=edit_ukm&id=$parse[id_user]' class='btn btn-info btn-mini'>Ubah</a>
									</td>
								</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
