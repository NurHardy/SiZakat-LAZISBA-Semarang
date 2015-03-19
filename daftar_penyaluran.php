<?php
	include "component/config/koneksi.php";
	$month = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
?>
<script>
function linkTo(link){
	window.location = link;

}

</script>
<div class="col-12"><br/>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Daftar Penyaluran</h5>
		</div>
		<div class="widget-content nopadding">
			<table class="standard-table">

				<tr>
					<th>Bulan</th>
					<?php
						$query = $mysqli->query("SELECT * FROM akun WHERE idParent = '$_GET[q]'");
						while($parse = $query->fetch_array(MYSQLI_ASSOC)) {
							echo "<th>".$parse['namaakun']."</th>";
						}
					?>
				</tr>
				<?php
						foreach($month as $m => $val){
							//echo $m."-".$val."<br />";
							echo "<tr onclick=\"linkTo('index.php?s=detail_penyaluran&bulan=".$m."')\" style='cursor:pointer;'>
									<td>".$val."</td>
								";
								
							$query = $mysqli->query("SELECT * FROM akun WHERE idParent = '".$_GET['q']."'");
							while($parse = $query->fetch_array(MYSQLI_ASSOC)){
								$q2 = $mysqli->query("SELECT SUM(jumlah) AS jumlah FROM penyaluran WHERE idakun LIKE '".$parse['kode']."%' AND tanggal LIKE '%-".$m."-%'");
								$p2 = $q2->fetch_array(MYSQLI_ASSOC);
								echo "<td>".$p2['jumlah']."</td>";
							}
							echo "</tr>";
							
						}
					?>
			</table>
		</div>
	</div>
</div>