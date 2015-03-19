<?php
	//session_start();
	include"component/config/koneksi.php";
	$action = "component/server/func_cicilan_kubah.php";
	require_once "component/libraries/injection.php";
?>
<div class="col-12">
    <div class="widget-box">
        <div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="icon-book"></i>
					<span>Penyaluran Dana Kredit Usaha Barokah (KUBAH)</span>
				</h5>
			</div>
				<div class="widget-content nopadding">
				<h4 style='padding:10px;'>Nama UKM : <?php 
					include "component/config/koneksi.php";
					$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user='$_GET[id]'");
					$a = mysqli_fetch_array($sql);
					echo '<b>'.$a['nama'].'</b>';
				?></h4>
			<div style='padding:0px 10px 10px 10px;'>
				<div class="widget-box">
                            <div class="widget-title">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab1">Penyaluran</a></li>
                                    <li><a data-toggle="tab" href="#tab2">Pengembalian/Cicilan</a></li>
                                </ul>
                            </div>
                            <div class="widget-content tab-content">
                                <div id="tab1" class="tab-pane active">
									<table class='table table-bordered table-striped table-hover'>
									<thead>
										<tr>
											<th width='5%'>No</th>
											<th width='15%'>Tanggal Penyaluran</th>
											<th width='18%'>Jumlah Penyaluran</th>
										</tr>
									</thead>
									<tbody>
					<?php
						
						$sql1 = mysqli_query($mysqli, "SELECT * FROM penyaluran WHERE id_akun='2.10.' AND th_kubah='$_GET[th]' AND id_ukm='$_GET[id]'");
						$i=0;
						$salur = 0;
						while($f = mysqli_fetch_array($sql1)){
							$i++;
							echo "
								<tr>
									<td>$i</td>
									<td>$f[tanggal]</td>
									<td>$f[jumlah]</td>
								</tr>
							";
							$salur = $salur + $f['jumlah'];
						};
						
								
					?>			</tbody>
						</table>
								</div>
                                <div id="tab2" class="tab-pane">
								<table class='table table-bordered table-striped table-hover'>
					<table class='table table-bordered table-striped table-hover'>
									<thead>
										<tr>
											<th width='5%'>No</th>
											<th width='15%'>Tanggal</th>
											<th width='18%'>Jumlah</th>
										</tr>
									</thead>
					<?php
							$sql = mysqli_query($mysqli, "SELECT * FROM penerimaan WHERE id_akun='1.9.' AND th_kubah='$_GET[th]' AND id_donatur='$_GET[id]'");
							$i=0;
							$salur1 = 0;
							while($f = mysqli_fetch_array($sql)){
								$i++;
								echo "
									<tr>
										<td>$i</td>
										<td>$f[tanggal]</td>
										<td>$f[jumlah]</td>
									</tr>
								";
								$salur1 = $salur1 + $f['jumlah'];
							};
						
					?>
				</table>
								</div>
                            </div>                            
                        </div>
			</div>
			
			<h5 style='margin-left:10px;' >Penyaluran sebesar : Rp <?php echo number_format($salur, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Pengebalian sebesar : Rp <?php echo number_format($salur1, 0 , ',' , '.' ); ?></h5>
			<h5 style='margin-left:10px;' >Kekurangan Pengembalian sebesar : Rp <?php echo number_format(($salur-$salur1), 0 , ',' , '.' ); ?></h5>
		</div>
	</div>
</div>
</div>