<?php 
/*
 * home.php
 * ==> Tampilan home pengguna SiZakat
 *
 * AM_SIZ_USER_HOME | Tampilan home user sizakat
 * ------------------------------------------------------------------------
 */
?>
<div class='col-12'>
	<h4>Selamat Datang, <b><?php echo $_SESSION['username'];?></b></h4>
	<p style='text-align:justify'>
		Takmir dan Lazisba Online merupakan sebuah sistem yang dapat membantu dalam melakukan
		rekapitulasi penerimaan maupun penyaluran dari zakat yang telah diterima. Dengan adanya
		Takmir dan Lazisba Online ini, masyarakat lebih mudah dalam memperoleh informasi mengenai
		transaksi zakat, sehingga dapat meningkatkan kepercayaan terhadap pengelolaan zakat yang
		ada di Masjid Baiturrahman Semarang. 
	</p>
	<?php if (true) { //============ TODO: taruh privilege di sini... ?>
	<div id="siz_dashboard_loader" style="display:none; text-align:center;">
		<img src="images/loader.32.gif" alt="Loading..." /> Memuat Dasbor...
	</div>
	<div id="siz_dashboard_main">
		<div style="text-align:center;"><a href="#" onclick="return loadDashboard();">
	 		<span class="glyphicon glyphicon-refresh"></span> Muat Ulang
	 	</a></div>
		<div id="siz_dashboard_container">
			Please activate JavaScript.
		</div>
	</div>
	
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.gritter.min.js"></script>
<script>
	var AJAX_URL = "main.php?s=ajax&m=transaksi";
	function loadDashboard() {
		$.ajax({
			type: 'post',
			url: AJAX_URL,
			data: {
				act: 'get.dashboard.html'
			},
			beforeSend: function( xhr ) {
				$("#siz_dashboard_loader").show();
				$("#siz_dashboard_main").hide();
			},
			success: function(data){
				$("#siz_dashboard_container").html(data);
				initDashboard();
			},
			error: function() {
				$("#siz_dashboard_container").html("Terjadi kesalahan.");
			}
		}).always(function(){
			$("#siz_dashboard_main").show();
			$("#siz_dashboard_loader").hide();
		});
		return false;
	}
	function initDashboard() {
		$("#siz_dashboard_container .sparkline_line_good span").sparkline("html", {
			type: "line",
			fillColor: "#B1FFA9",
			lineColor: "#459D1C",
			width: "50",
			height: "24"
		});
		$("#siz_dashboard_container .sparkline_line_bad span").sparkline("html", {
			type: "line",
			fillColor: "#FFC4C7",
			lineColor: "#BA1E20",
			width: "50",
			height: "24"
		});	
		$("#siz_dashboard_container .sparkline_line_neutral span").sparkline("html", {
			type: "line",
			fillColor: "#CCCCCC",
			lineColor: "#757575",
			width: "50",
			height: "24"
		});
		
		$("#siz_dashboard_container .sparkline_bar_good span").sparkline('html',{
			type: "bar",
			barColor: "#459D1C",
			barWidth: "5",
			height: "24"
		});
		$("#siz_dashboard_container .sparkline_bar_bad span").sparkline('html',{
			type: "bar",
			barColor: "#BA1E20",
			barWidth: "5",
			height: "24"
		});	
		$("#siz_dashboard_container .sparkline_bar_neutral span").sparkline('html',{
			type: "bar",
			barColor: "#757575",
			barWidth: "5",
			height: "24"
		});

		// === jQeury Gritter, a growl-like notifications === //
		//$.gritter.add({
		//	title:	'Unread messages',
		//	text:	'You have 9 unread messages.',
		//	image: 	'img/demo/envelope.png',
		//	sticky: false
		//});	
		$('#gritter-notify .normal').click(function(){
			$.gritter.add({
				title:	'Normal notification',
				text:	'This is a normal notification',
				sticky: false
			});		
		});
		
		$('#gritter-notify .sticky').click(function(){
			$.gritter.add({
				title:	'Sticky notification',
				text:	'This is a sticky notification',
				sticky: true
			});		
		});
		
		$('#gritter-notify .image').click(function(){
			var imgsrc = $(this).attr('data-image');
			$.gritter.add({
				title:	'Unread messages',
				text:	'You have 9 unread messages.',
				image: imgsrc,
				sticky: false
			});		
		});

		$('#gritter-notify .light').click(function(){
			$.gritter.add({
				title:	'Normal notification',
				text:	'This is a normal notification',
				sticky: false,
				class_name: 'light'
			});
		})
	    
	    
	    // === Popovers === //
	    var placement = 'bottom';
	    var trigger = 'hover';
	    var html = true;

	    var isiPopPerencanaan = $("#siz_popover_perencanaan").html();
	    $('.popover-perencanaan').popover({
	       placement: placement,
	       content: isiPopPerencanaan,
	       trigger: trigger,
	       html: html   
	    });
	    $('.popover-users').popover({
	       placement: placement,
	       content: '<span class="content-big">1433</span> <span class="content-small">Total Users</span><br /><span class="content-big">0</span> <span class="content-small">Registered Today</span><br /><span class="content-big">0</span> <span class="content-small">Registered Yesterday</span><br /><span class="content-big">16</span> <span class="content-small">Registered Last Week</span>',
	       trigger: trigger,
	       html: html   
	    });
	    $('.popover-orders').popover({
	       placement: placement,
	       content: '<span class="content-big">8650</span> <span class="content-small">Total Orders</span><br /><span class="content-big">29</span> <span class="content-small">Pending Orders</span><br /><span class="content-big">32</span> <span class="content-small">Orders Today</span><br /><span class="content-big">64</span> <span class="content-small">Orders Yesterday</span>',
	       trigger: trigger,
	       html: html   
	    });
	    $('.popover-tickets').popover({
	       placement: placement,
	       content: '<span class="content-big">2968</span> <span class="content-small">All Tickets</span><br /><span class="content-big">48</span> <span class="content-small">New Tickets</span><br /><span class="content-big">495</span> <span class="content-small">Solved</span>',
	       trigger: trigger,
	       html: html   
	    });
	}
	function initPage() {
		loadDashboard();
	}
</script>
	<?php } //================ ?>
</div>