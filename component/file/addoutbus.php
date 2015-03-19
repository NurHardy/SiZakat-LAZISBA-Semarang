<?php
	include "component/config/koneksi.php";
	$action = "component/server/add_out_bus.php?bln=$_GET[bln]";
	$level="";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'edit_bus')){
		$id = $_GET['id'];
		$query = mysql_query("SELECT * FROM penyaluran_bus WHERE id_penyaluran = '$id'");
		$d= mysql_fetch_array($query);
		$nama = $d['nama'];
		$alamat = $d['alamat'];
		$wil = $d['wilayahs'];
		$jen = $d['jenjang'];
		$action = "component/server/edit_out_bus.php?id=$id";
	}

?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>Input Penyaluran Koordinator BUS</h5>
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
					<label class="control-label">Jumlah</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="jumlah" required='required' value='<?php echo (ISSET($nama))?$nama:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Jenjang Sekolah</label>
					<div class="controls">
						<select name='jenjang'>
							<option value='1' <?php echo ((ISSET($jen)) && ($jen == '1'))?'selected':"";?>>SD</option>
							<option value='2' <?php echo ((ISSET($jen)) && ($jen == '2'))?'selected':"";?>>SMP</option>
							<option value='3' <?php echo ((ISSET($jen)) && ($jen == '3'))?'selected':"";?>>SMA</option>
						</select>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-small" name='save_out'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				