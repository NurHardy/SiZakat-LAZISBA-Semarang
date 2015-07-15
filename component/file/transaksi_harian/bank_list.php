<?php
/*
 * bank_list.php
 * ==> Tampilan tabel akun bank LAZISBA
 *
 * AM_SIZ_BANKLIST | List akun bank LAZISBA
 * ------------------------------------------------------------------------
 */

	$queryGetBank = "SELECT * FROM bank";
	$resultGetBank = mysqli_query($mysqli, $queryGetBank);
	$queryCount++;
?>
<div class="col-12">
	<div class="widget-box">
		<div class="box gradient">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Akun Bank</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th style="width: 200px;">Logo</th>
							<th>Nama</th><th>Saldo Awal</th><th>Saldo</th><th>Aksi</th>
						</tr>
					</thead>
					<tbody>
<?php 
	while ($bankAccountData = mysqli_fetch_assoc($resultGetBank)) {
		echo "
 			<tr>
 				<td><img class=\"siz_bank_logo\" src=\"".$bankAccountData['logo']."\" alt=\"Logo Bank\" /></td>
 				<td><div><b>".htmlspecialchars($bankAccountData['bank'])."</b></div>
					<div>".htmlspecialchars($bankAccountData['no_rekening'])."</div>
					<div>a.n. ".htmlspecialchars($bankAccountData['atas_nama'])."</div>
				</td>
				<td>".to_rupiah($bankAccountData['saldo_awal'])."</td>
				<td>".to_rupiah($bankAccountData['saldo_akhir'])."</td>
				<td><a href=\"main.php?s=transaksi&amp;action=edit-bank&amp;id=".$bankAccountData['id_bank']."\">
					<span class='glyphicon glyphicon-pencil'></span> Edit</a> | 
					<a href=\"main.php?s=transaksi&amp;action=detil-bank&amp;id=".$bankAccountData['id_bank']."\">
					<span class='glyphicon glyphicon-search'></span> Detil</a>
				</td>
 			</tr>
 		";
	}
?>
					</tbody>
				</table>
				<div style="padding:10px;">
					<a href="main.php?s=transaksi&amp;action=new-bank" class="btn btn-primary">
						<span class="glyphicon glyphicon-plus"></span> Tambah Akun Bank</a>
				</div>
			</div>
		</div>
	</div><!-- End widget box -->
</div>