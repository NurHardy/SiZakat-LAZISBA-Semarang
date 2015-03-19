<?php
	include "component/config/koneksi.php";
	$action = "component/server/func_input_data_muzakki.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'editmuzakki')){
		$id = $_GET['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM Muzakki WHERE IdMuzakki = '$id'");
		$parse = mysqli_fetch_array($query);
		
		$id = $parse['IdMuzakki'];
		$nama = $parse['Nama'];
		$tml = $parse['Tmp_Lahir'];
		$tgl = $parse['Tgl_Lahir'];
		$alamat = $parse['Alamat'];
		$kota = $parse['Kota'];
		$telp = $parse['Telepon'];
		$hp = $parse['Hp'];
		$email = $parse['Email'];
		$kerja = $parse['Pekerjaan'];
		$phasil = $parse['Penghasilan'];
		$perus = $parse['Perusahaan'];
		$alper = $parse['Alamat_Perusahaan'];
		$action = "component/server/func_edit_data_muzakki.php?id=$id";
	}
?>

		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5><?php echo ($_GET['s'] == 'form_muzakki')?"Tambah Muzakki (Penzakat)":"Ubah Muzakki (Penzakat)";?></h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="<?php echo $action; ?>" Method="POST">
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
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Nama lengkap</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" name="namalengkap" style='width:80%' value='<?php echo (ISSET($nama))?$nama:"";?>' required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Tempat Lahir</label>
						<div class="controls span3">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' name="tempatlahir" value='<?php echo (ISSET($tml))?$tml:"";?>' required="required">
						</div>
					   </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Tanggal Lahir</label>
						<div class="controls span3">
						  <input type="text" data-date="12-02-2012" data-date-format="dd-mm-yyyy" style='width:80%' value="12-02-2012" class="datepicker form-control input-small" name="tanggallahir" required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Alamat</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" name="alamat" style='width:80%' value="<?php echo (ISSET($alamat))?$alamat:"";?>" required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Kota</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($kota))?$kota:"";?>" name="kota">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Telepon</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($telp))?$telp:"";?>" name="telepon">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">HP</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($hp))?$hp:"";?>" name="hp" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Email</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($email))?$email:"";?>" name="email">
						</div>
					  </div>
						  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Pekerjaan</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($kerja))?$kerja:"";?>" name="pekerjaan" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Penghasilan<span class="help-block"></span></label>
						<div class="controls span5">
						 <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($phasil))?$phasil:"";?>" name="penghasilan" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Nama Perusahaan</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value="<?php echo (ISSET($perus))?$perus:"";?>" name="perusahaan">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Alamat Perusahaan</label>
						<div class="controls span8">
							<textarea name="alamatperusahaan" class="span8"><?php echo (ISSET($alper))?$alper:"";?></textarea>
						</div>
					  </div>
				
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'form_muzakki')?"Tambah Data Muzakki":"Ubah Data Muzakki";?></button> atau <a class="text-danger" href="#">Batal</a>
					</div>
					</div>
					</form>
				</div>
			</div>						
		</div>
	