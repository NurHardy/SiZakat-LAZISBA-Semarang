<?php
	//session_start();
	include"component/config/koneksi.php";
	$action = "component/server/func_cicilan_kubah.php";
	require_once "component/libraries/injection.php";
?>
<script>
	function linkTo(link){
		window.location.href = link;
	}
</script>
<div class="col-12">
    <div class="widget-box">
        <div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="icon-book"></i>
					<span>Penyaluran Dana Kredit Usaha Barokah (KUBAH)</span>
				</h5>
			</div>
			
				<table class="table table-bordered table-striped table-hover data-table">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama UKM</th>
						<th>Total Dana</th>
						<th>Tanggal Penyerahan</th>
						<th>Sudah Dicicil</th>
						<th>Sisa</th>
					</tr>
				</thead>
				</tbody>
					<?php
						$q1 = mysqli_query($mysqli, "SELECT u.id_user, u.nama, SUM(p.jumlah) as jumlah
											FROM penyaluran p, user u 
											WHERE p.id_ukm = u.id_user AND th_kubah='$_GET[th]'
											GROUP BY u.id_user, u.nama");
						$i = 1;
						while($p1 = mysqli_fetch_array($q1)){
							$sq = mysqli_query($mysqli, "SELECT tanggal FROM penyaluran WHERE is_ukm='1' AND id_ukm='$p1[id_user]'  AND th_kubah='$_GET[th]' ORDER BY tanggal DESC");
							$tgl1 = mysqli_fetch_array($sq);
							$t = explode('-',$tgl1['tanggal']);
							$q2 = mysqli_query($mysqli, "SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE id_donatur = '$p1[id_user]' AND th_kubah='$_GET[th]'");
							$p2 = mysqli_fetch_array($q2);
							echo "
								<tr onClick='linkTo(\"main.php?s=transaksi_kubah_detail&th=$_GET[th]&id=$p1[id_user]\")' style='cursor:pointer;'>
									<td>$i</td>
									<td>$p1[nama]</td>
									<td>$p1[jumlah]</td>
									<td>$tgl1[tanggal]</td>
									<td>$p2[jumlah]</td>
									<td>".($p1['jumlah']-$p2['jumlah'])."</td>
								</tr>
							";
							$i++;
						}
					?>
					</tbody>
				</table>
				</div>
	</div>
</div>
</div>