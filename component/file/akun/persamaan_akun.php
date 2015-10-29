<?php
	require_once COMPONENT_PATH . "/file/akun/helper_akun.php";

	$accountTree = getAccountArray(2, 0);
	function generateAccountRows($accountArray) {
		if (($accountArray==null) || (!is_array($accountArray))) return;
		
		foreach ($accountArray as $itemAccount) {
			if ($itemAccount['childs'] == 0) {
				$idAkunPengeluaran = $itemAccount['id'];
				echo "
				<div class='row' style='margin: 5px 0px;'>
				<div class='col-md-6'>
					<div class='out_container'>
						<b>{$itemAccount['code']}</b> " . htmlspecialchars ( $itemAccount ['label'] ) . "</div>
					</div>
				<div class='col-md-6' style='border-left:solid 1px #1F7044;'><div class='siz_perimbangan_boxsumber' id='siz_akunkeluar_{$idAkunPengeluaran}'>
						<div class='item_container'>
				";
				echo getHTMLSumberDana ( $itemAccount ['code'] );
				echo "
						</div>
						<div class='siz_item_control'>
							<a href=\"#\" onclick=\"return tampilFormTambah({$idAkunPengeluaran});\">
							<span class='glyphicon glyphicon-plus'></span> Tambah Sumber Dana</a>
						</div><div class='siz_form_tambahsumber' style='display:none;'></div>
							<span class='loading' style='display:none;'>Loading...</span>
						</div>
					</div>
				</div>
				";
				
			} else {
				echo "<div class='siz_pers_parent_ctr'>\n";
				echo "
					<div class='parent_title'><b>{$itemAccount['code']}</b>".htmlspecialchars($itemAccount ['label'])."
						<span class='glyphicon glyphicon-triangle-bottom'></span></div>		
				";
				if (is_array($itemAccount['data'])) {
					generateAccountRows($itemAccount['data']);
				}
				echo "</div>\n";
			}
		} // End foreach
		echo "</div>";
	}
?>
<!-- AM_SIZ_PERSMN_AKUN | Persamaan Akun -->
<link rel="stylesheet" href="css/jquery.gritter.css" />
<script>
var AJAX_URL = "main.php?s=ajax&m=akun";
</script>
<script src="js/jquery.gritter.min.js"></script>
<script src="js/sizakat/persamaan_akun.js"></script>

<div class="col-12">
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon"> <i class="glyphicon glyphicon-th"></i>
			</span>
			<h5>Perimbangan Akun</h5>
		</div>
		<div class="widget-content nopadding">
			<h4 style='margin-left: 10px;'>Akun Harian</h4>
			<div class='row'>
				<div class='col-md-8'>
<?php
	generateAccountRows($accountTree);
?>
				</div>
			</div>
		</div>
	</div>
</div>

