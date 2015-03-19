<?php
	include "component/config/koneksi.php";
	//$action = "component/server/func_pengaturan_bus.php";
	$sql = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name = 'dana_bus_jenjang'");
	$f = mysqli_fetch_array($sql);
	$d = $f['value'];
	$x = explode("#",$d);
	
?>

		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Pengaturan Jumlah Dana BUS</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="component/server/func_pengaturan_bus.php" Method="POST">
					
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
						<label class="control-label span3" for="normal-field">SD</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" style='width:20%' class="form-control input-small" name="sd" value='<?php echo $x[0]; ?>' >
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">SMP</label>
						<div class="controls span3">
						  <input type="text" id="normal-field"  style='width:20%' class="form-control input-small" name="smp" value='<?php echo $x[1]; ?>' >
						</div>
					   </div>
					  
									  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">SMA</label>
						<div class="controls span5">
						  <input type="text" id="normal-field"  style='width:20%' class="form-control input-small" name="sma" value="<?php echo $x[2]; ?>" >
						</div>
					  </div>
					  
					  
										
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-small"  name="save">save</button> 
					</div>
					</div>
					</form>
				</div>
			</div>						
		</div>
	