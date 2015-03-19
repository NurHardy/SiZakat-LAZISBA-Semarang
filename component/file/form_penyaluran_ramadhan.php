<?php 
	include "component/config/koneksi.php";
$sql = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name = 'ramadhan' ");
$opsi_ramadhan = mysqli_fetch_array($sql);

if($opsi_ramadhan['value'] != 1){
	echo "<meta http-equiv=\"refresh\" content=\"0; url=main.php?s=home\">";
}else{
?>
		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Tambah Transaksi Penyaluran Ramadhan</h5>
				</div>
				<div class="widget-content nopadding">
					<form class="form-horizontal row-fluid" action="component/server/func_input_penyaluran_ramadhan.php" Method="POST">
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
											  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Tanggal Transaksi</label>
						<div class="controls span3">
						  <input type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d');?>" class="datepicker form-control input-small" name="tgl" required="required" style='width:80%;' />
						</div>
					  </div>
					  

					  
					<!--  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">No Transaksi</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" name="no_transaksi" value="" required="required" style='width:80%;'>
						</div>
					  </div>-->
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Jumlah Penyaluran</label>
						<div class="controls span5">
						  <input type="text" id="normal-field" class="form-control input-small" value="" name="jumlah" required="required" style='width:80%;'>
						</div>
					  </div>
					  
					  <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Jenis Transaksi</label>
						<div class="controls span5">
							<select name="jenis_transaksi" style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
							<option></option>
							<?php
								include "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM Akun Where Jenis = '3' AND kode LIKE '3.2%' AND idakun NOT IN (SELECT idParent FROM akun)");
								while( $pecah = mysqli_fetch_array($sql)){
									echo"<option value=\"$pecah[kode]\">$pecah[kode] - $pecah[namaakun]</option>";
								}
							?>
							</select>
						</div>
					  </div>
					  

					  
					<!-- <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Mustahik</label>
						<div class="controls span5">
						<select name="mustahik" style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
							<option ></option>
							<?php
								include "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 1");
								while( $pecah = mysqli_fetch_array($sql)){
									echo"<option value=\"$pecah[id_user]\">$pecah[id_user] - $pecah[nama]</option>";
								}
							?>
						</select>
						</div>
					  </div>-->
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Amilin</label>
						<div class="controls span5">
						  <select name="amilin" style='width:80%;' data-placeholder='--Pilih Jenis--' required="required">
						  <option ></option>
							<?php
								include "component/config/koneksi.php";
								$sql = mysqli_query($mysqli, "SELECT * FROM user WHERE level = 99");
								while( $pecah = mysqli_fetch_array($sql)){
									echo"<option value=\"$pecah[id_user]\">$pecah[id_user] - $pecah[nama]</option>";
								}
							?>
							</select>
						</div>
					  </div>
			<!--		  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Tahun Ramadhan</label>
                <div class="controls span5">
					<select name='thn' class="input-small" style='width:80%;' data-placeholder="-- Pilih Tahun Ramadhan --" required='required'>
						<option></option>
					<?php
						$q4 = mysqli_query($mysqli, "SELECT * FROM opsi WHERE name='tahun'");
						while($p4 = mysqli_fetch_array($q4)){
							echo "
								<option value='$p4[value]'>$p4[id_opsi] - $p4[value]</option>
								";
						}
					?>
					</select>
				</div>
              </div>
					-->	  

					  

					  </div>
					  
					 <div class="form-row control-group row-fluid">
						<label class="control-label span3" for="normal-field">Keterangan</label>
						<div class="controls span8">
							<textarea name="keterangan" class="span8" style='width:80%;'></textarea>
						</div>
					  </div>
				
					<div class="form-actions">
						<button type="submit" name="save" class="btn btn-primary btn-small">Simpan</button> atau <a class="text-danger" href="#">Batal</a>
					</div>
					</div>
					</form>
				</div>
			</div>						
		</div>
	<?php } ?>