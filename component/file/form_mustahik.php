<?php
	//session_start();
	require_once "component/libraries/injection.php";
	$action = "component/server/save_mustahik.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'editmustahik')){
		$s  = clear_injection($_GET['s']); 
		$id = clear_injection($_GET['id']);
		
		$sql = mysql_query("SELECT * FROM mustahik WHERE IdMustahik='$id'");
		$d = mysql_fetch_array($sql);
		
		$nama 			= $d['Nama'];
		$tempat 		= $d['Tmp_Lahir'];
		$tanggal 		= $d['Tgl_Lahir'];
		$alamat 		= $d['Alamat'];
		$kota 			= $d['Kota'];
		$telepon 		= $d['Telepon'];
		$hp 			= $d['Hp'];
		$email 			= $d['Email'];
		$pekerjaan 		= $d['Pekerjaan'];
		$penghasilan 	= $d['Penghasilan'];
		/*$sekolah 		= $d['sekolah'];
		$aSekolah 		= $d['aSekola'];*/
		$action = "component/server/edit_mustahik.php?id=$id";
	}
?>

<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span><?php echo ($_GET['s'] == 'form_mustahik')?"Tambah Data Mustahik":"Ubah Data Mustahik";?></span>
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
                <label class="control-label span3" for="normal-field">Nama lengkap</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' name="nama" required='required' value='<?php echo (ISSET($nama))?$nama:"";?>'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Tempat/Tanggal Lahir</label>
                <div class="controls span5">
                  <input type="text"  class="form-control input-small span7" style='width:40%;display:inline;float:left;margin-rigth:20px;' value='<?php echo (ISSET($tempat))?$tempat:"";?>' name="tempat" required='required' placeholder='Tempat Lahir'/>
                 <input type="text" data-date="<?php echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd" class="datepicker form-control input-small span5" name='tanggal' value='<?php echo (ISSET($tanggal))?$tanggal:date('Y-m-d');?>' style='width:39%;display:inline;float:left;margin-left:10px;' />
				 <br style='clear:both;'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Alamat</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" style='width:80%' value='<?php echo (ISSET($alamat))?$alamat:"";?>' name="alamat" required='required'/>
                </div>
              </div>
			  
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Kota</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" value='<?php echo (ISSET($kota))?$kota:"";?>' style='width:80%' class="form-control input-small" name="kota" required='required'/>
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Telepon</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" style='width:80%'value='<?php echo (ISSET($telepon))?$telepon:"";?>' name="telepon" />
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">HP</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" name="hp" style='width:80%' value='<?php echo (ISSET($hp))?$hp:"";?>' />
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Email</label>
                <div class="controls span5">
                  <input type="email" id="normal-field" class="form-control input-small" style='width:80%' value='<?php echo (ISSET($email))?$email:"";?>' name="email" />
                </div>
              </div>
			  
	  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Pekerjaan</label>
                <div class="controls span5">
                  <input type="text" value='<?php echo (ISSET($pekerjaan))?$pekerjaan:"";?>' style='width:80%' id="normal-field" class="form-control input-small" name="pekerjaan" required='required'/>
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Penghasilan</label>
                <div class="controls span5">
                 <input type="number" id="normal-field" value='<?php echo (ISSET($penghasilan))?$penghasilan:"";?>' style='width:80%' class="form-control input-small" name="penghasilan" required='required'/>
				 <span class="help-block">Penghasilan di tuliskan dalam rupiah</span>
                </div>
              </div>
			  
			 <!--<div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Sekolah</label>
                <div class="controls span5">
                  <input type="text" id="normal-field" class="form-control input-small" name="sekolah" required='required'/>
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Alamat Sekolah</label>
                <div class="controls span5">
					<textarea name="aSekolah" class="form-control" name='alamat' required='required'></textarea>
                </div>
              </div>-->
			<div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'form_mustahik')?"Tambah Mustahik":"Ubah Mustahik";?></button>
			</div>

              </div>
            </form>
          </div>
          </div>
          </div>