<?php
	include "component/config/koneksi.php";

		$query = mysql_query("select * from opsi where name = 'link' ");
		$parse = mysql_fetch_array($query);
		$link = $parse['value'];
		
		if(ISSET($_POST['save'])){
			//$sql = mysql_query ("INSERT INTO opsi (id_opsi,name,value) VALUES ('','link','$_POST[link]')");
			
			$sql = mysql_query ("UPDATE opsi SET value = '$_POST[link]' WHERE name = 'link'");
								
				if($sql){
					$_SESSION['success'] = "Data Berhasil Di Ubah"; 
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
					<h5>Input informasi ketakmiran LAZISBA Semarang</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid"method="post" action="">
					<div class="form-group">
					<?php
						if(ISSET($_SESSION['success']) || ISSET($_SESSION['error'])){
							if(ISSET($_SESSION['success'])){
								echo '<div class="alert alert-success" style="margin:10px;">'.$_SESSION['success'].'</div>';
								echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=pengaturan_lain_lain\">";
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
							<label class="control-label">Link-Link</label>
							<div class="controls span8">
							<textarea name="link" class="span8" style='width:80%; height:170px;'><?php echo $link;?></textarea>
							<span class="help-block">Nama Website | URL : Http://namaWebsite.com<span>
							</div>
						</div>
						
						

						<div class="form-actions">
							<input type="submit" class="btn btn-primary btn-small" value="Simpan" name="save"> atau <a class="text-danger" href="#">Batal</a>
						</div>
					</form>
				</div>
			</div>						
		</div>