<?php
	include "component/config/koneksi.php";
	$action = "component/server/add_bus.php";
	$level="";
	if(ISSET($_GET['s']) && ISSET($_GET['id']) && ($_GET['s'] == 'editbus')){
		$id = $_GET['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM penerima_bus WHERE id_penerima = '$id'");
		$d= mysqli_fetch_array($query);
		$nama = $d['nama'];
		$alamat = $d['alamat'];
		$ayah = $d['ayah'];
		$ibu = $d['ibu'];
		$tlhr = $d['tanggal_lahir'];
		$prestasi = $d['keterangan'];
		$jen = $d['jenjang'];
		
		$alamatortu = $d['alamatortu'];
		$pekayah = $d['pekayah'];
		$pekibu = $d['pekibu'];
		$penghasilan = $d['penghasilan'];
		$status = $d['status'];
		$hobi = $d['hobi'];
		
		$query1 = mysqli_query($mysqli, "SELECT * FROM prestasi WHERE id_penerima = '$id'");
		$d1= mysqli_fetch_array($query1);
		$doa = $d1['doa'];
		$alquran = $d1['alquran'];
		$minat = $d1['minat'];
		$rapor = $d1['rapor'];
		
		$action = "component/server/edit_bus.php?id=$id";
	}

?>
<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-align-justify"></i>									
			</span>
			<h5>Input Data Penerima BUS</h5>
		</div>
		<div class="widget-content nopadding">
			<form enctype="multipart/form-data" action="<?php echo $action;?>" method="post" class="form-horizontal">
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
				<div class="form-group">
					<label class="control-label">Nama Lengkap</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="nama" required='required' value='<?php echo (ISSET($nama))?$nama:"";?>'/>
					</div>
				</div>
				
				<div class="form-row control-group row-fluid form-group">
					<label class="control-label span3" for="normal-field">Tanggal Lahir</label>
					<div class="controls span5">
						<input required='required' type="text" data-date-format="yyyy-mm-dd" value='<?php echo (ISSET($tlhr))?$tlhr:"1991-01-01";?>' class="datepicker form-control input-small span5" name='tlahir' style='width:80%;'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Jenjang Sekolah</label>
					<div class="controls">
						<select name='jenjang' >
							<option value='1' <?php echo ((ISSET($jen)) && ($jen == '1'))?'selected':"";?>>SD</option>
							<option value='2' <?php echo ((ISSET($jen)) && ($jen == '2'))?'selected':"";?>>SMP</option>
							<option value='3' <?php echo ((ISSET($jen)) && ($jen == '3'))?'selected':"";?>>SMA</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Alamat</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="alamat" required='required' value='<?php echo (ISSET($alamat))?$alamat:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Hobi</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="hobi" required='required' value='<?php echo (ISSET($hobi))?$hobi:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Nama Ayah</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="ayah" value='<?php echo (ISSET($ayah))?$ayah:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Nama Ibu</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="ibu" value='<?php echo (ISSET($ibu))?$ibu:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Alamat Orang Tua</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="alamatortu" value='<?php echo (ISSET($alamatortu))?$alamatortu:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Pekerjaan Ayah</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="pekayah" value='<?php echo (ISSET($pekayah))?$pekayah:"";?>'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Pekerjaan Ibu</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="pekibu" value='<?php echo (ISSET($pekibu))?$pekibu:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Penghasilan Perbulan</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="penghasilan" value='<?php echo (ISSET($penghasilan))?$penghasilan:"";?>'/>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">Status</label>
					<div class="controls">
						<input type="text" class="form-control input-small" style='width:80%' name="status" value='<?php echo (ISSET($status))?$status:"";?>'/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label">Prestasi</label>
						<div class="controls">
						<i style='font-size:12px;line-height:13px;'><b>Al-qur'an</b></i><br/>
						<textarea name="alquran" style="width: 80%;height:70px;"><?php echo (ISSET($alquran))?$alquran:"";?></textarea>
						</div>
						
						<div class="controls">		
						<i style='font-size:12px;line-height:13px;'><b>Doa</b></i><br/>
						<textarea name="doa" style="width: 80%;height:70px;"><?php echo (ISSET($doa))?$doa:"";?></textarea>
						</div>
						
						<div class="controls">
						<i style='font-size:12px;line-height:13px;'><b>Nama Lomba | Keterangan</b></i><br/>
						<textarea name="minat" style="width: 80%;height:70px;"><?php echo (ISSET($minat))?$minat:"";?></textarea>
						</div>
						
						<div class="controls">
						<i style='font-size:12px;line-height:13px;'><b>Rata - Rata | Peringkat</b></i><br/>
						<textarea name="rapor" style="width: 80%;height:70px;"><?php echo (ISSET($rapor))?$rapor:"";?></textarea>
						</div>
				</div>
				
				
				<!-- <div class="form-group">
					<label class="control-label">Prestasi</label>
					<div class="controls">
						<i style='font-size:12px;line-height:13px;'><b>contoh :<br /> 
							20 Agustus 2012 | Selesai hafalan Jus 1<br />24 September 2012 | Selesai hafalan Jus 2</b></i>
						<textarea name="prestasi" style="width: 80%;height:200px;"><?php echo (ISSET($prestasi))?$prestasi:"";?></textarea>
					</div>
				</div>-->
				<div class="form-group">
					<label class="control-label">Foto</label>
					<div class="controls">
						<input type="file" name="bus">
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary btn-small" name='save_penerima'>Save</button> 
				</div>
			</form>
		</div>
	</div>						
</div>
				