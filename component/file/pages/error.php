<div class="row">
	<div class="alert alert-danger" role="alert">
		<p style="font-size: 1.1em;">
			<span class="glyphicon glyphicon-exclamation-sign"></span>
			Maaf, permintaan Anda tidak bisa diproses sistem. Deskripsi kesalahan:</p>
		<div>
			<?php
				if (isset($errorDescription)) {
					echo $errorDescription;
				}
			?>
		</div>
	</div>
</div>