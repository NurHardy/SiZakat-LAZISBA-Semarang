<div class="col-12">

		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Muzakki yang Berulang Tahun Bulan ini</h5>
			</div>
			<div class="widget-content nopadding">
				<form method='GET'>
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
					<tr>
					
						<th>No</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Tanggal Lahir</th>
						<th>No.Hp</th>
						<th>Checklist Kirim SMS</th>
					</tr>
					</thead>
					<tbody>
					<?php
						include "component/config/koneksi.php";
						$query = mysqli_query($mysqli, "select * from user WHERE level = 1 and MID(tanggal_lahir,4,2) = MONTH(CURDATE())");
						$i = 1;
						while($pecah = mysqli_fetch_array($query)){
						echo"	
							<tr class=\"gradeA\">
						
							<td width=\"50\">$i</td>
							<td>$pecah[nama]</td>
							<td>$pecah[alamat]</td>
							<td width=\"100px\">$pecah[tanggal_lahir]</td>
							<td>$pecah[hp]</td>
							<td>
								<input type='checkbox' name='sms[]' value='$pecah[hp]' /> Kirim SMS
							</td>
						</tr>";
						$i++;
						}
					?>
					</tbody>
					</table>
					<div style='width:100%;text-align:right;padding:20px;'>
						<input type='hidden' name='s' value='kirim_sms' />
						<input type='hidden' name='msg' value='Selamat Ulang Tahun, ' />
						<input type='submit' name='send' value='Kirimkan SMS' class='btn btn-info btn-small'/>
					</div>
					</form>
			</div>
		</div>
	</div>

