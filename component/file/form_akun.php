<?php
	//session_start();
	include"component/config/koneksi.php";
	$action = "component/server/save_akun.php";
	require_once "component/libraries/injection.php";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'editakun')){ 
		$id = clear_injection($_GET['id']);
		
		$sql = mysql_query("SELECT * FROM akun WHERE idakun='$id'");
		$d = mysql_fetch_array($sql);
		$nama		= $d['namaakun'];
		$idParent 	= $d['idParent'];
		$ket 		= $d['keterangan'];
		
		$action = "component/server/edit_akun.php?id=$id";
	}
?>
<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span><?php echo ($_GET['s'] == 'editakun')?"Ubah Akun":"Tambah Akun";?></span>
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
                <label class="control-label span3" for="normal-field">Nama Akun</label>
                <div class="controls span12">
                  <input type="text"  style='width:80%' name="nama" required='required' value='<?php echo (ISSET($nama))?$nama:"";?>'/>
                </div>
              </div>
			  <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Induk Akun</label>
                <div class="controls span5">
					<select name='induk' data-placeholder="Pilih Induk Akun" style='width:80%'>
						<option></option>
						<option value='1' <?php echo ((ISSET($idParent)) && ($idParent == 1))?"selected='selected'":""; ?>>1. Penerimaan</option>
						<option value='2' <?php echo ((ISSET($idParent)) && ($idParent == 2))?"selected='selected'":""; ?>>2. Penyaluran</option>
						<option value='21' <?php echo ((ISSET($idParent)) && ($idParent == 21))?"selected='selected'":""; ?>>3. Ramadhan</option>
						<?php 
							$optionInduk = mysql_query("SELECT * FROM akun WHERE idParent <> '0' AND (idParent IN (SELECT idakun FROM akun)) ORDER BY kode ASC");
							while($fo = mysql_fetch_array($optionInduk)){
								if(ISSET($idParent)){
									if($fo['idakun'] == $idParent){
										$sel = "selected='selected'";
									}else{
										$sel = "";
									}
								}else{
									$sel = "";
								}
								
								echo "<option value='$fo[idakun]' $sel >$fo[kode] $fo[namaakun]</option>";
							}
						?>
					</select>
                </div>
              </div>
			  
			 <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Keterangan</label>
                <div class="controls span5">
                  <textarea type="text" id="normal-field" class="form-control" name="keterangan" ><?php echo (ISSET($ket))?$ket:"";?></textarea>
                </div>
              </div>
			<div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'editakun')?"Edit Akun":"Tambah Akun";?></button>
			</div>
              </div>
            </form>
          </div>
          </div>
          </div>