<?php
	include "../../config/koneksi.php";
?>

	<div class="col-12"><br/>
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Detail Bulanan</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="standard-table">

				<tr>
					<th>No</th>
					<th>Tanggal</th>
					<th>Mustahik</th>
					<th>Disalurkan Kepada</th>
					<th>Jumlah</th>
				</tr>

				<?php
					$query = $mysqli->query("SELECT * FROM penyaluran WHERE tanggal like '%-$_GET[bulan]-%'");
					$i = 1;
					while($parse = $query->fetch_array(MYSQLI_ASSOC)){
						$q2 = $mysqli->query("SELECT * FROM mustahik WHERE IdMustahik = '$parse[idmustahik]'");
						$q3 = $mysqli->query("SELECT * FROM akun WHERE kode = '$parse[idakun]'");
						$p2 = $q2->fetch_array(MYSQLI_ASSOC);
						$p3 = $q3->fetch_array(MYSQLI_ASSOC);
						echo "<tr class='grade'>
							<td>".$i."</td>
							<td>".$parse['tanggal']."</td>
							<td>".$p2['Nama']."</td>
							<td>".$p3['namaakun']."</td>
							<td>".$parse['jumlah']."</td>
						</tr>";
						$i++;
					}
				?>
			</table>
				
			</div>
		</div>
	</div>
