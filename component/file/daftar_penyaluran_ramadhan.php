<?php
	include "component/config/koneksi.php";
$sql = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name = 'ramadhan' ");
$opsi_ramadhan = mysqli_fetch_array($sql);

if($opsi_ramadhan['value'] != 1){
	echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
}else{
?>
	<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Penyaluran Ramadhan</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Amilin</th>
							<th>Jumlah</th>
							<th>Akun</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($mysqli, "SELECT * FROM penyaluran WHERE is_ramadhan = 1");
						$k=0;
						while($parse=mysqli_fetch_array($query)){
							$k++;
							
							$sql2 = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$parse[id_teller]' ");
							$s2 = mysqli_fetch_array($sql2);
							
							$sql3 = mysqli_query($mysqli, "SELECT * FROM akun WHERE kode = '$parse[id_akun]'");
							$s3 = mysqli_fetch_array($sql3);
							
							echo "<tr class='grade'>
								<td width=\"100px\">$k</td>
								<td>$parse[tanggal]</td>
								<td>$s2[nama]</td>
								<td>$parse[jumlah]</td>
								<td>$s3[namaakun]</td>
								<td>
								<a href='main.php?s=edit_penyaluran_ramadhan&id=$parse[id_penyaluran]' class='btn btn-info btn-mini'>Ubah</a>
							</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php } ?>