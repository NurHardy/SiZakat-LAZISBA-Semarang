<?php
					
	$listKategoriDonasi = array(
		1 => "Bulanan",
		2 => "3 Bulanan",
		3 => "6 Bulanan",
		4 => "Tahunan",
		66 => "Tidak Tetap"
	);
	$showContent = false;
	
	if(isset($_GET['id']) && ($_GET['s'] == 'donatur')){
		$id = intval($_GET['id']);
		$query = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$id'");
		$parse = mysqli_fetch_array($query);
		
		if ($parse != null) {
			$id = $parse['id_user'];
			$nama = $parse['nama'];
			$tml = $parse['tempat_lahir'];
			$tgl = $parse['tanggal_lahir'];
			$alamat = $parse['alamat'];
			$kota = $parse['kota'];
			$hp = $parse['hp'];
			$email = $parse['email'];
			$kerja = $parse['pekerjaan'];
			$phasil = $parse['penghasilan'];
			$perus = $parse['perusahaan'];
			$alper = $parse['alamat_perusahaan'];
			$user = $parse['username'];
			$pass = $parse['password'];
			$jns = $parse['jns_donatur'];
			$action = "component/server/func_edit_data_muzakki.php?id=$id";
			$showContent = true;
			
		}else {
			$errorDescription = "Data donatur tidak ditemukan dalam database.";
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
			<h5>Detil Donatur</h5>
		</div>
		<div class="widget-content">
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span>
				Informasi Donatur</h3>
			</div>
			<div class="panel-body">
				<div class="row">
				  <div class="col-md-2">
					<img src="images/emptyportrait.png" alt="Foto"/>
				  </div>
				  <div class="col-md-10">
					<table class="siz-table-detail">
						<tr>
							<td>Username Login</td>
							<td><?php echo (ISSET($user))?$user:"";?><br>
							<a href="#">
								<span class="glyphicon glyphicon-lock"></span>
								Set password untuk user ini
							</a></td>
						</tr>
						<tr>
							<td>Nama Lengkap</td>
							<td><?php if (isset($nama)) echo $nama; ?></td>
						</tr>
						<tr>
							<td>Tempat Tanggal Lahir</td>
							<td><?php echo (isset($tml)?$tml:"").", ".(isset($tgl)?$tgl:date('d-m-Y')); ?></td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td><?php echo (isset($alamat))?$alamat:"";?></td>
						</tr>
						<tr>
							<td>Kota</td>
							<td><?php echo (ISSET($kota))?$kota:"";?></td>
						</tr>
						<tr>
							<td>HP</td>
							<td><?php echo (ISSET($hp))?$hp:"";?></td>
						</tr>
						<tr>
							<td>E-mail</td>
							<td><?php
								echo (ISSET($email))?"<a href=\"mailto:".$email."\">".$email."</a>":"";
							?></td>
						</tr>
						<tr>
							<td>Pekerjaan</td>
							<td><?php echo (ISSET($kerja))?$kerja:"";?></td>
						</tr>
						<tr>
							<td>Penghasilan</td>
							<td><?php echo (ISSET($phasil))?to_rupiah($phasil):"";?></td>
						</tr>
						<tr>
							<td>Nama Perusahaan</td>
							<td><?php echo (ISSET($perus))?$perus:"";?></td>
						</tr>
						<tr>
							<td>Alamat Perusahaan</td>
							<td><?php echo nl2br((ISSET($alper))?$alper:"");?></td>
						</tr>
						<tr>
							<td>Kategori Donasi</td>
							<td><?php echo (isset($listKategoriDonasi[$jns])?
												$listKategoriDonasi[$jns]:"Unknown");?></td>
						</tr>
					</table>
				  </div>
				</div> <!-- End row detil -->
			</div>
		</div> <!-- End panel informasi user -->
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-time"></span>
					History Donasi:</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Jenis Transaksi</th>
								<th>Jumlah</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$k=0;
								$jml=0;
								$sql = mysqli_query($mysqli,
									"SELECT *, a.idakun FROM penerimaan p LEFT JOIN akun a ON p.id_akun=a.kode ".
									"WHERE p.id_donatur = '{$_GET['id']}' ORDER BY tanggal DESC");
								while($s = mysqli_fetch_array($sql)){
									$k++;
									$jml = $jml + $s['jumlah'];
									$tanggalDonasi = date("d M Y", strtotime($s['tanggal']));
									echo "
										<tr>
											<td>{$tanggalDonasi}</td>
											<td><a href=\"main.php?s=akun&amp;action=detail&amp;id={$s['idakun']}\">
												{$s['kode']} {$s['namaakun']}</a><br>
												<div class=\"siz-desc-nota\">No. nota: {$s['no_nota']}</div></td>
											<td>".to_rupiah($s['jumlah'])."</td>
											<td><a href=\"main.php?s=transaksi&amp;action=edit-in&amp;id=".$s['id_penerimaan']."\">
											<i class=\"glyphicon glyphicon-pencil\"></i> Edit</a></td>
										</tr>
									";
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
