<?php 
	include "component/config/koneksi.php";

	if(ISSET($_POST['save'])){
		$a1 = clear_injection($_POST['fb']);
		$a2 = clear_injection($_POST['tw']);
		$a3 = clear_injection($_POST['we']);
		$a4 = clear_injection($_POST['kn']);
		$a5 = clear_injection($_POST['nm']);
		$a6 = clear_injection($_POST['skl']);
		
		
		$val = $a1."#".$a2."#".$a3."#".$a4."#".$a5."#".$a6;
		
		$sql2 = mysql_query("UPDATE opsi SET value='$val' WHERE name='general'");
		
			if($sql2){
				$_SESSION['success'] = "Berhasil"; 
			}else{
				$_SESSION['error'] = "Terdapat Kesalahan Dalam Pemrosesan : ".mysql_error();
			}
	}
	
	$sql = mysql_query("SELECT * FROM opsi WHERE name='general'");
	$d = mysql_fetch_array($sql);
	$d = explode('#',$d['value']);
	$fb = @$d[0];
	$tw = @$d[1];
	$we = @$d[2];
	$kn = @$d[3];
	$nm = @$d[4];
	$skl = @$d[5];
	
?>

<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>
			<i class="icon-book"></i><span>Pengaturan Umum</span>
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
					<label class="control-label">Nama Web</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="nm" required='required' value='<?php echo (ISSET($nm))?$nm:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Facebook</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="fb" required='required' value='<?php echo (ISSET($fb))?$fb:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Twitter</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="tw" required='required' value='<?php echo (ISSET($tw))?$tw:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Alamat Web</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="we" required='required' value='<?php echo (ISSET($we))?$we:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Kontak Person</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="kn" required='required' value='<?php echo (ISSET($kn))?$kn:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Sekilas Tentang Takmir dan Lazisba Online : </label>
					<div class="controls">
						<textarea name='skl' style='width:80%'><?php echo (ISSET($skl))?$skl:"";?></textarea>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-small" name='save'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				