<?php
	include "component/config/koneksi.php";
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>Daftar Peserta Penerima BUS per Wilayah</span>
            </h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal row-fluid" action="main.php" Method="GET">
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
                <label class="control-label span3" for="normal-field">Wilayah</label>
                <div class="controls span5">
                  <select name='wilayah'>
					<?php 
						$sql = mysql_query("SELECT DISTINCT wilayah FROM penerima_bus");
						while($s = mysql_fetch_array($sql)){
							echo "<option value='$s[wilayah]'>$s[wilayah]</option>";
						};
					?>
				  </select>
				  <input type='hidden' name='s' value='daftar_peserta_bus_wilayah' />
                </div>
              </div>
			
			<div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small">Tampilkan Daftar Peserta</button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>