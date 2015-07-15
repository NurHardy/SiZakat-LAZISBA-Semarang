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
		<script src="js/unicorn.form_common.js"></script>
		
		<script src="js/sizakat.js"></script>
		<!-- 
		<script src="js/fullcalendar.min.js"></script>
		
		<script src="js/bootstrap-colorpicker.js"></script>
		
		
		<script src="js/unicorn.tables.js"></script>
		
		<script src="js/jquery.treeview.js"></script>
		<script src="js/unicorn.tree.js"></script>
		<script src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>  -->
		<script type="text/javascript">
			$(document).ready(function(){
				if (typeof(init_page) === 'function') init_page();
				$(".siz-use-select2").select2();
			});
			tinyMCE.init({
				// General options
				//mode : "textareas",
				theme : "advanced",
				selector : "textarea.editme",
				plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,image,|,insertdate,inserttime,preview,|,forecolor",
				theme_advanced_buttons2 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				// Example content CSS (should be your site CSS)
				// using false to ensure that the default browser settings are used for best Accessibility
				// ACCESSIBILITY SETTINGS
				content_css : false,
				// Use browser preferred colors for dialogs.
				browser_preferred_colors : true,
				detect_highcontrast : true,

				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "lists/template_list.js",
				external_link_list_url : "lists/link_list.js",
				external_image_list_url : "lists/image_list.js",
				media_external_list_url : "lists/media_list.js",

				// Style formats
				style_formats : [
					{title : 'Bold text', inline : 'b'},
					{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
					{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
					{title : 'Example 1', inline : 'span', classes : 'example1'},
					{title : 'Example 2', inline : 'span', classes : 'example2'},
					{title : 'Table styles'},
					{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
				],

				// Replace values for the template plugin
				template_replace_values : {
					username : "Some User",
					staffid : "991234"
				}
			});
			
			$(selector).chosen(config[selector]);
			
		</script>
</BoDy ><!-- Menghindari injeksi < /body > pada provider telkom -->
</hTmL >