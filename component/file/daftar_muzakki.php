<?php
	include "component/config/koneksi.php";
?>
	<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Muzakki</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Lengkap</th>
							<th>Alamat</th>
							<th>Kota</th>
							<th>No. Hp</th>
							<th>Email</th>
							<th>Perusahaan</th>
							
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 1");
						$k=0;
						while($parse=mysqli_fetch_array($query)){
							$k++;
							echo "<tr class='grade'>
								<td width=\"100px\">$k</td>
								<td>$parse[nama]</td>
								<td>$parse[alamat]</td>
								<td>$parse[kota]</td>
								<td>$parse[hp]</td>
								<td>$parse[email]</td>
								<td>$parse[perusahaan]</td>
								<td>
								<a href='main.php?s=editmuzakki&id=$parse[id_user]' class='btn btn-info btn-mini'>Ubah</a>
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
