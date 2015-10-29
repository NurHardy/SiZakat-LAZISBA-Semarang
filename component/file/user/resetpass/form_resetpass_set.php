<?php
/*
 * form_resetpass_set.php
 * ==> Tampilan form reset password (set password baru)
 *
 * AM_SIZ_RSTPASS_SET | Form set password baru
 * ------------------------------------------------------------------------
 */

	// Load helper user
	require_once(COMPONENT_PATH."/libraries/helper_user.php");

	$keepCheck = true;
	$showForm = true;
	$errorMessage = null;
	$successMessage = null;
	
	$sizUserEmail = "";
	
	//==================== Cek Form
	$formCheck = $_POST['siz_submit'];
	$data['submitErrors'] = array();
	
	//==================== GET REQUEST KEY
	$sizReqKey = "-";
	if (isset($_POST['siz_resetkey'])) {
		$sizReqKey	= trim($_POST['siz_resetkey']);
	} else if (isset($_GET['key'])) {
		$sizReqKey	= trim($_GET['key']);
	} else {
		$errorMessage = "Request key expected.";
		$showForm = false;
		$keepCheck = false;
	}
	
	// Panjang kunci request adalah 40 karakter.
	if (strlen($sizReqKey) != 40) {
		$errorMessage = "Invalid key.";
		$showForm = false;
		$keepCheck = false;
	}
	if ($keepCheck) {
		// Cek dan validasi key
		$reqCheckResult = null; // Awalnya tidak ada error
		
		$queryCekReq = sprintf("SELECT * FROM request_reset_password WHERE kunci='%s'",
				mysqli_real_escape_string($mysqli, $sizReqKey));
		
		$resultQueryCekReq = mysqli_query($mysqli, $queryCekReq);
		$queryCount++;
		
		$requestData = mysqli_fetch_assoc($resultQueryCekReq);
		if ($requestData == null) {
			$reqCheckResult = ("Request key not found!");
		} else {
			if ((strtotime("now") > $requestData['expired']) ||
				($requestData['status'] == 0)) {
					$reqCheckResult = ("Link sudah tidak aktif! Silakan request ulang.");
				}
		}
		if ($reqCheckResult != null) {
			$errorMessage = $reqCheckResult;
			$showForm = false;
			$keepCheck = false;
		} else {
			$participantData = cek_user($requestData['id_user']);
			if ($participantData == null) {
				$errorMessage = "Data peserta tidak ditemukan! Mungkin karena telah dihapus.";
				$showForm = false;
			} else {
				$sizUserName	= $participantData['username'];
				$sizUserEmail	= $participantData['email'];
			}
		}
	}
	if ($keepCheck && isset($formCheck)) {
		// == Catch form fields...
		$f_pass1	= ($_POST['siz_pass1']);
		$f_pass2	= ($_POST['siz_pass2']);
	
		if (empty($f_pass1) || (empty($f_pass2))) {
			$errorMessage = ("Mohon isi password baru dan konfirmasi.");
		} else {
			if (md5($f_pass1) != md5($f_pass2)) {
				$errorMessage = ("Password baru dan konfirmasi tidak sama.");
	
			} else {
				$actResult = null;
				$actResult = set_user_password($requestData['id_user'], $f_pass1);
					
				if ($actResult == null) $errorMessage = "Terjadi kesalahan internal.";
				else {
					// Apply request...
					$clientIPaddr	= $_SERVER['REMOTE_ADDR'];
					$tanggal		= date("Y-m-d H:i:s");
					$queryApplyReq = sprintf("UPDATE request_reset_password SET tgl_apply='%s', ".
						"ip_apply='%s', status=0 WHERE id_reset=%d",
							$tanggal, $clientIPaddr, $requestData['id_reset']);
					mysqli_query($mysqli, $queryApplyReq);
					
					$successMessage = "Password untuk <b>".htmlspecialchars($sizUserEmail).
						"</b> berhasil diperbaharui.";
					$showForm = false;
				}
			}
		} // end if data tidak kosong
	} else {
		//if ($keepCheck)
		//	akses_request($requestData['f_id']);
	}
?>
<div class="siz-login">
	<div id="container">
		<div id="logo">
			<img src="images/logo-white.png" alt="Logo LAZISBA"
				style="width: 100px;" />
		</div>
<div id="loginbox">
<?php

if (! empty ( $errorMessage )) {
	echo "<div class=\"alert alert-danger\">\n";
	echo "<span class=\"glyphicon glyphicon-alert\"></span> " . $errorMessage;
	echo "</div>\n";
}

if (! empty ( $successMessage )) {
	echo "<div class=\"alert alert-success\">\n";
	echo "<span class=\"glyphicon glyphicon-ok\"></span> " . $successMessage;
	echo "</div>\n";
}

if ($showForm) { // =================== IF SHOW FORM ============ ?>
			<p class="form-instruction">Silakan masukkan password baru Anda:</p>
			<form id="loginform" method='post' class="form-horizontal"
			action="<?php
	echo htmlspecialchars ( "index.php?s=reset&action=do&key=" . $sizReqKey )?>">
			
			<div class="input-group">
				<span class="input-group-addon"><i
					class="glyphicon glyphicon-user"></i></span> <input
					class="form-control" readonly type="text" placeholder="Username"
					name="siz_uname" title="Username"
					value="<?php if (! empty ( $sizUserName )) echo htmlspecialchars ( $sizUserName ); ?>" />
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i
					class="glyphicon glyphicon-envelope"></i></span> <input
					class="form-control" readonly type="email" placeholder="E-mail"
					name="siz_uemail" title="E-mail"
					value="<?php if (! empty ( $sizUserEmail )) echo htmlspecialchars ( $sizUserEmail ); ?>" />
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i
					class="glyphicon glyphicon-lock"></i></span> <input
					class="form-control" required type="password" placeholder="Password Baru"
					name="siz_pass1" value="" />
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i
					class="glyphicon glyphicon-lock"></i></span> <input
					class="form-control" required type="password" placeholder="Ketik Ulang"
					name="siz_pass2" value="" />
			</div>
			<hr />
			<div class="form-actions">
				<div style="margin-bottom: 10px;">
					<div class="divclear"></div>
				</div>

				<button type="submit" class="btn btn-primary btn-block" name="login">
					<span class="glyphicon glyphicon-ok"></span>&nbsp;Reset Password
				</button>
			</div>
			<input type="hidden" name="siz_submit" value="login" />
		</form>
		<p>
			Kontak: <a href="mailto:admin@siz.lazisba.org">admin@siz.lazisba.org</a>
		</p>
<?php } else { //=========================== ELSE IF SHOW FORM ======= ?>
			<a href="index.php"><span class="glyphicon glyphicon-home"></span>&nbsp;Beranda</a> |
			<a href="index.php?s=login"><span class="glyphicon glyphicon-lock"></span>&nbsp;Login</a>
<?php } //================================== END IF SHOW FORM ======== ?>
			<div class="divclear"></div>
		</div>
	</div>
</div>