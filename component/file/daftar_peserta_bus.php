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
						<th>Nama</th>
						<th>Nama Ayah</th>
						<th>Nama Ibu</th>
						<th>Tanggal Lahir</th>
						<th>Alamat</th>
						<th>Wilayah</th>
						<th>Jenjang</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						$query = mysqli_query($mysqli, "select * from penerima_bus");
						$i = 1;
						
						
						while($pecah = mysqli_fetch_array($query)){
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
							<td>$pecah[nama]</td>
							<td>$pecah[ayah]</td>
							<td>$pecah[ibu]</td>
							<td>$pecah[tanggal_lahir]</td>
							<td>$pecah[alamat]</td>
							<td>$pecah[wilayah]</td>
							<td>$j</td>
							<td align=\"center\"><a href=\"main.php?s=detailbus&id=$pecah[id_penerima]\" class=\"btn btn-info btn-mini\">Detail Peserta</a> | 
							<a href=\"component/server/get_pdf.php?content=cetak_detail_bus&id=$pecah[id_penerima]\" target='_BLANK_' class=\"btn btn-info btn-mini\">Cetak Detail </a></td>
							
						
						</tr>";
						$i++;
						}
					?>
					</tbody>
					</table>  
			</div>
		</div>
	</div>
