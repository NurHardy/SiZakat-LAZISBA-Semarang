<?php
	include "component/config/koneksi.php";
	$action = "component/server/add_bus.php";
	$level="";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'edit_bus')){
		$id = $_GET['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM penerima_bus WHERE id_penerima = '$id'");
		$d= mysqli_fetch_array($query);
		$nama = $d['nama'];
		$alamat = $d['alamat'];
		$wil = $d['wilayahs'];
		$jen = $d['jenjang'];
		$action = "component/server/edit_bus.php?id=$id";
	}

?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>Input Data Penerima BUS</h5>
		</div>
		<div class="widget-content nopadding">
			<form enctype="multipart/form-data" action="<?php echo $action;?>" method="post" class="form-horizontal">
				<div class="form-group">
					<?php
						if(ISSET($_SESSION['success']) || ISSET($_SESSION['error'])){
							if(ISSET($_SESSION['success'])){
								echo '<div class="alert alert-success" style="margin:10px;">'.$_SESSION['success'].'</div>';
								unset($_SESSION['success']);
							}
							
							if(ISSET($_SESSION['error'])){
								echo '<div class="alert alert-error" style="margin:10px;">'.$_SESSION['error'].'</div>';
								unset($_SESSION['error']);
							}
						}
					?>
				</div>
				<div class="form-group">
					<label class="control-label">Alamat</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="alamat" required='required' value='<?php echo (ISSET($alamat))?$alamat:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Nama Ayah</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="ayah" value='<?php echo (ISSET($alamat))?$alamat:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Nama Ibu</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="ibu" value='<?php echo (ISSET($alamat))?$alamat:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Jenjang Sekolah</label>
					<div class="controls">
						<select name='jenjang'>
							<option value='1' <?php echo ((ISSET($jen)) && ($jen == '1'))?'selected':"";?>>SD</option>
							<option value='2' <?php echo ((ISSET($jen)) && ($jen == '2'))?'selected':"";?>>SMP</option>
							<option value='3' <?php echo ((ISSET($jen)) && ($jen == '3'))?'selected':"";?>>SMA</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Wilayah</label>
					<div class="controls">
						<select name='wilayah'>
							<option value='1' <?php echo ((ISSET($wil)) && ($wil == '1'))?'selected':"";?>>Wilayah I</option>
							<option value='2' <?php echo ((ISSET($wil)) && ($wil == '2'))?'selected':"";?>>Wilayah II</option>
							<option value='3' <?php echo ((ISSET($wil)) && ($wil == '3'))?'selected':"";?>>Wilayah III</option>
							<option value='4' <?php echo ((ISSET($wil)) && ($wil == '4'))?'selected':"";?>>Wilayah IV</option>
							<option value='5' <?php echo ((ISSET($wil)) && ($wil == '5'))?'selected':"";?>>Wilayah V</option>
							<option value='6' <?php echo ((ISSET($wil)) && ($wil == '6'))?'selected':"";?>>Wilayah VI</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Prestasi</label>
					<div class="controls">
						<textarea name="prestasi" style="width: 80%;"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Foto</label>
					<div class="controls">
						<input type="file" name="bus">
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-small" name='save_penerima'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				