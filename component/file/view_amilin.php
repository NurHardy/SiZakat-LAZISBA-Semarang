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
				<h5>Daftar Amilin</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
					<tr>
						<th>No</th>
						
						<th>Nama</th>
						<th>Alamat</th>
						<th>No.Hp</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						$query = mysqli_query($mysqli, "select * from user WHERE level = 99 or level = 3");
						$i = 1;
						while($pecah = mysqli_fetch_array($query)){
						echo"	
							<tr class=\"gradeA\">
						
							<td>$i</td>
							<td>$pecah[nama]</td>
							<td>$pecah[alamat]</td>
							<td>$pecah[hp]</td>
							<td>$pecah[email]</td>
							<td align=\"center\"><a href=\"main.php?s=edit_amilin&id=$pecah[id_user]\" class=\"btn btn-info btn-mini\">Ubah</a> </td>
							
						
						</tr>";
						$i++;
						}
					?>
					</tbody>
					</table>  
			</div>
		</div>
	</div>

