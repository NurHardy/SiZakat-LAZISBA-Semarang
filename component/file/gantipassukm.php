<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>Ganti Password UKM</span>
            </h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal row-fluid" action="component/server/gantipassukm.php" Method="POST">
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
						$q1 = mysql_query("SELECT * FROM user WHERE id_user = '$_SESSION[iduser]'");
						$p1 = mysql_fetch_array($q1);
						$username = $p1['username'];
					?>
				</div>
               <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Username</label>
                <div class="controls span12">
                  <input type="text"  style='width:80%' name="username" required='required' value='<?php echo (ISSET($username))?$username:"";?>'/>
                </div>
              </div>
			    <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Password</label>
                <div class="controls span12">
                  <input type="password" style='width:80%' name="p1" required='required'/>
                </div>
              </div>
			    <div class="form-row control-group row-fluid form-group">
                <label class="control-label span3" for="normal-field">Konfirmasi Password</label>
                <div class="controls span12">
                  <input type="password"  style='width:80%' name="p2" required='required'/>
                </div>
              </div>
			 <div class="form-actions">
                  <button type="submit" name='submit' class="btn btn-primary btn-small"><?php echo ($_GET['s'] == 'editukm')?"Edit UKM":"Tambah UKM";?></button>
			</div>
              </div>
            </form>
          </div>
          </div>
          </div>