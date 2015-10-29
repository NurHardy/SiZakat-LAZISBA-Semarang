<?php 
/*
 * form_resetpass_email.php
 * ==> Tampilan form email untuk request reset password
 *
 * AM_SIZ_RSTPASS_EMAIL | Form input email reset password
 * ------------------------------------------------------------------------
 */
	
	$showForm = true;
	$errorMessage = null;
	$successMessage = null;
	
	if(isset($_POST['siz_submit'])){
		// == Catch form fields...
		$sizEmail	= trim($_POST['siz_uemail']);
		
		// Load helper user
		require_once(COMPONENT_PATH."/libraries/helper_user.php");
		
		$participantData = null;
		if (empty($sizEmail)) {
			$errorMessage = "Mohon isi alamat e-mail.";
		} else {
			//regular expression for email validation
			if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $sizEmail)) {
				$errorMessage = 'Alamat e-mail '.htmlentities($sizEmail).' tidak valid.';
			} else {
				// Cek jika e-mail sudah terdaftar
				$participantData = cek_user($sizEmail, 'email');
		
				if ($participantData == null) {
					$errorMessage = 'Alamat e-mail '.htmlentities($sizEmail).' tidak terdaftar.';
				}
			}
		}
		
		if (empty($errorMessage)) {
			$acName			= $participantData['nama'];
			$acEmail		= $participantData['email'];
			$compTag		= "SIZ";
				
			$postFix		 = sprintf('%08d', $participantData['id_user']);
			$requestKey		 = md5(uniqid(rand(), true));
			$requestKey		.= $postFix;
				
			$sendEmailResult_ = false;
			if (!IS_DEBUGGING) {
				require_once(COMPONENT_PATH.'/libraries/helper_email.php');
				$emailMsgBody = get_resetpass_email($acEmail, $acEmail, $requestKey);
				$sendEmailResult_ = siz_send_email(
						"[SIZ LAZISBA] Permintaan Reset Password",
						$emailMsgBody,
						$acEmail,
						$acName,
						$sizConfigEmailHost,
						$sizConfigEmailUser,
						$sizConfigEmailPass,
						$sizConfigEmailFrom,
						$compTag." | RESET"
				);
			} else {
				$sendEmailResult_ = true;
			}
			if (!$sendEmailResult_) {
				$errorMessage = 'Terjadi kesalahan! Email gagal dikirim. Silakan coba beberapa saat '.
					'lagi atau hubungi administrator.';
			} else {
				// Data valid dan buat record request reset
				$clientIPaddr	= $_SERVER['REMOTE_ADDR'];
				$tanggal		= date("Y-m-d H:i:s");
				$tanggalExpired	= strtotime("+1 hour"); // berlaku 1 jam setelah dibuat
				
				$query =	"INSERT INTO request_reset_password (id_user, kunci, tgl_submit,".
						" expired, ip_submit)";
				$query .= sprintf(" VALUES (%d,'%s','%s',%d,'%s')",
						intval($participantData['id_user']),
						$requestKey,
						$tanggal,
						$tanggalExpired,
						$clientIPaddr
				);
				$queryReqResult = mysqli_query($mysqli, $query);
				if ($queryReqResult) {
					$successMessage = "E-mail telah dikirimkan ke <b>".$acEmail.
						"</b>. Silakan cek inbox.";
					$showForm = false;
				} else {
					$errorMessage = "Terjadi kesalahan internal (Query failed).";
				}
			}
		} // end if empty error
	} // end if form submitted

?>
<div class="siz-login">
	<div id="container">
		<div id="logo">
			<img src="images/logo-white.png" alt="Logo LAZISBA"
				style="width: 100px;" />
		</div><?php
if (! empty ( $errorMessage )) {
	echo "<div class=\"alert alert-danger\">\n";
	echo "<span class=\"glyphicon glyphicon-alert\"></span> " . $errorMessage;
	echo "</div>\n";
}
?>
            <div id="loginbox">
<?php
if (!empty($successMessage)) {
	echo "<div class=\"alert alert-success\">\n";
	echo "<span class=\"glyphicon glyphicon-ok\"></span> " . $successMessage;
	echo "</div>\n";
}

if ($showForm) { //=================== IF SHOW FORM ============ ?>
			<form id="loginform" method='post'
				action="index.php?s=reset">

				<p class="form-instruction">Untuk mereset password, silakan ketikkan e-mail Anda yang terdaftar pada sistem.
					Langkah untuk mereset password Anda akan dikirimkan melalui e-mail tersebut.
					Jika menemui kesulitan, silakan hubungi kontak di bawah.</p>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
					<input class="form-control" required type="email"
						placeholder="E-mail" name="siz_uemail" value="<?php 
						if (!empty($sizEmail)) echo htmlspecialchars($sizEmail); ?>" />
				</div>

				<hr />
				<div class="form-actions">
					<div style="margin-bottom: 10px;">
						<div class='pull-left'>
							<a href='index.php?s=login'> <span
								class="glyphicon glyphicon-chevron-left"></span>&nbsp;Kembali
							</a>
						</div>
						
						<div class="divclear"></div>
					</div>

					<button type="submit" class="btn btn-primary btn-block"
						name="login">
						<span class="glyphicon glyphicon-ok"></span>&nbsp;Recover
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