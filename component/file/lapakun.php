<?php
	include "component/config/koneksi.php";
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>LAPORAN PER AKUN</span>
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
                <label class="control-label span3" for="normal-field">Bulan</label>
                <div class="controls span5">
					<select name='bln'>
					<?php 
						
	$month1 = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
					foreach($month1 as $m => $v){
						echo "<option value='$m'>$v</option>";
					}
					?>
					</select>
                </div>
              </div>
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Tahun</label>
                <div class="controls span5">
                  <select name='th'>
					<?php 
						$d = date('Y');
						for($i=0;$i<5;$i++){
							echo "<option value='".($d-$i)."'>".($d-$i)."</option>";
						}
					?>
				  </select>
				  <input type='hidden' name='s' value='laplistakun' />
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