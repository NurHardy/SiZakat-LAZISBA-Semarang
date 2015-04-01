<?php
	include "component/config/koneksi.php";
?>

<script type="text/javascript">
	$( document ).ready(function() {
		$(function() {
			$('#debetkaskas').click(function() {
				$('div[id^=div]').hide();
				$('#div1,#div2, #div3, #div5, #div6, #div7, #div8').show();
			});
			$('#debetkasaset').click(function() {
				$('div[id^=div]').hide();
				$('#div1').show();
			});

			$('#debettransferkas').click(function() {
				$('div[id^=div]').hide();
				$('#div1, #div2, #div3, #div4, #div5, #div6, #div7, #div8').show();
			});

			$('#debettransferaset').click(function() {
				$('div[id^=div]').hide();
				$('#div1').show();
			});
			
			$('#kreditkaskas').click(function() {
				$('div[id^=div]').hide();
				$('#div1,#div2, #div3, #div5, #div6, #div7, #div8').show();
			});
			$('#kreditkasaset').click(function() {
				$('div[id^=div]').hide();
				$('#div1').show();
			});

			$('#kredittransferkas').click(function() {
				$('div[id^=div]').hide();
				$('#div1,#div2, #div3, #div4, #div5, #div6, #div7, #div8').show();
			});

			$('#kredittransferaset').click(function() {
				$('div[id^=div]').hide();
				$('#div1').show();
			});

		});
	});
