<?php if (empty($isSimplePage)) { //=== KHUSUS BUKAN HALAMAN LOGIN ======= ?>
	</div>
	<!-- 960 Container / End -->
</div>
<!-- Wrapper / End -->


<!-- Footer
================================================== -->

<!-- Footer Start -->
<div id="footer">
	<!-- 960 Container -->
	<div class="container">
	
		<!-- About -->
		<div class="five columns">
			<div class="footer-headline"><h4>About Us</h4></div>
			<p><?php echo $s[5]; ?></p>
		</div>
		
		<!-- Useful Links -->
		<div class="five columns">
			<div class="footer-headline"><h4>Link</h4></div>
			<ul class="links-list">
				<?php
					$query = $mysqli->query("SELECT * FROM opsi WHERE name = 'link'");
					$d= $query->fetch_array(MYSQLI_ASSOC);
					
					
					$ket = nl2br(trim($d['value']));
					$pres = explode('<br />',$ket); 

					for($i=0;$i<count($pres);$i++){
						$dt = explode('|',$pres[$i]);
						echo"<li><a href=\"".$dt[1]."\">".$dt[0]."</a></li>";
							
					}
				?>
			</ul>
		</div>
		<div class="five columns">
			<div class="footer-headline"><h4>Peta Lokasi Lazisba</h4></div>
			<iframe width="100%" height="150%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.id/maps?f=q&amp;source=s_q&amp;hl=id&amp;geocode=&amp;q=simpang+lima,+semarang&amp;aq=&amp;sll=-6.914709,113.58324&amp;sspn=6.583894,10.821533&amp;ie=UTF8&amp;hq=simpang+lima,&amp;hnear=Semarang,+Jawa+Tengah&amp;t=m&amp;fll=-6.989133,110.425193&amp;fspn=0.006432,0.010568&amp;st=107658235038825379151&amp;rq=1&amp;ev=zi&amp;split=1&amp;ll=-6.989069,110.422516&amp;spn=0.000799,0.00228&amp;z=18&amp;output=embed"></iframe><br /><small><a href="https://maps.google.co.id/maps?f=q&amp;source=embed&amp;hl=id&amp;geocode=&amp;q=simpang+lima,+semarang&amp;aq=&amp;sll=-6.914709,113.58324&amp;sspn=6.583894,10.821533&amp;ie=UTF8&amp;hq=simpang+lima,&amp;hnear=Semarang,+Jawa+Tengah&amp;t=m&amp;fll=-6.989133,110.425193&amp;fspn=0.006432,0.010568&amp;st=107658235038825379151&amp;rq=1&amp;ev=zi&amp;split=1&amp;ll=-6.989069,110.422516&amp;spn=0.000799,0.00228&amp;z=18" style="color:#0000FF;text-align:left">Lihat Peta Lebih Besar</a></small>
		</div>
		
		<!-- Footer / Bottom -->
		<div class="sixteen columns">
			<div id="footer-bottom">
				&copy; Copyright <?php echo date('Y');?> by <a href="#">Takmir &amp; Lazisba Online</a>. All rights reserved.
				<div id="scroll-top-top"><a href="#"></a></div>
			</div>
		</div>

	</div>
	<!-- 960 Container / End -->

</div>
<!-- Footer / End -->
<?php } //================== END IF ============================== ?>

</BoDy ><!-- Menghindari injeksi < /body > pada provider telkom -->
</hTmL >