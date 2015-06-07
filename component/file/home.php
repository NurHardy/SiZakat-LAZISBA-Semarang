<div class='col-12'>
	<h4>Selamat Datang, <b><?php echo $_SESSION['username'];?></b></h4>
	<p style='text-align:justify'>
		Takmir dan Lazisba Online merupakan sebuah sistem yang dapat membantu dalam melakukan rekapitulasi penerimaan maupun penyaluran dari zakat yang telah diterima. Dengan adanya Takmir dan Lazisba Online ini, masyarakat lebih mudah dalam memperoleh informasi mengenai transaksi zakat, sehingga dapat meningkatkan kepercayaan terhadap pengelolaan zakat yang ada di Masjid Baiturrahman Semarang. 
	</p>
	<?php if (true) { //============ TODO: taruh privilege di sini... ?>
	<div class="row">
		<div class="col-xs-12 center" style="text-align: center;">					
			<ul class="stat-boxes">
				<li class="popover-visits">
					<div class="left sparkline_bar_good"><span>2,4,9,7,12,10,12</span><i class="fa fa-double-angle-up"></i> +10%</div>
					<div class="right">
						<strong>36094</strong>
						Visits
					</div>
				</li>
				<li class="popover-users">
					<div class="left sparkline_bar_neutral"><span>20,15,18,14,10,9,9,9</span><i class="fa fa-minus"></i> 0%</div>
					<div class="right">
						<strong>1433</strong>
						Users
					</div>
				</li>
				<li class="popover-orders">
					<div class="left sparkline_bar_bad"><span>3,5,9,7,12,20,10</span><i class="fa fa-double-angle-down"></i> -50%</div>
					<div class="right">
						<strong>8650</strong>
						Orders
					</div>
				</li>
				<li class="popover-tickets">
					<div class="left sparkline_line_good"><span>12,6,9,23,14,10,17</span><i class="fa fa-double-angle-up"></i> +70%</div>
					<div class="right">
						<strong>2968</strong>
						Tickets
					</div>
				</li>
			</ul>
		</div>	
	</div>
	<div class="row">
		<div class="col-xs-12 center" style="text-align: center;">					
			<ul class="quick-actions">
				<li>
					<a href="#">
						<i class="icon-cal"></i>
						Manage Events
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-shopping-bag"></i>
						Manage Orders
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-database"></i>
						Manage DB
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-people"></i>
						Manage Users
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-lock"></i>
						Security
					</a>
				</li>
				<li>
					<a href="#">
						<i class="icon-piechart"></i>
						Statistics
					</a>
				</li>
			</ul>
		</div>	
	</div>
	<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.gritter.min.js"></script>
<script>

$(document).ready(function(){
	
	$(".sparkline_line_good span").sparkline("html", {
		type: "line",
		fillColor: "#B1FFA9",
		lineColor: "#459D1C",
		width: "50",
		height: "24"
	});
	$(".sparkline_line_bad span").sparkline("html", {
		type: "line",
		fillColor: "#FFC4C7",
		lineColor: "#BA1E20",
		width: "50",
		height: "24"
	});	
	$(".sparkline_line_neutral span").sparkline("html", {
		type: "line",
		fillColor: "#CCCCCC",
		lineColor: "#757575",
		width: "50",
		height: "24"
	});
	
	$(".sparkline_bar_good span").sparkline('html',{
		type: "bar",
		barColor: "#459D1C",
		barWidth: "5",
		height: "24"
	});
	$(".sparkline_bar_bad span").sparkline('html',{
		type: "bar",
		barColor: "#BA1E20",
		barWidth: "5",
		height: "24"
	});	
	$(".sparkline_bar_neutral span").sparkline('html',{
		type: "bar",
		barColor: "#757575",
		barWidth: "5",
		height: "24"
	});

	// === jQeury Gritter, a growl-like notifications === //
	$.gritter.add({
		title:	'Unread messages',
		text:	'You have 9 unread messages.',
		image: 	'img/demo/envelope.png',
		sticky: false
	});	
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

    $('.popover-visits').popover({
       placement: placement,
       content: '<span class="content-big">36094</span> <span class="content-small">Total Visits</span><br /><span class="content-big">220</span> <span class="content-small">Visits Today</span><br /><span class="content-big">200</span> <span class="content-small">Visits Yesterday</span><br /><span class="content-big">5677</span> <span class="content-small">Visits in This Month</span>',
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

    $('#bootbox-confirm').click(function(e){
    	e.preventDefault();
    	bootbox.confirm("Are you sure?", function(result) {
    		var msg = '';
    		if(result == true) {
    			msg = 'Yea! You confirmed this.';
    		} else {
    			msg = 'Not confirmed. Don\'t worry.';
    		}
			bootbox.dialog({
				message: msg,
				title: 'Result',
				buttons: {
					main: {
						label: 'Ok',
						className: 'btn-default'
					}
				}
			});
		}); 
    });
    $('#bootbox-prompt').click(function(e){
    	e.preventDefault();
    	bootbox.prompt("What is your name?", function(result) {
			if (result !== null && result !== '') {
				bootbox.dialog({
					message: 'Hi '+result+'!',
					title: 'Welcome',
					buttons: {
						main: {
							label: 'Close',
							className: 'btn-danger'
						}
					}
				});
			}
		});
    });
    $('#bootbox-alert').click(function(e){
    	e.preventDefault();
    	bootbox.alert('Hello World!');
    });
    
});
</script>
	<?php } //================ ?>
</div>