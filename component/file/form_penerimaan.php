<?php
	include "component/config/koneksi.php";
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>Tambah Transaksi Penerimaan</span>
            </h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal row-fluid" action="component/server/func_input_penerimaan.php" Method="POST">
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
                <label class="control-label span3" for="normal-field">No. Nota</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" name="notrans" required='required' style='width:80%;'/>
                </div>
              </div>
			   <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Tanggal</label>
                <div class="controls span5">
                  <input required='required' type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d');?>" class="datepicker form-control input-small span5" name='tanggal' value='' style='width:80%;'/>
                </div>
              </div>
			  
			   <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Jenis Transaksi</label>
                <div class="controls span5">
					<select name='trans' class="input-small" style='width:80%;' required='required' data-placeholder="-- Pilih Transaksi --">
						<option></option>
					<?php
						$q1 = mysqli_query($mysqli, "SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
						while($p1 = mysqli_fetch_array($q1)){
							echo "
								<option value='$p1[kode]'>$p1[kode] - $p1[namaakun]</option>
								";
						}
					?>
					</select>
                </div>
              </div>
			  

			  
			   <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Muzakki</label>
                <div class="controls span5">
					<select name='muzakki' class="input-small" style='width:80%;' data-placeholder="-- Pilih Muzakki --" required='required'>
						<option></option>
					<?php
						$q3 = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 1");
						while($p3 = mysqli_fetch_array($q3)){
							echo "
								<option value='$p3[id_user]'>$p3[id_user] - $p3[nama]</option>
								";
						}
					?>
					</select>
				</div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Amilin</label>
                <div class="controls span5">
					<select name='amilin' class="input-small" style='width:80%;' data-placeholder="-- Pilih Transaksi --" required='required'>
						<option></option>
					<?php
						//include "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 99");
								while( $pecah = mysqli_fetch_array($sql)){
									echo"<option value=\"$pecah[id_user]\">$pecah[id_user] - $pecah[nama]</option>";
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
						<textarea name="keterangan" class="span8" style='width:80%;'></textarea>
					</div>
				  </div>
			
			<div class="form-actions">
                  <button type="submit" name='save' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'form_penerimaan')?"Tambah Transaksi":"Ubah Transaksi";?></button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>