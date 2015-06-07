<!DOCTYPE html>
<html lang="en">
	<head>
		<title>LAZISBA</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="icon" href="images/favicon.ico">
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
		<link rel="stylesheet" href="css/fullcalendar.css" />	
		<link rel="stylesheet" href="css/unicorn.main.green.css" />
		<link rel="stylesheet" href="css/unicorn.green.css" class="skin-color" />
        <!-- <link rel="stylesheet" href="css/colorpicker.css" /> -->
        <link rel="stylesheet" href="css/bootstrap-datepicker3.min.css" />
		<link rel="stylesheet" href="css/icheck/flat/blue.css" />
		<link rel="stylesheet" href="css/select2.css" />		
		<link rel="stylesheet" href="css/jquery.treeview.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
		<link rel="stylesheet" href="css/sizakat.css" />
		
		<script src="js/jquery.min.js"></script>
	</head>
	<body>
		<div id="header">
			<h1><a href="">LAZISBA</a></h1>	
			<a id="menu-trigger" href="#"><i class="glyphicon glyphicon-align-justify"></i></a>	
		</div>
		<?php
			/*
			  - bulanan	(1-12)
			  - 3 bulanan (1,5,9)
			  - 6 bulanan (1,7)
			  - 1 tahunan 1
			 */
			 
			 $bln3 = array(1,5,9);
			 $bln6 = array(1,7);
			 
			
			$sql1 = mysqli_query($mysqli, "SELECT * FROM user WHERE level='1'");
			$jml = 0;
			while($d = mysqli_fetch_array($sql1)){
				//cek bulanan
				if($d['jns_donatur'] == '1'){
					$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
					$a = mysqli_fetch_array($sqla);
					if($a['jumlah'] <= 0){
						$jml++;
					}
				}elseif($d['jns_donatur'] == '2'){
					if(in_array(date('m'),$bln3)){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}elseif($d['jns_donatur'] == '3'){
					if(in_array(date('m'),$bln6)){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}elseif($d['jns_donatur'] == '4'){
					if(date('m') == '1'){
						$sqla = mysqli_query($mysqli, "SELECT count(id_penerimaan) as jumlah FROM penerimaan WHERE id_donatur='$d[id_user]' AND MONTH(tanggal) = '".date('m')."'");
						$a = mysqli_fetch_array($sqla);
						if($a['jumlah'] <= 0){
							$jml++;
						}
					}
				}
				
			}
		?>

		<div id="user-nav">
            <ul class="btn-group">
				<?php if($_SESSION['level']==99){?>
				<li class="btn" id="menu-messages"><a href="main.php?s=view_alert" data-target="#menu-messages"><i class="glyphicon glyphicon-calendar"></i> <span class="text">Pengingat</span> <span class="label label-danger"><?php echo $jml;?></span></a>
				
				
				<?php
					include "component/config/koneksi.php";
					$sql = mysqli_query($mysqli, "SELECT COUNT(*) AS jumlah FROM user WHERE level = 1 and MID(tanggal_lahir,4,2) = MONTH(CURDATE())");
					$f = mysqli_fetch_array($sql);
					
					
				?>
				<li class="btn" id="menu-messages"><a href="main.php?s=view_messages"><i class="glyphicon glyphicon-gift"></i> <span class="text">Ulang Tahun</span> <span class="label label-danger"><?php echo $f['jumlah'];?></span></a>
				
				<?php };?>
				<li class="btn"><a title=""><span class="text">Selamat Datang,<?php echo $_SESSION['username'];?></span></a></li>
                <li class="btn"><a title="" href="main.php?s=logout"><i class="glyphicon glyphicon-off"></i> <span class="text">Logout</span></a></li>
            </ul>
        </div>