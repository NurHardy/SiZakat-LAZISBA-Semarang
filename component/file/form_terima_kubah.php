<?php
	include "component/config/koneksi.php";
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>Tambah Transaksi Penerimaan Dana KUBAH</span>
            </h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal row-fluid" action="component/server/func_penerimaan_kubah.php" Method="POST">
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
                <label class="control-label span3" for="normal-field">Tanggal</label>
                <div class="controls span5">
                  <input required='required' type="text" data-date="<?php echo date('Y-m-d');?>" data-date-format="dd-mm-yyyy" class="datepicker form-control input-small span5" name='tanggal' value='' style='width:80%;'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">No. Transaksi</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" name="notrans" required='required' style='width:80%;'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Donatur</label>
                <div class="controls span5">
					<select name='donatur' class="input-small" style='width:80%;' data-placeholder="-- Pilih Muzakki --" required='required'>
						<option></option>
					<?php
						$q3 = mysql_query("SELECT * FROM user WHERE level = '1'");
						while($p3 = mysql_fetch_array($q3)){
							echo "
								<option value='$p3[id_user]'>$p3[nama]</option>
								";
						}
					?>
					</select>
				</div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Amilin</label>
                <div class="controls span5">
					<select name='pegawai' class="input-small" style='width:80%;' data-placeholder="-- Pilih Transaksi --" required='required'>
						<option></option>
					<?php
						//include "component/config/koneksi.php";
								$sql = mysql_query("SELECT * FROM user WHERE level = '99'");
								while( $pecah = mysql_fetch_array($sql)){
									echo"<option value=\"$pecah[id_user]\">$pecah[nama]</option>";
								}
					?>
					</select>
				</div>
              </div>
				<div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Jumlah Setoran</label>
                <div class="controls span5">
                  <input type="text" id="normal-field number" class="form-control input-small" name="jumlah" required='required' style='width:80%;'/>
                </div>
              </div>
			  
				<div class="form-row control-group row-fluid">
					<label class="control-label span3" for="normal-field">Keterangan</label>
					<div class="controls span8">
						<textarea name="keterangan" class="span8"></textarea>
					</div>
				  </div>
			
			<div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'form_penerimaan')?"Tambah Transaksi":"Ubah Transaksi";?></button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>