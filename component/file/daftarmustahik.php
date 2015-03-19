<!--daftarmustahik.php-->
<div class="col-12">
<div class="widget-box">
	<div class="widget-title">
		<span class="icon">
			<i class="glyphicon glyphicon-th"></i>
		</span>
		<h5>Daftar Mustahik</h5>
	</div>
	<div class="widget-content nopadding">
		<table class="table table-bordered table-striped table-hover data-table">
			<thead>
				<tr>
					<th width='7%'>No</th>
					<th width='13%'>Nama</th>
					<th width='10%'>Tempat,Tanggal Lahir</th>
					<th width='15%'>Alamat</th>
					<th width='8%'>Kota</th>
					<th width='7%'>Telepon</th>
					<th width='7%'>Handphone</th>
					<th width='8%'>Email</th>
					<th width='13%'>Pekerjaan</th>
					<th width='35%'>Penghasilan</th>
					<th width='10%'>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$sql = mysqli_query($mysqli, "SELECT * FROM mustahik");
					$k=0;
					while($d = mysqli_fetch_array($sql)){
					$k++;
				?>
				<tr>
					<td><?php echo  $k;?></td>
					<td><?php echo  $d['Nama'];?></td>
					<td><?php echo  $d['Tmp_Lahir'].",".$d['Tgl_Lahir'];?></td>
					<td><?php echo $d['Alamat'];?></td>
					<td><?php echo $d['Kota'];?></td>
					<td><?php echo $d['Telepon'];?></td>
					<td><?php echo $d['Hp'];?></td>
					<td><?php echo $d['Email'];?></td>
					<td><?php echo $d['Pekerjaan'];?></td>
					<td><?php echo $d['Penghasilan'];?></td>
					<td><a href='main.php?s=editmustahik&id=<?php echo $d['IdMustahik'];?>' class="btn btn-info btn-mini">Ubah</a></td>
				</tr>
				<?php };?>
			</tbody>
		</table>  
	</div>
</div>
</div>