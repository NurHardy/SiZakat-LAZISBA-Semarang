<?php
	include "component/config/koneksi.php";
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
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
			<h5>Daftar Penerimaan</h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:20px;' class='col-5'>
			<table>
				<tr><td>Tahun </td><td>:&nbsp;</td><td><?php echo $_GET['th']; ?></td></tr>
				<tr>
				<td>Pilih Tahun</td><td>:&nbsp;</td>
				<td>
					<form method='GET' action='main.php'>
					<input type='hidden' name='s' value='<?php echo $_GET['s'];?>' />
					<input type='hidden' name='q' value='<?php echo $_GET['q'];?>' />
					<select name='th' data-placeholder='--Pilih Tahun--'>
						<option></option>
						<?php
							/*for($i=5;$i>0;$i--){
								if((date('Y')+$i) == $_GET['th']){
									$sel = 'selected=selected';
								}else{
									$sel ="";
								}
								echo "<option $sel>".(date('Y')+$i)."</option>";
							}*/
							for($i=0;$i<6;$i++){
								if((date('Y')-$i) == $_GET['th']){
									$sel = 'selected';
								}else{
									$sel ="";
								}
								
								echo "<option $sel>".(date('Y')-$i)."</option>";
							}
						?>
					</select>&nbsp;&nbsp;
					<button class='btn btn-info btn-small' type='submit'>&nbsp;&nbsp;Pilih&nbsp;&nbsp;</button>
				</td> 
				</tr>
			</table></div>
			<div class='col-6' style='padding:20px'>
				<table>
					<tr valign='top'>
						<td><div style='font-size:15px;font-weight:bolder;'>Total Penerimaan</div></td>
						<td>&nbsp;&nbsp;:</td>
						<td><div style='font-size:15px;font-weight:bolder;'><?php
							$ttl = mysql_query("SELECT SUM(jumlah) as jml FROM penerimaan WHERE tanggal LIKE '__-__-$_GET[th]'");
							$ttla = mysql_fetch_array($ttl);
							echo "Rp ".number_format($ttla['jml'],2,',','.');
						?></div></td>
					</tr>
					<tr valign='top'>
						<td><div style='font-size:15px;font-weight:bolder;'>Jumlah Transaksi Penerimaan</div></td>
						<td>&nbsp;&nbsp;:</td>
						<td><div style='font-size:15px;font-weight:bolder;'><?php
							$ttl = mysql_query("SELECT count(id_penerimaan) as jml FROM penerimaan WHERE tanggal LIKE '__-__-$_GET[th]'");
							$ttla = mysql_fetch_array($ttl);
							echo $ttla['jml'];
						?></div></td>
					</tr>
				</table>
			</div>
			<div class='clearfix clear'></div>
			<div style='width:100%;overflow-x:scroll;overflow-y:hidden;'>
			<table class="table table-bordered table-striped table-hover data-table">
				<thead>
					<tr>
						<th rowspan='2'>No</th>
						<th rowspan='2'>Transaksi</th>
						<th colspan='12'>Bulan</th>
					</tr>
					<tr>
						<?php 
							foreach($month as $m){
								echo "<th >".$m."</th>";
							}
							
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$q1 = mysql_query("SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
						$i=0;
						while($p = mysql_fetch_array($q1)){
							$i++;
							echo "<tr>";
								echo "<td >$i</td>";
								echo "<td >$p[namaakun]</td>";
								foreach($month as $m => $val){
									$sql = mysql_query("SELECT SUM(jumlah) as jumlah FROM penerimaan WHERE id_akun='$p[kode]' AND MID(tanggal,4,2) = '$m' AND MID(tanggal,7,4) = '$_GET[th]'");
									$d = mysql_fetch_array($sql);
									if($d['jumlah'] == ""){
										$jml = 0;
									}else{
										$jml = $d['jumlah'];
									}
									echo "<td>".$jml."</td>";
								}
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>