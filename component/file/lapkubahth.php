<?php
	include "component/config/koneksi.php";
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>LAPORAN BULANAN</span>
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
                <label class="control-label span3" for="normal-field">Tahun</label>
                <div class="controls span5">
                  <select name='th'>
					<?php 
						$sql = mysql_query("SELECT DISTINCT th_kubah FROM penyaluran WHERE id_akun='2.10.'");
						while($s = mysql_fetch_array($sql)){
							echo "<option value='$s[th_kubah]'>$s[th_kubah]</option>";
						};
					?>
				  </select>
				  <input type='hidden' name='s' value='transaksi_kubah' />
                </div>
              </div>
			
			<div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small">Tampilkan Laporan Bulanan</button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>