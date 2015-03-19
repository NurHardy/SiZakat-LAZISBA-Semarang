<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Persamaan Akun</h5>
			</div>
			<div class="widget-content nopadding">
				<h4 style='margin-left:10px;'>Akun Harian</h4>
				<form action="component/server/persamaan_akun.php" method="post" class="form-horizontal">
				<?php 
					$q1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
					while($p1 = mysqli_fetch_array($q1)){
					
				?>
				
				<hr />
				<div class="form-group">
					<label class="control-label span"><?php echo $p1['kode']." ".$p1['namaakun'];?></label>
					<div class="controls">
						<select data-placeholder="Pilih Akun Penyaluran" name='akun_<?php echo $p1['idakun']?>[]' style="width:350px;" multiple class="chzn-select" tabindex="8">
							<option></option>
							<?php
								$sql = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis='2'");
								while($s = mysqli_fetch_array($sql)){
									$sqla1 = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penerimaan='$p1[kode]' AND id_penyaluran='$s[kode]'");
									if(mysqli_num_rows($sqla1) > 0){
										echo "<option value='$s[kode]' selected>$s[kode] $s[namaakun]</option>";
									}else{
										echo "<option value='$s[kode]'>$s[kode] $s[namaakun]</option>";
									}
								}
								$sql = mysqli_query($mysqli, "SELECT * FROM pengeluaran WHERE jenis='4'");
								while($s = mysqli_fetch_array($sql)){
									$sqla1 = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penerimaan='$p1[kode]' AND id_penyaluran='$s[kode]'");
									if(mysqli_num_rows($sqla1) > 0){
										echo "<option value='$s[kode]' selected>$s[kode] $s[namaakun]</option>";
									}else{
										echo "<option value='$s[kode]'>$s[kode] $s[namaakun]</option>";
									}
								}
							?>
						</select>
					</div>
				</div>
				<?php 
					}
				?>
				<h4 style='margin-left:10px;'>Akun Ramadhan</h4>
				<hr />
				<?php 
					$q1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis = '3' AND kode LIKE '3.1%' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
					while($p1 = mysqli_fetch_array($q1)){
					
				?>
				<div class="form-group">
					<label class="control-label span"><?php echo $p1['kode']." ".$p1['namaakun'];?></label>
					<div class="controls">
						<select data-placeholder="Pilih Akun Penyaluran" name='akun_<?php echo $p1['idakun']?>[]' style="width:350px;" multiple class="chzn-select" tabindex="8">
							
							<?php
								$sql = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis='3' AND kode LIKE '3.2%'");
								while($s = mysqli_fetch_array($sql)){
									$sqla1 = mysqli_query($mysqli, "SELECT * FROM persamaan_akun WHERE id_penerimaan='$p1[kode]' AND id_penyaluran='$s[kode]'");
									if(mysqli_num_rows($sqla1) > 0){
										echo "<option value='$s[kode]' selected>$s[kode] $s[namaakun]</option>";
									}else{
										echo "<option value='$s[kode]'>$s[kode] $s[namaakun]</option>";
									}
								}
							?>
						</select>
					</div>
				</div>
				<?php 
					}
				?>
				<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-small"  name="save">save</button> 
					</div>
				</form>
			</div>
		</div>
	</div>

