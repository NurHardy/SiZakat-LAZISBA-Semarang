<?php
	include "component/config/koneksi.php";
	$action = "component/server/func_input_data_muzakki.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'editmuzakki')){
		$id = $_GET['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$id'");
		$parse = mysqli_fetch_array($query);
		
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
	} else {
		// Jika tambah muzakki baru...
		if (isset($_GET['nama'])) {
			$nama = @htmlspecialchars($_GET['nama']);
		}
		if (isset($_GET['alamat'])) {
			$alamat = @htmlspecialchars($_GET['alamat']);
		}
	}
?>

		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5><?php echo ($_GET['s'] == 'form_muzakki')?"Tambah Donatur":"Ubah Donatur";?></h5>
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
						<label class="control-label span3" for="normal-field">Pekerjaan</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($kerja))?$kerja:"";?>" name="pekerjaan" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Penghasilan<span class="help-block"></span></label>
						<div class="controls span5">
						 <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($phasil))?$phasil:"";?>" name="penghasilan" required="required">
						</div>
					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Nama Perusahaan</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:80%' class="form-control input-small" value="<?php echo (ISSET($perus))?$perus:"";?>" name="perusahaan">
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
						 <input type="password" id="normal-field"  style='width:80%' class="form-control input-small" name='passwords' <?php echo ($_GET['s'] == 'form_muzakki')?"required='required'":"";?> value='' />
						</div>
					</div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Alamat Perusahaan</label>
						<div class="controls span8">
							<textarea name="alamatperusahaan" style='width:80%;'><?php echo (ISSET($alper))?$alper:"";?></textarea>
						</div>
					  </div>
					
					<div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Kategori Donasi</label>
						<div class="controls span8">
							<select name='jenis' data-placeholder='Pilih Kategori Waktu Donasi' required='required'>
								<option></option>
								<option value='66' <?php echo ((ISSET($jns)) && ($jns == '66'))?"selected":"";?>>Tidak Tetap</option>
								<option value='1' <?php echo ((ISSET($jns)) && ($jns == '1'))?"selected":"";?>>Bulanan</option>
								<option value='2' <?php echo ((ISSET($jns)) && ($jns == '2'))?"selected":"";?>>3 Bulanan</option>
								<option value='3' <?php echo ((ISSET($jns)) && ($jns == '3'))?"selected":"";?>>6 Bulanan</option>
								<option value='4' <?php echo ((ISSET($jns)) && ($jns == '4'))?"selected":"";?>>Tahunan</option>
							</select>
						</div>
					  </div>
										
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-small"  name="save"><?php echo ($_GET['s'] == 'form_muzakki')?"Tambah Muzakki":"Ubah Muzakki";?></button> 
					</div>
					</div>
					</form>
				</div>
			</div>						
		</div>
	