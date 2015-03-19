<?php
	include "component/config/koneksi.php";
	$action = "component/server/server_add_amilin.php";
	$level="";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'edit_amilin')){
		$id = $_GET['id'];
		$query = mysql_query("SELECT * FROM user WHERE id_user = '$id'");
		$d= mysql_fetch_array($query);
		$nama = $d['nama'];
		$tmp = $d['tempat_lahir'];
		$tgl = $d['tanggal_lahir'];
		$alamat = $d['alamat'];
		$kota = $d['kota'];
		$hp = $d['hp'];
		$email = $d['email'];
		$user = $d['username'];
		$pass = $d['password'];
		$action = "component/server/edit_amilin.php?id=$id";
		$level = $d['level'];
		$wil = $d['wilayah_bus'];
	}

?>
<script src="js/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$('.wilayah').hide();
		$('.level').change(function(){
			
			var lv = $(this).val();
			if(lv == 3){
				$('.wilayah').fadeIn();
			}else{
				$('.wilayah').fadeOut();
			}
		})
	});
</script>

<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>
			<i class="icon-book"></i><span><?php echo ($_GET['s'] == 'edit_amilin')?"Ubah Amilin":"Tambah Amilin";?></span>
			</h5>
		</div>
		<div class="widget-content nopadding">
			<form action="<?php echo $action;?>" method="post" class="form-horizontal">
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
					 <input type="password" id="normal-field"  style='width:80%' class="form-control input-small" name='passwords' value='' />
					</div>
				</div>
				<div class="form-row control-group row-fluid">
					<label class="control-label span3" for="normal-field">Status<span class="help-block"></span></label>
					<div class="controls span5">
						<select name='level' class='level'>
						<!--	<option value='2'  <?php if($level == '2'){echo 'selected';}?>>Amilin</option> -->
							<option value='-'  <?php if($level == ''){echo 'selected';}?>>Pilih Status</option>
							<option value='3'  <?php if($level == '3'){echo 'selected';}?>>Koordinator Bus</option>
							<option value='99' <?php if($level == '99'){echo 'selected';}?>>Administrator</option>
						</select>
					</div>
				</div>
				<div style='margin-top:5px;' class="wilayah form-row control-group row-fluid">
					<label class="control-label span3" for="normal-field">Wilayah<span class="help-block"></span></label>
					<div class="controls span5">
						<select name='wil'>
							<option value='1' <?php echo ((ISSET($wil)) && ($wil == '1'))?'selected':"";?>>Wilayah I</option>
							<option value='2' <?php echo ((ISSET($wil)) && ($wil == '2'))?'selected':"";?>>Wilayah II</option>
							<option value='3' <?php echo ((ISSET($wil)) && ($wil == '3'))?'selected':"";?>>Wilayah III</option>
							<option value='4' <?php echo ((ISSET($wil)) && ($wil == '4'))?'selected':"";?>>Wilayah IV</option>
							<option value='5' <?php echo ((ISSET($wil)) && ($wil == '5'))?'selected':"";?>>Wilayah V</option>
							<option value='6' <?php echo ((ISSET($wil)) && ($wil == '6'))?'selected':"";?>>Wilayah VI</option>
							<option value='7' <?php echo ((ISSET($wil)) && ($wil == '7'))?'selected':"";?>>Wilayah VII</option>
							<option value='8' <?php echo ((ISSET($wil)) && ($wil == '8'))?'selected':"";?>>Wilayah VIII</option>
							<option value='9' <?php echo ((ISSET($wil)) && ($wil == '9'))?'selected':"";?>>Wilayah IX</option>
							<option value='10' <?php echo ((ISSET($wil)) && ($wil == '10'))?'selected':"";?>>Wilayah X</option>
						</select>
					</div>
				</div>
		
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-small" name='save'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				