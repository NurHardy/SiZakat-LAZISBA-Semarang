<style>
	page{
		font-family:times;
	}
	
	.table1{
		border:1px solid #000;
		border-collapse:collapse;
		width:55px;
	}
	
	.table1 tr td{
		border-collapse:collapse;
		border:1px solid #000;
		padding:8px;
	}
	
	.table2 tr td{
		
		font-family:times;
		padding:5px;
	}
	
	.table1 tr th{
		color: #FFF;
		background:#565656;	
		border-collapse:collapse;
		border:1px solid #000;
		padding:9px;
		word-wrap:break-word;
	}
	
</style>

<page style="font-size: 11pt">
	<h3>Detail Data Pribadi Penerima Zakat</h3>
	<table class="table2">
		<?php
			include "../config/koneksi.php";
			$id = $_GET['id'];
			$q1 = mysqli_query($mysqli, "SELECT * FROM penerima_bus WHERE id_penerima = '$id'");
			$p1 = mysqli_fetch_array($q1);
			$ket = nl2br($p1['keterangan']);
			$pres = explode('<br />',$ket); 
			if($p1['jenjang'] == '1'){
				$pend = "SD";
			}else if($p1['jenjang'] == '2'){
				$pend = "SMP";
			}else if($p1['jenjang'] == '3'){
				$pend = "SMA";
			}
		?>
		<tr>
			<td>Nama </td>
			<td>: <?php echo $p1['nama']; ?></td>
		</tr>
		
		<tr>
			<td>Tangal Lahir</td>
			<td>: <?php echo $p1['tanggal_lahir']; ?></td>
		</tr>
		
		<tr>
			<td>Pendidikan</td>
			<td>: <?php echo $pend ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>: <?php echo $p1['alamat']; ?></td>
		</tr>
		<tr>
			<td>Hobi</td>
			<td>: <?php echo $p1['hobi']; ?></td>
		</tr>
		<tr>
			<td>Nama Ayah</td>
			<td>: <?php echo $p1['ayah']; ?></td>
		</tr>
		<tr>
			<td>Nama Ibu</td>			
			<td>: <?php echo $p1['ibu']; ?></td>
		</tr>
		<tr>
			<td>Alamat Orang Tua</td>
			<td>: <?php echo $p1['alamatortu']; ?></td></tr>
		<tr>
			<td>Pekerjaan Ayah</td>
			<td>: <?php echo $p1['pekayah']; ?></td>
		</tr>
		<tr>
			<td>Pekerjaan Ibu</td>
			<td>: <?php echo $p1['pekibu']; ?></td>
		</tr>
		
		<tr>
			<td>Penghasilan</td>
			<td>: <?php echo $p1['penghasilan']; ?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td>: <?php echo $p1['status']; ?></td>
		</tr>
	</table>

	<h3>Detail Prestasi Penerima Zakat</h3>
	<table width="" border="1px" class="table1">
	<tr >
		<th colspan="3" align="center">HAFALAN</th>
		<th colspan="3" align="center">MINAT dan BAKAT</th>
		<th colspan="3" align="center">RAPOR</th>
	
	</tr>
	
	<tr >
		<th>No.</th>
		<th>Al-Qur'an</th>
		<th>Doa</th>
				
		<th>No.</th>
		<th>Nama Lomba</th>
		<th>Keterangan</th>
				
		<th>Rata-Rata</th>
		<th>Peringkat</th>
	</tr>
	
	<tr width=""></tr>
	
		<?php 
			include "../config/koneksi.php";
			$id = $_GET['id'];
			$q1 = mysqli_query($mysqli, "SELECT * FROM prestasi WHERE id_penerima = '$id'");
			$p1 = mysqli_fetch_array($q1);
			
			$a = nl2br($p1['alquran']);
			$a1 = explode('<br />',$a);
			
			$b = nl2br($p1['doa']);
			$b1 = explode('<br />',$b);
			
			$c = nl2br($p1['minat']);
			$c1 = explode('<br />',$c);
			
			
			$d = nl2br($p1['rapor']);
			$d1 = explode('<br />',$d);
			
			$max = 10;
			
			$max = max(array(count($a1),count($b1),count($c1),count($d1)));
			
			for($i=0;$i<$max;$i++){
			
				if(isset($a1[$i])){
					$aa = $a1[$i];
				}else{
					$aa = "";
				}
				
				if(isset($b1[$i])){
					$bb = $b1[$i];
				}else{
					$bb = "";
				}
				
				if(isset($c1[$i])){
				
					$c = explode('|',$c1[$i]);
					$cc1 = isset($c[0]);
					$cc2 = isset($c[1]);
				}else{
					$cc1 = "";
					$cc2 = "";
				}
				
				if(isset($d1[$i])){
					$d = explode('|',$d1[$i]);
					$dd1 = isset($d[0]);
					$dd2 = isset($d[1]);
				}else{
					$dd1 = "";
					$dd2 = "";
				}
				$xxx = str_split($bb,10);
				$bb = implode('<br />',$xxx);
				
				
				$xxx1 = str_split($cc1,10);
				$cc1 = implode('<br />',$xxx1);
				
				
				$xxx2 = str_split($cc2,10);
				$cc2 = implode('<br />',$xxx2);
				
				
				echo"<tr width=\"\">
					<td>".($i+1)."</td>
					<td>".$aa."</td>
					<td>".$bb."</td>
					
					<td>".($i+1)."</td>
					<td>".$cc1."</td>
					<td>".$cc2."</td>
					
					<td>".$dd1."</td>
					<td>".$dd2."</td>
				
				</tr>";
			}
		?>
	</table>
</page>
				