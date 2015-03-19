<script type="text/javascript">
	function konfirmasi(){
		var pilihan = confirm("are you sure to delete this record ?");
		if(pilihan){
			return true;
		}else{
			return false;
		}
	}
</script>
<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>
				<h5>Daftar Penerima Zakat Wilayah</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover data-table">
					<thead>
						<tr>
							<th rowspan='2'>No</th>
							<th rowspan='2'>Bulan</th>
							<th rowspan='2'>Pemasukan</th>
							<th colspan='3'>Pengeluaran</th>
							<th rowspan='2'>Action</th>
						</tr>
						<tr>
							<th>SD <br/>(Tersalur/Dibutuhkan)</th>
							<th>SMP <br/>(Tersalur/Dibutuhkan)</th>
							<th>SMA <br/>(Tersalur/Dibutuhkan)</th>
						</tr>
					</thead>
					<tbody>
		
					<?php
						
						$bulan = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
						include "component/config/koneksi.php";
						
						
						$query = mysql_query("select * from user WHERE id_user='$_SESSION[iduser]'");
						$user = mysql_fetch_array($query);
						
						
						
						$sql1 = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun='2.1.$user[wilayah_bus].' AND MID(tanggal,7,4) ='".date('Y')."' ");
						$dd = mysql_fetch_array($sql1);
						
						$sql2 = mysql_query("SELECT SUM(jumlah) as jumlah_keluar, jenjang FROM penyaluran_bus WHERE YEAR(tanggal) = '".date('Y')."' AND wilayah='$user[wilayah_bus]'");
						$dd2 = mysql_fetch_array($sql2);
						
						$saldo = $dd['jumlah'] - $dd2['jumlah_keluar'];					
						//$saldo = 0;
						
						
						
						$quera = mysql_query("select * from opsi WHERE name='dana_bus_jenjang'");
		
						$jenjang = mysql_fetch_array($quera);
						$j = explode('#',$jenjang['value']);
						
						
						for($i=0;$i<count($bulan);$i++){
							$sql1 = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun='2.1.$user[wilayah_bus].' AND ".($i+1)." = MID(tanggal,4,2)");
							$dd = mysql_fetch_array($sql1);
							
							$sqlaa = mysql_query(
										"SELECT jenjang, count(jenjang) jml 
										FROM penerima_bus 
										WHERE 
										(MONTH(off_date) > '".($i+1)."' OR MONTH(off_date)  = '00') 
										AND (YEAR(off_date) >= '".(date('Y'))."' OR YEAR(off_date) = '00') 
										AND wilayah='$user[wilayah_bus]' 
										AND (MONTH(add_date) <= '".($i+1)."' AND YEAR(add_date) <= '".(date('Y'))."') 
										GROUP BY jenjang"
							);
							
							
							$sd = $smp = $sma = 0;
							while($qj = mysql_fetch_array($sqlaa)){
								if($qj['jenjang'] == 1){
									$sd  = $qj['jml']*$j[0];
								}elseif($qj['jenjang'] == 2){
									$smp = $qj['jml']*$j[1];
								}elseif($qj['jenjang'] == 3){
									$sma = $qj['jml']*$j[2];
								}
							}
							$sd1 = $smp1 = $sma1 = 0;
							$sqlo = mysql_query("SELECT SUM(jumlah) as jumlah_keluar, jenjang FROM penyaluran_bus WHERE bulan = '".($i+1)."' AND YEAR(tanggal) = '".date('Y')."' AND wilayah='$user[wilayah_bus]' GROUP BY jenjang");
							while($qj1 = mysql_fetch_array($sqlo)){
								//echo $qj1['jenjang'].' '.$qj1['jml'];
								if($qj1['jenjang'] == 1){
									$sd1  = $qj1['jumlah_keluar'];
								}elseif($qj1['jenjang'] == 2){
									$smp1 = $qj1['jumlah_keluar'];
								}elseif($qj1['jenjang'] == 3){
									$sma1 = $qj1['jumlah_keluar'];
								}else{
									//echo "aaaaaa$i ";
								}
								
							}
							
								
							
							echo "
								<tr>
									<td>".($i+1)."</td>
									<td>$bulan[$i]</td>
									<td>".(($dd['jumlah'] == "")?'0':$dd['jumlah'])."</td>
									<td>$sd1/$sd</td>
									<td>$smp1/$smp</td>
									<td>$sma1/$sma</td>
									<td style='width:15%'>";
									if($saldo > 0){
										echo "<a href='main.php?s=addoutbus&bln=".($i+1)."' class='btn btn-info btn-mini'>Tambah Pengeluaran</a>";
									}else{
										echo "<div class='btn btn-mini' style='background:#DDD;'>Tambah Pengeluaran</div>";
									}
									echo "</td>
								</tr>
							";
						}
					?>
					</tbody>
					</table>  
			</div>
		</div>
		<br />
		<h4>Rekap Keuangan</h4>
		<?php 
			$sql1 = mysql_query("SELECT SUM(jumlah) as jumlah FROM penyaluran WHERE id_akun='2.1.$user[wilayah_bus].' AND MID(tanggal,7,4) ='".date('Y')."' ");
			$dd = mysql_fetch_array($sql1);
			
			$sql2 = mysql_query("SELECT SUM(jumlah) as jumlah_keluar, jenjang FROM penyaluran_bus WHERE YEAR(tanggal) = '".date('Y')."' AND wilayah='$user[wilayah_bus]'");
			$dd2 = mysql_fetch_array($sql2);
		?>
		<table style='width:50%;'>
			<tr>
				<td style='width:30%'>Pemasukan</td>
				<td>: <?php echo $dd['jumlah'];?></td>
			</tr>
			<tr>
				<td style='width:30%'>Pengeluaran</td>
				<td>: <?php echo $dd2['jumlah_keluar'];?></td>
			</tr>
			<tr>
				<td style='width:30%'>Saldo</td>
				<td>: <?php echo $dd['jumlah']-$dd2['jumlah_keluar'];?></td>
			</tr>
		</table>
		<br />
		<br />
	</div>

