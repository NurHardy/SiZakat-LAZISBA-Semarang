<div class="col-12">

		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Muzakki yang Belum Melakukan Donasi</h5>
			</div>
			<div class="widget-content nopadding">
				<form method='GET'>
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
					<tr>
					
						<th>No</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>No.Hp</th>
						<th>Rutinitas</th>
						<th>Checklist SMS</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						
						$bln3 = array(1,5,9);
						$bln6 = array(1,7);
						 
						
						$sql1 = mysql_query("SELECT * FROM user WHERE level='1'");
						$jml = 0;
						$i = 1;	
						while($d = mysql_fetch_array($sql1)){
							$cc = 0;
							//cek bulanan
							if($d['jns_donatur'] == '1'){
								$sqla = mysql_query("SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
								$a = mysql_fetch_array($sqla);
								if($a['jumlah'] <= 0){
									$cc = 1;
								}
							}elseif($d['jns_donatur'] == '2'){
								if(in_array(date('m'),$bln3)){
									$sqla = mysql_query("SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
									$a = mysql_fetch_array($sqla);
									if($a['jumlah'] <= 0){
										$cc = 1;
									}
								}
							}elseif($d['jns_donatur'] == '3'){
								if(in_array(date('m'),$bln6)){
									$sqla = mysql_query("SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
									$a = mysql_fetch_array($sqla);
									if($a['jumlah'] <= 0){
										$cc = 1;
									}
								}
							}elseif($d['jns_donatur'] == '4'){
								if(date('m') == '1'){
									$sqla = mysql_query("SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
									$a = mysql_fetch_array($sqla);
									if($a['jumlah'] <= 0){
										$cc = 1;
									}
								}
							}
							
							if($cc == '1'){
								echo"	
								<tr class=\"gradeA\">
									<td width=\"50\">$i</td>
									<td>$d[nama]</td>
									<td>$d[alamat]</td>
									<td>$d[hp]</td>
									<td>";
									if($d['jns_donatur'] == 1){
										echo "Bulanan";
									}else if($d['jns_donatur'] == 2){
										echo "3 Bulanan";
									}else if($d['jns_donatur'] == 3){
										echo "6 Bulanan";
									}elseif($d['jns_donatur'] == 4){
										echo "1 Tahun Sekali";
									}else{
										echo "Tidak Tetap";
									}
									
									echo "</td>
									<td>
										<input type='checkbox' name='sms[]' value='$d[hp]' /> Kirim SMS
									</td>
								</tr>";
								$i++;
							}
							
						}
						
					
					?>
					</tbody>
					</table>  
					<div style='width:100%;text-align:right;padding:20px;'>
						<input type='hidden' name='s' value='kirim_sms' />
						<input type='hidden' name='msg' value='Mengingatkan, ' />
						<input type='submit' name='send' value='Kirimkan SMS' class='btn btn-info btn-small'/>
					</div>
					</form>
			</div>
		</div>
	</div>

