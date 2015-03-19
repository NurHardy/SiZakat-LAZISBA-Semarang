<div class="col-12"><br/>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="glyphicon glyphicon-th"></i>
			</span>
			<?php
				$q0 = mysql_query("SELECT * FROM opsi WHERE id_opsi = '4'");
				$p0 = mysql_fetch_array($q0);
			?>
			<h5>Detail penyaluran dan cicilan KUBAH tahun <?php echo $p0['value']?></h5>
		</div>
		<div class="widget-content nopadding">
			<div style='padding:20px;' class='col-12'>
				<?php
					$q0 = mysql_query("SELECT * FROM opsi WHERE id_opsi = '4'");
					$p0 = mysql_fetch_array($q0);
					$q1 = mysql_query("SELECT * FROM penyaluran p, user u WHERE YEAR(p.tanggal) = '$p0[value]' AND p.id_akun = '2.10.' AND p.id_ukm = '$_SESSION[iduser]' AND p.id_ukm = u.id_user");
						echo mysql_error();
					$p1 = mysql_fetch_array($q1);
					$date = explode("-",$p1['tanggal']);
					$tahun = $date[0]+1;
					echo "
						Penyaluran dana KUBAH untuk <strong>$p1[nama]</strong><br/>
						Total dana : <strong>Rp. $p1[jumlah],- </strong><br/>
						Tanggal jatuh tempo : <strong>$date[2]-$date[1]-$tahun</strong";
					
					$q2 = mysql_query("SELECT * FROM penerimaan WHERE id_donatur = '$_SESSION[iduser]'");
					$i = 1;
					echo "
						<p>
						<table class='table table-bordered table-striped table-hover data-table'>
							<thead>
								<tr>
									<th>Cicilan Ke</th>
									<th>Jumlah</th>
									<th>Tanggal</th>
								</tr>
							</thead>
							<tbody>
					";
					while($p2 = mysql_fetch_array($q2)){
						echo 
							"<tr>
								<td>$i</td>
								<td>Rp $p2[jumlah],-</td>
								<td>$p2[tanggal]</td>
							";
						$i++;
					}
					echo "</tbody></table>";
					
					$q3 = mysql_query("SELECT SUM(jumlah) as cicilan FROM penerimaan WHERE id_donatur = '$_SESSION[iduser]'");
					$p3 = mysql_fetch_array($q3);
					echo "<br/>Total Cicilan : ".$p3['cicilan']."<br/>";
					$sisa = $p1['jumlah']-$p3['cicilan'];
					echo "Sisa Tanggungan UKM : Rp.".$sisa.",-";
				?>
			</div>
			<div class='clearfix clear'></div>
			<div style='width:100%;overflow-x:scroll;overflow-y:hidden;'>
			
			</div>
		</div>
	</div>
</div>