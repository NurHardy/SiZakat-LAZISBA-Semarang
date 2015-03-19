<?php
	include "component/config/koneksi.php";
	//include "../libraries/injection.php";
	
	$query = mysqli_query($mysqli, "SELECT * FROM user WHERE id_user = '$_SESSION[iduser]'");
	$d= mysqli_fetch_array($query);
	$nama = $d['nama'];
	$user = $d['username'];
	$pass = $d['password'];
	$tmp = $d['tempat_lahir'];
	$tgl = $d['tanggal_lahir'];
	$alamat = $d['alamat'];
	$kota = $d['kota'];
	$hp = $d['hp'];
	$email = $d['email'];;
	
	//---------------------------------------------------------------------------------------
	if(isset($_POST['save'])){
			if($_POST['password'] == ""){
					$pas = "";
			}else{
					$pas = "password = sha1(sha1(md5('$_POST[password]'))),";
			}
			
			$nama = $_POST['nama'];
			$tmp_lahir = $_POST['tmp_lahir'];
			$tgl_lahir = $_POST['tgl_lahir'];
			$alamat = $_POST['alamat'];
			$kota = $_POST['kota'];
			$hp = $_POST['hp'];
			$email = $_POST['email'];
			$user = $_POST['user'];
			
			$sql = mysqli_query($mysqli, "UPDATE user SET username = '$user', $pas nama = '$nama', tempat_lahir = '$tmp_lahir', tanggal_lahir = '$tgl_lahir' , alamat = '$alamat', kota = '$kota', hp = '$hp' , email = '$email' WHERE id_user = '$_SESSION[iduser]'");
			
			if($sql){
					if($_SESSION['level'] == 99){
						$_SESSION['success'] = "Data Admin Berhasil Diubah";
						//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_pribadi&id=$_SESSION[iduser]\">";
					}else{
						$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
						//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_pribadi&id=$_SESSION[iduser]\">";
					}
				
			}
	}	
	
?>

<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>
			<i class="icon-book"></i><span><?php echo ($_GET['s'] == 'ubah_akun_pribadi')?"Ubah Akun":"";?></span>
			</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="" method="post" class="form-horizontal">
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
					<label class="control-label">Nama Lengkap</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="nama" required='required' value='<?php echo (ISSET($nama))?$nama:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Tempat Lahir</label>
					<div class="controls"  class="span4">
						<input type="text" class="form-control input-small" style='width:80%' name="tmp_lahir" class="span3" required='required' value='<?php echo (ISSET($tmp))?$tmp:"";?>'/>
					</div>
													   
				</div>
				
				<div class="form-group">
					<label class="control-label">Tanggal Lahir</label>
					<div class=" controls">
						<input type="text" data-date="12-02-2012" style='width:80%' data-date-format="dd-mm-yyyy"  value="<?php echo (ISSET($tgl))?$tgl:"12-02-2012";?>" class="datepicker form-control input-small" name="tgl_lahir" value=''/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Alamat</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="alamat" required='required' value='<?php echo (ISSET($alamat))?$alamat:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Kota</label>
					<div class="controls">
						<input type="text" class="form-control input-small" name="kota" style='width:80%' required='required' value='<?php echo (ISSET($kota))?$kota:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Hp</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="hp" required='required' value='<?php echo (ISSET($hp))?$hp:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Email</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="email" required='required' value='<?php echo (ISSET($email))?$email:"";?>'/>
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
					<button type="submit" class="btn btn-primary btn-small" name='save'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				