<?php
	include "component/config/koneksi.php";
?>		
		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Kirimkan SMS</h5>
				</div>
				<div class="widget-content nopadding" >
					<p style='padding:10px'>Fitur Kirim SMS hanya dapat digunakan pada PC/Komputer yang sudah diatur untuk melakukan pengiriman sms.</p>
					<?php 
						if(ISSET($_GET['sms'])){
							$s = $_GET['sms']; 
							$sms = "";
							for($i=0;$i<count($s);$i++){
								if($i < (count($s)-1)){
									$sms .= $s[$i].";";
								}else{
									$sms .= $s[$i];
								}
							}
						}else{
							$sms = "";
						}
					?>
					<iframe class='fream' src='http://localhost/smsapp/index.php?sms=<?php echo $sms; ?>&msg=<?php echo (ISSET($_GET['msg']))?$_GET['msg']:"";?>' style='padding-top:30px;width:100%;height:450px;border:0px solid #000;'>

</iframe>
				</div>
			</div>						
		</div>
	