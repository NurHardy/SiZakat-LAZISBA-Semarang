<?php
	include"component/config/koneksi.php";
	function setTree($idParent){
		global $mysqli;
	
		$sql = mysqli_query($mysqli, "SELECT * FROM akun WHERE idParent='".$idParent."' ORDER BY idakun ASC");
		if(mysqli_num_rows($sql) > 0){
			echo "<ul>";
			while($dd = mysqli_fetch_array($sql)){
				if($idParent != 0){
					$edit = "<a href='main.php?s=editakun&id=$dd[idakun]'><i class='glyphicon glyphicon-pencil'></i></a>";
				}else{
					$edit = "";
				}
				echo "<li title='".$dd['keterangan']."'><span >".$dd['kode'].' '.$dd['namaakun']."&nbsp;&nbsp;$edit</span>";
				setTree($dd['idakun']);
				echo "</li>";
			}
			echo "</ul>";
		}
	}
?>

<div class="col-12">
      <div class="widget-box">
        <div class="box gradient">
          <div class="widget-title">
            <h5>
            <i class="icon-book"></i><span>Daftar Akun</span>
            </h5>
          </div>
          <div class="widget-content nopadding">
			<ul id="browser" class="filetree">
				<?php
					setTree(0);
				?>
			</ul>
		  </div>
		</div>
	</div>
</div>