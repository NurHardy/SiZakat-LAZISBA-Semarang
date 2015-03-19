	<div class="col-12"><br/>
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Informasi</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Judul</th>
							<th>Oleh</th>
							<th>Tanggal</th>
							<th>Jenis</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						
						include "component/config/koneksi.php";
		
						$query = mysqli_query($mysqli, "SELECT *, CASE  
												WHEN Status = 1 THEN 'Tentang Lazisba' 
												WHEN Status = 2 THEN 'Artikel/Berita'
												WHEN Status = 3 THEN 'Event/Kegiatan'
												ELSE '-'
												END AS statusstr 
												FROM informasi");
												$k=0;
						while($parse=mysqli_fetch_array($query)){
						$k++;
							echo "<tr class='grade'>
								<td>$k.</td>
								<td>$parse[judul]</td>
								<td>Admin</td>
								<td>$parse[tanggal]</td>
								<td>".(($parse['statusstr']))."</td>
								<td><a href='main.php?s=edit_info&id=$parse[id_informasi]' class='btn btn-info btn-mini'>Ubah</a>
								<a href='main.php?s=hapus_info&id=$parse[id_informasi]' onClick=\"return confirm('Anda yakin mau menghapus informasi ini ?')\" class='btn btn-danger btn-mini'>Hapus</a></td>
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