</script>

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
                <label class="control-label span3" for="normal-field">Tanggal</label>
                <div class="controls span5">
                  <input required='required' type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d');?>" class="datepicker form-control input-small span5" name='tanggal' value='' style='width:80%;'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Jenis</label>
                <div class="controls span5">
					<select id='jenis' name='jenis' class="input-small" style='width:80%;' required='required' data-placeholder="-- Pilih Jenis --">
						<option></option>
						<option value="0">Debet</option>
						<option value="1">Kredit</option>
					</select>
                </div>
              </div>	

			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Transaksi</label>
                <div class="controls span5">
					<select id='transaksi' name='transaksi' class="input-small" style='width:80%;' required='required' data-placeholder="-- Pilih Transaksi --">
						<option></option>
						<option value="0">Kas</option>
						<option value="1">Transfer</option>
					</select>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Kas/Aset</label>
                <div class="controls span5">
					<select id='kasaset' name='kasaset' class="input-small" style='width:80%;' required='required' data-placeholder="-- Kas/Aset --">
						<option></option>
						<option value="0">Kas</option>
						<option value="1">Asset</option>
					</select>
                </div>
              </div>

			  <div class="form-actions">
                  <button type="button" name='pilih' class="btn btn-primary btn-small" id=<?php 
																							//$a = 0;   //$_POST[nama_selector]; gagal
																							//$b = 0;	  //$("#selector").val(); gagal
																							//$c = 0;   
																							
																							if (($("#jenis").val() = 0) && ($("#transaksi").val() = 0) && ($("#kasaset").val() = 0)){
																								echo "debetkaskas";
																							}elseif (($( "#jenis" ).val() = 0) && ($( "#transaksi" ).val() = 0) && ($( "#kasaset" ).val() = 1)){
																								echo "debetkasaset";
																							}elseif (($( "#jenis" ).val() = 0) && ($( "#transaksi" ).val() = 1) && ($( "#kasaset" ).val() = 0)){
																								echo "debettransferkas";
																							}elseif (($( "#jenis" ).val() = 0) && ($( "#transaksi" ).val() = 1) && ($( "#kasaset" ).val() = 1)){
																								echo "debettransferaset";
																							}elseif (($( "#jenis" ).val() = 1) && ($( "#transaksi" ).val() = 0) && ($( "#kasaset" ).val() = 0)){
																								echo "kreditkaskas";
																							}elseif (($( "#jenis" ).val() = 1) && ($( "#transaksi" ).val() = 0) && ($( "#kasaset" ).val() = 0)){
																								echo "kreditkasaset";
																							}elseif (($( "#jenis" ).val() = 1) && ($( "#transaksi" ).val() = 1) && ($( "#kasaset" ).val() = 0)){
																								echo "kredittransferkas";
																							}else{
																								echo "kredittransferaset"; // terus mengacu ke ini
																							}
																						?>>
				  
				  
				  Pilih</button>
			  </div>
			  
			  <div class="form-row control-group row-fluid form-group" id="div1" style="display:none;">
                <label class="control-label span3" for="normal-field">No. Nota</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" name="notrans" required='required' style='width:80%;'/>
                </div>
              </div>
	  
			  <div class="form-row control-group row-fluid form-group" id="div2" style="display:none;">
                <label class="control-label span3" for="normal-field">Jenis Akun</label>
                <div class="controls span5">
					<select name='trans' class="input-small" style='width:80%;' required='required' data-placeholder="-- Pilih Akun --">
						<option></option>
					<?php
						$q1 = mysql_query("SELECT * FROM akun WHERE jenis = '1' AND idParent != '0'  AND idakun NOT IN (SELECT idParent FROM akun)");
						while($p1 = mysql_fetch_array($q1)){
							echo "
								<option value='$p1[kode]'>$p1[kode] - $p1[namaakun]</option>
								";
						}
					?>
					</select>
                </div>
              </div>
			  		  
			   <div class="form-row control-group row-fluid form-group" id="div3" style="display:none;">
                <label class="control-label span3" for="normal-field">Muzakki</label>
                <div class="controls span5">
					<select name='muzakki' class="input-small" style='width:80%;' data-placeholder="-- Pilih Muzakki --" required='required'>
						<option></option>
					<?php
						$q3 = mysql_query("SELECT * FROM user WHERE level = 1");
						while($p3 = mysql_fetch_array($q3)){
							echo "
								<option value='$p3[id_user]'>$p3[id_user] - $p3[nama]</option>
								";
						}
					?>
					</select>
				</div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group" id="div4" style="display:none;">
                <label class="control-label span3" for="normal-field">Bank</label>
                <div class="controls span5">
					<select name='muzakki' class="input-small" style='width:80%;' data-placeholder="-- Pilih Bank --" required='required'>
						<option></option>
					<!-- <?php
						$q3 = mysql_query("SELECT * FROM user WHERE level = 1");
						while($p3 = mysql_fetch_array($q3)){
							echo "
								<option value='$p3[id_user]'>$p3[id_user] - $p3[nama]</option>
								";
						}
					?> -->
					</select>
				</div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group" id="div5" style="display:none;">
                <label class="control-label span3" for="normal-field">Amilin</label>
                <div class="controls span5">
					<select name='amilin' class="input-small" style='width:80%;' data-placeholder="-- Pilih Amilin --" required='required'>
						<option></option>
					<?php
						//include "component/config/koneksi.php";
								$sql = mysql_query("SELECT * FROM user WHERE level = 99");
								while( $pecah = mysql_fetch_array($sql)){
									echo"<option value=\"$pecah[id_user]\">$pecah[id_user] - $pecah[nama]</option>";
								}
					?>
					</select>
				</div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group" id="div6" style="display:none;">
                <label class="control-label span3" for="normal-field">Jumlah Setoran</label>
                <div class="controls span5">
                  <input type="text" id="normal-field number" class="form-control input-small" name="jumlah" required='required' style='width:80%;'/>
                </div>
              </div>
			  
				<div class="form-row control-group row-fluid" id="div7" style="display:none;">
					<label class="control-label span3" for="normal-field">Keterangan</label>
					<div class="controls span8">
						<textarea name="keterangan" class="span8" style='width:80%;'></textarea>
					</div>
				  </div>
			
			<div class="form-actions" id="div8" style="display:none;">
                  <button type="submit" name='save' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'form_penerimaan')?"Tambah Transaksi":"Ubah Transaksi";?></button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>