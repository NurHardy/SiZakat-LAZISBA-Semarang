		<div class="row">
			<div id="footer" class="col-12">
				Copyright &copy; <?php echo date('Y')?> - UNDIP - <?php echo SIZ_VERSION; ?><br><span id="siz-benchmark"><?php 
		$timeEnd = microtime(true);

		//dividing with 60 will give the execution time in minutes other wise seconds
		$execution_time = round(($timeEnd - $timeStart),4);

		//execution time of the script
		echo 'Halaman diproses dalam '.$execution_time.' detik. ';
		if ($queryCount > 0) echo " (<b>".$queryCount."</b> query)";
	?></span>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="siz_overlaydlg_formctr" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<div id="siz_overlaydlg_loading">
					<img src="images/loader.32.gif" alt="Loading..." /> Memuat Konten...
				</div>
				<form action='#submit' method='POST' id='siz_overlaydlg_form' class="form-horizontal">
				<div id="siz_overlaydlg_body">
		      
		    	</div>
		    	</form>
		    </div>
		  
		  </div>
		</div>
		
		<!-- Loading overlay -->
		<div id="siz_loading_overlay">
			<div id="siz_loading_infobox">
				<img src="images/loader.gif" alt="Loading" />
				<span id="siz_loading_msg">Loading...</span>
			</div>
		</div>

		<!-- Basic Scripts -->
		<script src="js/jquery-ui.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.jpanelmenu.min.js"></script>
		<script src="js/select2.min.js"></script>
		<script src="js/bootstrap-datepicker.min.js"></script>
		<script src="js/jquery.icheck.min.js"></script>
		
		<script src="js/unicorn.js"></script>
		<script src="js/sizakat.js"></script>
		<!-- 
		<script src="js/fullcalendar.min.js"></script>
		
		<script src="js/bootstrap-colorpicker.js"></script>
		
		
		<script src="js/unicorn.tables.js"></script>
		
		<script src="js/jquery.treeview.js"></script>
		<script src="js/unicorn.tree.js"></script>
		<script src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>  -->
		
</BoDy ><!-- Menghindari injeksi < /body > pada provider telkom -->
</hTmL >