<div class="post post-page">
	<a href="#" ></a>
	<div class="post-content">
		<?php
			
			$q1 = $mysqli->query("SELECT * FROM informasi WHERE status='$_GET[status]'ORDER BY tanggal DESC LIMIT 0,5 ");
			$i=1;
			while($p1 = $q1->fetch_array(MYSQLI_ASSOC)) {
				$is = explode(" ",$p1['isi']);
				
				if(count($is) >= 50){
				
				$f = "";
				for($i=0;$i<50;$i++){
					$f .= $is[$i]." ";
				}
				}else{
					$f = implode(" ",$is);
				}
			
			if(count($is) >= 50){
				echo "				
				<div class='post-title'><h2>".$p1['judul']."</h2></div>
				<div class='post-meta'><span><i class='mini-ico-calendar'></i>".$p1['tanggal']."</span> <span><i class='mini-ico-user'></i>By <a href='#'>admin</a></span></div>
				<div class='post-description'>
					".$f."  .... <a href='index.php?s=detail_info&amp;id=".$p1['id_informasi']."' >read more</a>
				</div><br/>	
				";
			
			}else if (count($is) < 50){
				echo "				
				<div class='post-title'><h2>".$p1['judul']."</h2></div>
				<div class='post-meta'><span><i class='mini-ico-calendar'></i>".$p1['tanggal']."</span> <span><i class='mini-ico-user'></i>By <a href='#'>admin</a></span></div>
				<div class='post-description'>
					".$f."  
				</div><br/>	
				";
			
			}
			
			
			$i++;
			}
			
		?>
	</div>
</div>
