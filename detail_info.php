<div class="post post-page">
	<a href="#" class=""></a>
	<div class="post-content">
		<?php
			$q1 = $mysqli->query("SELECT * FROM informasi WHERE id_informasi='".$_GET['id']."'");
			$i = 1;
			while($p1 = $q1->fetch_array(MYSQLI_ASSOC)) {
				echo "
					<div class='post-title'><h2>".$p1['judul']."</h2></div>
					<div class='post-meta'><span><i class='mini-ico-calendar'></i>".$p1['tanggal']."</span> <span><i class='mini-ico-user'></i>Oleh <a href='#'>admin</a></span> <span><i class='mini-ico-comment'></i></div>
					<div class='post-description'>
						".$p1['isi']."
					</div>
				";
				$i++;
			}
		?>
	</div>
</div>
