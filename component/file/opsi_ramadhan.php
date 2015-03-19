<?php 	
	//session_start();
	include "component/config/koneksi.php";
	$s = mysql_query("select * from opsi where name = 'tahun'");
	$p = mysql_fetch_array($s);
	
	$s1 = mysql_query("select * from opsi where name = 'ramadhan'");
	$p1 = mysql_fetch_array($s1);
	
	
	if(ISSET($_POST['save'])){
	$sql = mysql_query ("UPDATE opsi SET value = '$_POST[aktivasi]' WHERE name = 'ramadhan'");
	$sql = mysql_query ("UPDATE opsi SET value = '$_POST[tahun]' WHERE name = 'tahun'");
	
						
		if($sql){
			$_SESSION['success'] = "Data Berhasil Di Update"; 
		}else{
			$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".mysql_error();
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=opsi_ramadhan\">";
		}
	}

?>


	<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Pilih Aktivasi Menu Ramadhan</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="" Method="POST">
				    <div class="form-group">
					<?php
						if(ISSET($_SESSION['success']) || ISSET($_SESSION['error'])){
							if(ISSET($_SESSION['success'])){
								echo '<div class="alert alert-success" style="margin:10px;">'.$_SESSION['success'].'</div>';
								echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=opsi_ramadhan\">";
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
						<label class="control-label span3" for="normal-field">Tahun Hijriah</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" name="tahun" value="<?php echo $p['value']?>" required="required" style='width:23%;'>
						</div>
					</div>
					
					<div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Aktivasi</label>
						<div class="controls span5">
						 <select name='aktivasi' class="input-small" style='width:20%;' data-placeholder="-- Pilih Aktivasi --" required='required'>
						 
							<option value="1" <?php if ($p1['value'] == 1){echo 'selected';}?>>Aktif</option>
							<option value="0"  <?php if ($p1['value'] == 0){echo 'selected';}?>>Non Aktif</option>
						</select>
						</div>
					</div>
					
					<div class="form-actions">
						<button type="submit" name="save" class="btn btn-primary btn-small">Simpan</button> atau <a class="text-danger" href="#">Batal</a>
					</div>
					
					</form>
				</div>
			</div>
	</div>