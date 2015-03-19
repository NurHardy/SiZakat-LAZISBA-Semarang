<script type="text/javascript">
	function konfirmasi(){
		var pilihan = confirm("are you sure to delete this record ?");
		if(pilihan){
			return true;
		}else{
			return false;
		}
	}
</script>
<script>
function linkTo(link){
	window.location = link;
}
</script>

<div class="col-12">

		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Penerima Zakat Wilayah</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Tanggal Lahir</th>
						<th>Jenjang</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						$query = mysql_query("select * from user WHERE id_user='$_SESSION[iduser]'");
						$d = mysql_fetch_array($query);
						$query = mysql_query("select * from penerima_bus WHERE wilayah='$d[wilayah_bus]' AND is_off='0'");
						$i = 1;
						$sd = $smp = $sma = 0;
						while($pecah = mysql_fetch_array($query)){
						echo"	
							<tr class=\"gradeA\" onclick=linkTo('main.php?s=detailbus&id=$pecah[id_penerima]')>
						
							<td>$i</td>
							<td>$pecah[nama]</td>
							<td>$pecah[alamat]</td>
							<td>$pecah[tanggal_lahir]</td>
							<td>";
							if($pecah['jenjang'] == '1'){
								echo "SD";
								$sd++;
							}elseif($pecah['jenjang'] == '2'){
								echo "SMP";
								$smp++;
							}elseif($pecah['jenjang'] == '3'){
								echo "SMA";
								$sma++;
							}
							
							echo "</td>
							<td align=\"center\">
							<a href=\"main.php?s=editbus&id=$pecah[id_penerima]\" class=\"btn btn-info btn-mini\">Ubah</a> 
							<a href=\"component/server/hapusbus.php?id=$pecah[id_penerima]\" class=\"btn btn-warning btn-mini\">Hapus</a> 
							</td>
							
						
						</tr>";
						$i++;
						}
					?>
					</tbody>
					</table>  
			</div>
		</div>
		<br />
		<br />
		<h4>Jumlah Penerima</h4>
		<table style='width:50%;'>
			<tr>
				<td style='width:30%'>SD</td>
				<td>: <?php echo $sd; ?> Siswa</td>
			</tr>
			<tr>
				<td>SMP</td>
				<td>: <?php echo $smp; ?> Siswa</td>
			</tr>
			<tr>
				<td>SMA</td>
				<td>: <?php echo $sma; ?> Siswa</td>
			</tr>
			<tr>
				<td>TOTAL</td>
				<td>: <?php echo ($sd+$smp+$sma); ?> Siswa</td>
			</tr>
		</table>
	</div>

