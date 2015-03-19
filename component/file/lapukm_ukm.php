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
				<form method='get' action="">
				<table style='margin-top:20px;margin-left:10px;'>
					<tr>
						<td style='width:100px;'>Tahun</td>
						<td><select name='th'>
								<?php 
									$sqla = mysql_query("SELECT DISTINCT th_kubah FROM penyaluran WHERE id_ukm = '$_SESSION[iduser]'");
									while($s = mysql_fetch_array($sqla)){
										if($s['th_kubah'] == $_GET['th']){
											echo "<option value='$s[th_kubah]' selected>$s[th_kubah]</option>";
										}else{
											echo "<option value='$s[th_kubah]'>$s[th_kubah]</option>";
										}
									}
								?>
							</select>
						</td>
						<td><input type='hidden' name='s' value='lapukm_ukm'><input type='submit' value='Pilih Tahun' class='btn btn-mini btn-primary'/></td>
					</tr>
				</table>
				</form>
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
						
						$sql1 = mysql_query("SELECT * FROM penyaluran WHERE id_akun='2.10.' AND th_kubah='$_GET[th]' AND id_ukm='$_SESSION[iduser]'");
						$i=0;
						$salur = 0;
						while($f = mysql_fetch_array($sql1)){
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
							$sql = mysql_query("SELECT * FROM penerimaan WHERE id_akun='1.9.' AND th_kubah='$_GET[th]' AND id_donatur='$_SESSION[iduser]'");
							$i=0;
							$salur1 = 0;
							while($f = mysql_fetch_array($sql)){
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