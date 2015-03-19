<?php
		//session_start();
		include "component/config/koneksi.php";
		$query = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$_SESSION[iduser]'");
		$parse = mysqli_fetch_array($query);
		
		$id = $parse['id_user'];
		$nama = $parse['nama'];
		$tml = $parse['tempat_lahir'];
		$tgl = $parse['tanggal_lahir'];
		$alamat = $parse['alamat'];
		$kota = $parse['kota'];
		$hp = $parse['hp'];
		$email = $parse['email'];

		$user = $parse['username'];
		$pass = $parse['password'];

?>

		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Ubah Akun Pribadi</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="component/server/func_ubah_akun_zakat.php" Method="POST">
					
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
						  <input type="text" id="normal-field" style='width:80%' class="form-control input-small" name="namalengkap" value='<?php echo (ISSET($nama))?$nama:"";?>' required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Tempat Lahir</label>
						<div class="controls span3">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" name="tempatlahir" value='<?php echo (ISSET($tml))?$tml:"";?>' required="required">
						</div>
					   </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Tanggal Lahir</label>
						<div class="controls span3">
						  <input type="text" data-date="12-02-2012" data-date-format="dd-mm-yyyy" value="<?php echo (ISSET($tgl))?$tgl:date('d-m-Y');?>" class="datepicker form-control input-small" name="tanggallahir"  style='width:80%' required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Alamat</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" name="alamat" value="<?php echo (ISSET($alamat))?$alamat:"";?>" required="required">
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Kota</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($kota))?$kota:"";?>" name="kota">
						</div>
					  </div>
					  
										  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">HP</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($hp))?$hp:"";?>" name="hp" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Email</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($email))?$email:"";?>" name="email">
						</div>
					  </div>
						  
					 
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Username<span class="help-block"></span></label>
						<div class="controls span5">
						 <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" name="user" required="required" value='<?php echo (ISSET($user))?$user:"";?>' />
						</div>
					</div>
					
					<div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Password<span class="help-block"></span></label>
						<div class="controls span5">
						 <input type="password" id="normal-field"  style='width:80%' class="form-control input-small" name='password' value='' />
						</div>
					</div>
					  
					 										
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-small"  name="save">SAVE</button> 
					</div>
					</div>
					</form>
				</div>
			</div>						
		</div>