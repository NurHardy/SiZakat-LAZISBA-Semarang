<?php
	//session_start();
	include"component/config/koneksi.php";
	$action = "component/server/func_salur_kubah.php";
	require_once "component/libraries/injection.php";
?>
<div class="col-12">
    <div class="widget-box">
        <div class="box gradient">
			<div class="widget-title">
				<h5>
					<i class="icon-book"></i>
					<span><?php echo ($_GET['s'] == 'editukm')?"Ubah UKM":"Penyaluran Dana Kredit Usaha Barokah (KUBAH)";?></span>
				</h5>
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
              <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Nama UKM</label>
                <div class="controls span12">
					<select name="nama" style='width:80%' data-placeholder="Pilih UKM">
					<option></option>
						<?php
							$q1 = mysqli_query($mysqli, "SELECT * FROM user WHERE level='2'");
							while($p1 = mysqli_fetch_array($q1)){
								echo "<option value='$p1[id_user]'>$p1[nama]</option>";
							}
						?>
					</select>
                </div>
              </div>
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Jumlah Dana</label>
                <div class="controls span12">
                  <input type="text"  style='width:80%' name="dana" required='required' value=''/>
                </div>
              </div>
			  <div class="form-row control-group row-fluid form-group">
					<label class="control-label span3" for="normal-field">Tahun Penyaluran</label>
					<div class="controls span12">
						<input type="text" name="thn" style='width:80%' required="required">
					</div>
				</div>
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Petugas</label>
                <div class="controls span12">
                 <select name="petugas" style='width:80%' data-placeholder="Pilih Petugas">
					<option></option>
					<?php
						$q1 = mysqli_query($mysqli, "SELECT * FROM user WHERE level = '99'");
						while($p1 = mysqli_fetch_array($q1)){
							echo "<option value='$p1[id_user]'>$p1[nama]</option>";
						}
					?>
				</select>
                </div>
              </div>
			  <div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small">Salurkan Dana</button>
			</div>
              </div>
            </form>
          </div>
          </div>
          </div>