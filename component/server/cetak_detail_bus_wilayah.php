	<style>
	page{
		font-family:times;
	}
	
	.table1{
		border:1px solid #000;
		border-collapse:collapse;=
	}
	
	.table1 tr .td1{
		border-collapse:collapse;
		border:1px solid #000;
		padding:8px;
		width:90px;
		height:50px;
	}
	
	.table1 tr .td2{
		border-collapse:collapse;
		border:1px solid #000;
		padding:8px;
		width:500px;
		height:50px;
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
				
			<h4>DAFTAR PENERIMA BUS WILAYAH <?php echo $_GET['wilayah']?></h4>
				<table border="1" class="table1">
				<tr>
					<th align="center">FOTO</th>
					<th align="center">BIODATA</th>
				</tr>
					<?php
						include "../config/koneksi.php";
						$wilayah = $_GET['wilayah'];
						$query = mysqli_query($mysqli, "select * from penerima_bus WHERE wilayah = $wilayah");
						$i = 1;
						
						
						while($pecah = mysqli_fetch_array($query)){
						if($pecah['jenjang'] == 1){
							$j = "SD";
						}else if($pecah['jenjang'] == 2){
							$j = "SMP";
						}else if($pecah['jenjang'] == 3){
							$j = "SMA";
						}
						
						if($pecah['foto'] == ''){
							$p = '';
						}else{
						
							$p = "<img src='../../img/bus/$pecah[foto]' style='width:120px; !important height:15px !important; '/>";
						}
						echo "
							<tr>
								<td class=\"td1\">$p</td>
								<td class=\"td2\">
									<b>Nama</b>      : $pecah[nama]<br/> <br/>
									<b>Nama Ayah</b> : $pecah[ayah]<br/> <br/>
									<b>Nama Ibu</b>  : $pecah[ibu]<br/> <br/>
									<b>Tanggal Lahir </b>  : $pecah[tanggal_lahir]<br/> <br/>
									<b>Alamat </b>  : $pecah[alamat]<br/> <br/>
									
								
								</td>
						</tr>";
						
							$i++;
						}
						
					
					?>
					
					</table>  
</page>
