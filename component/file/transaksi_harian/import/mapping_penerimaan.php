<?php
$colName = $_GET ['col'];

if ($colName == 'akun') {
	$queryGetMapSource =
		"SELECT ket_akun, COUNT(ket_akun) AS jumlah FROM stage_penerimaan " .
	"WHERE kode_akun='0' GROUP BY ket_akun ";
	
	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else if ($colName == 'donatur') {
	$queryGetMapSource =
		"SELECT nama_donatur AS ket_akun, COUNT(nama_donatur) AS jumlah FROM stage_penerimaan " .
		"WHERE id_donatur='0' GROUP BY nama_donatur ";
	
	$resultGetMapSource = mysqli_query ( $mysqli, $queryGetMapSource );
	if ($resultGetMapSource == null) {
		echo mysqli_error ( $mysqli );
	}
} else {
	return;
}

?>
<div class="col-md-6">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon"> <i class="glyphicon glyphicon-log-in"></i>
			</span>
			<h5>Mapping Jenis Akun Penerimaan</h5>
		</div>
		<div class="widget-content">
			<table
				class="table table-bordered table-striped table-hover siz-operation-table">
				<thead>
					<tr>
						<th>Keterangan Akun</th>
						<th>Tujuan Mapping Akun</th>
						<th>Jumlah Transaksi</th>
					</tr>
				</thead>
				<tbody>
<?php
$jmlTrx = 0;
while ( $rowMapSource = mysqli_fetch_assoc ( $resultGetMapSource ) ) {
	echo "
		<tr>
			<td>" . htmlspecialchars ( $rowMapSource ['ket_akun'] ) . "</td>		
			<td><div><a href='#'>- Pilih -</a></div></td>
			<td>" . $rowMapSource ['jumlah'] . "</td>
		</tr>
		";
	$jmlTrx+= $rowMapSource ['jumlah'];
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2'>Jumlah Transaksi:</td>
						<td><b><?php echo $jmlTrx; ?></b></td>
					</tr>
				</tfoot>
			</table>
			<div>
				<a href="javascript:history.back();">
					<span class="glyphicon glyphicon-chevron-left"></span> Kembali</a> - 
				<button type="submit" name="siz_submit_btn" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-saved"></span> Simpan Mapping
				</button>
			</div>
		</div>
	</div>
</div>
