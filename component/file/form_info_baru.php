<?php
	include "component/config/koneksi.php";
	$action = "component/server/func_input_info_baru.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'edit_info')){
		$id = $_GET['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM informasi WHERE id_informasi = '$id'");
		$parse = mysqli_fetch_array($query);
		$judul = $parse['judul'];
		$status = $parse['status'];
		$isi = $parse['isi'];
		
		$action = "component/server/func_edit_info.php?id=$id";
	}
?>
		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Input informasi ketakmiran LAZISBA Semarang</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid"method="post" action="<?php echo $action; ?>">
						<div class="form-group">
							<label class="control-label">Judul Informasi</label>
							<div class="controls">
								<input type="text" class="form-control input-small" name="judul" value='<?php echo (ISSET($judul))?$judul:"";?>' style='width:80%'/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Tipe Informasi</label>
							<div class="controls">
								<select name="tipe" data-placeholder='--Pilih Jenis Informasi--' style='width:80%'>
									<option></option>
									<option value="1" <?php echo (ISSET($status) && ($status == '1'))?'selected':"";?>>Tentang LAZISBA</option>
									<option value="2" <?php echo (ISSET($status) && ($status == '2'))?'selected':"";?>>Artikel</option>
									<option value="3" <?php echo (ISSET($status) && ($status == '3'))?'selected':"";?>>Event/Acara</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Informasi</label>
							<div class="controls">
								<textarea id="elm1" name="informasi" class="form-control editme" rows="20" cols="80" style="width: 100%"><?php echo (ISSET($isi))?$isi:"";?></textarea>
							</div>
						</div>
						<div class="form-actions">
							<input type="submit" class="btn btn-primary btn-small" value="Simpan"> atau <a class="text-danger" href="#">Batal</a>
						</div>
					</form>
				</div>
			</div>						
		</div>