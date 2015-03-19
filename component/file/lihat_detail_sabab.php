<?php
		include "component/config/koneksi.php";
		if(ISSET($_SESSION['iduser']) && ($_SESSION['level'] == 4)){
			$id = $_SESSION['iduser'];
		}else{
			$id = $_GET['id'];
		}
		$query = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$id'");
		$d= mysqli_fetch_array($query);
		$nama = $d['nama'];
		$tmp = $d['tempat_lahir'];
		$tgl = $d['tanggal_lahir'];
		$alamat = $d['alamat'];
		$kota = $d['kota'];
		$hp = $d['hp'];
		$email = $d['email'];
		
		$ket = nl2br($d['keterangan']);
		$pres = explode('<br />',$ket); 

?>

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
					<table>
						<tr>
							<th>Nama Lengkap</th>
							<td>:</td>
							<td><?php echo $nama?></td>
						</tr>
						<tr>
							<th>Tempat Lahir </th>
							<td>:</td>
							<td><?php echo $tmp?></td>
						</tr>
						<tr>
							<th>Tanggal Lahir </th>
							<td>:</td>
							<td><?php echo $tgl?></td>
						</tr>
						<tr>
							<th>Alamat</th>
							<td>:</td>
							<td><?php echo $alamat?></td>
						</tr>
						<tr>
							<th>Kota </th>
							<td>:</td>
							<td><?php echo $kota?></td>
						</tr>
						<tr>
							<th>No. HP</th>
							<td>:</td>
							<td><?php echo $hp?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td>:</td>
							<td><?php echo $email?></td>
						</tr>
					
						
					</table>
				</div>
				<div>
					<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th width="10px">No	</th>
							<th width="90px">Tanggal Prestasi</th>
							<th width="150px">Prestasi</th>
						</tr>
					</thead>
					
					<tbody>
					<?php 
						for($i=0;$i<count($pres);$i++){
							$dt = explode('|',$pres[$i]);
							echo "<tr><td>".($i+1)."</td><td>".$dt[0]."</td><td>".$dt[1]."</td></tr>";
						}
					?>
					
					</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>