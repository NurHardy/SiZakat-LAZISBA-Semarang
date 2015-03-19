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

<div class="col-12">
	<?php
		$wilayah = $_GET['wilayah'];
	echo "<a href=\"component/server/get_pdf.php?content=cetak_detail_bus_wilayah&wilayah=$wilayah \" target='_BLANK_' class=\"btn btn-info btn-mini\" style=\"margin-top:10px; margin-bottom:-10px;\">Cetak Daftar Peserta BUS </a>";

	?>
		
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Peserta BUS</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
					<tr>
						<th>No</th>
						<th>foto</th>
						<th>Nama</th>
						<th>Nama Ayah</th>
						<th>Nama Ibu</th>
						<th>Tanggal Lahir</th>
						<th>Alamat</th>
						<th>Wilayah</th>
						<th>Jenjang</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						$wilayah = $_GET['wilayah'];
						$query = mysql_query("select * from penerima_bus WHERE wilayah = '$wilayah' ");
						$i = 1;
						
						
						while($pecah = mysql_fetch_array($query)){
						if($pecah['jenjang'] == 1){
							$j = "SD";
						}else if($pecah['jenjang'] == 2){
							$j = "SMP";
						}else if($pecah['jenjang'] == 3){
							$j = "SMA";
						}
						echo"	
							<tr class=\"gradeA\">
						
							<td>$i</td>
							<td><img src='img/bus/$pecah[foto]' width=\"100px\" height=\"100px\"/></td>
							<td>$pecah[nama]</td>
							<td>$pecah[ayah]</td>
							<td>$pecah[ibu]</td>
							<td>$pecah[tanggal_lahir]</td>
							<td>$pecah[alamat]</td>
							<td>$pecah[wilayah]</td>
							<td>$j</td>
							
						</tr>";
						$i++;
						}
					?>
					</tbody>
					</table>  
			</div>
		</div>
	</div>
