<?php
	include "component/config/koneksi.php";
	$action = "component/server/func_input_info_baru.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'edit_info')){
		$id = $_GET['id'];
		$query = mysql_query("SELECT * FROM informasi WHERE IdInformasi = '$id'");
		$parse = mysql_fetch_array($query);
		$judul = $parse['Judul'];
		$isi = $parse['Isi'];
		
		$action = "component/server/func_edit_info.php?id=$id";
	}
?>
		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5><?php echo ($_GET['s'] == 'edit_info')?"Ubah ":"Tambah ";?>Informasi Ketakmiran LAZISBA Semarang</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid"method="post" action="<?php echo $action; ?>">
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
							<label class="control-label">Judul Informasi</label>
							<div class="controls">
								<input type="text" class="form-control input-small" name="judul" value='<?php echo (ISSET($judul))?$judul:"";?>' required='required'/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">Informasi</label>
							<div class="controls">
								<textarea id="elm1" name="informasi" class="form-control" rows="20" cols="80" style="width: 100%"><?php echo (ISSET($isi))?$isi:"";?></textarea>
							</div>
						</div>
						<div class="form-actions">
							<input type="submit" class="btn btn-primary btn-small" value="Simpan"> atau <a class="text-danger" href="#">Batal</a>
						</div>
					</form>
				</div>
			</div>						
		</div>