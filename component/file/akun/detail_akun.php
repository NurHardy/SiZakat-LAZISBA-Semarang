<?php
	$showContent = false;
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
	
	$listJenis = array("Unknown","Akun Penerimaan","Akun Pengeluaran","Akun Ramadhan","Akun Lain-lain");
	if(isset($_GET['id']) && ($_GET['s'] == 'akun')){
		$id = intval($_GET['id']);
		$query = mysqli_query($mysqli, "SELECT * FROM akun WHERE idakun={$id}");
		$parse = mysqli_fetch_array($query);
		
		if ($parse != null) {
			$id = $parse['idakun'];
			$jenis = $parse['jenis'];
			$kode = $parse['kode'];
			$nama = $parse['namaakun'];
			$keterangan = $parse['keterangan'];
			$idParent = $parse['idParent'];
			$showContent = true;
			
		}else {
			$errorDescription = "Data akun keuangan tidak ditemukan dalam database.";
		}
	} else {
		$errorDescription = "Argumen tidak lengkap.";
	}
	
	if (!$showContent) {
		include COMPONENT_PATH."/file/pages/error.php";
		return;
	}
?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>Detil Akun</h5>
		</div>
		<div class="widget-content">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>
				Informasi Akun</h3>
			</div>
			<div class="panel-body">
				<div class="row">
				  <div class="col-md-2">
					&nbsp;
				  </div>
				  <div class="col-md-10">
					<table class="siz-table-detail">
						<tr>
							<td>Kode Akun</td>
							<td><?php echo (ISSET($kode))?$kode:"";?><br></td>
						</tr>
						<tr>
							<td>Nama Akun</td>
							<td><?php if (isset($nama)) echo $nama; ?></td>
						</tr>
						<tr>
							<td>Jenis</td>
							<td><?php echo (isset($listJenis[$jenis]))?$listJenis[$jenis]:"";?></td>
						</tr>
						<tr>
							<td>Keterangan</td>
							<td><?php echo (isset($keterangan))?nl2br($keterangan):"";?></td>
						</tr>
					</table>
				  </div>
				</div> <!-- End row detil -->
			</div>
		</div> <!-- End panel informasi user -->
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-time"></span>
					List Transaksi</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Jenis Transaksi</th>
								<th>Diterima Dari</th>
								<th>Keterangan</th>
								<th>Jumlah</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$k=0;
								$jml=0;
								$queryTransaksiAkun = "SELECT p.id_penerimaan, p.tanggal, p.id_akun, p.no_nota, a.namaakun, p.jumlah, u.id_user, u.nama, p.keterangan
											FROM penerimaan p 
											LEFT JOIN akun a 
												ON p.id_akun = a.kode 
											LEFT JOIN user u
												ON p.id_donatur = u.id_user
											WHERE id_akun LIKE '".$kode."%' ORDER BY tanggal DESC LIMIT 50";
								$sql = $mysqli->query($queryTransaksiAkun);
								
								while($f = $sql->fetch_array(MYSQLI_ASSOC)){
									$i++;
									$totalMasuk  = $totalMasuk + $f['jumlah'];
									$tanggal = explode('-',$f['tanggal']);
									$tanggal = $tanggal[2].' '.$month1[$tanggal[1]].' '.$tanggal[0];
									
									echo "<tr>";
										echo "<td>$tanggal</td>";
										echo "<td><a href=\"#\">$f[id_akun] $f[namaakun]</a>";
										if (!empty($f['no_nota'])) {
											echo "<br><div class=\"siz-desc-nota\">Nomor Nota : ".htmlspecialchars($f['no_nota'])."</div>";
										}
										echo "</td>";
										echo "<td><a href=\"main.php?s=donatur&amp;id=".$f['id_user']."\">";
										echo "<span class=\"glyphicon glyphicon-user\"></span> ".$f['nama']."</a></td>";
										echo "<td>$f[keterangan]</td>";
										echo "<td align='right'>".to_rupiah($f['jumlah'])."</td>";
										echo "<td><a href=\"main.php?s=transaksi&amp;action=edit-in&amp;id=".$f['id_penerimaan']."\">";
										echo "<i class=\"glyphicon glyphicon-pencil\"></i> Edit</a></td>";
										
									echo "</tr>\n";
									$jml += $f['jumlah'];
								}
							?>
						</tbody>
					</table>
					<p class="siz=p-conclude">Total Donasi : <b><?php echo to_rupiah($jml);?></b></p>
				</div>
				
			</div>
		  </div> <!-- End panel transaksi -->
		  
		</div>
	</div> <!-- End widget box -->			
</div>
