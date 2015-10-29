<?php

function get_resetpass_email($accName, $accEmail, $reqKey, $noHead = false) {
	$resetLink = "/index.php?s=reset&action=do&key=".$reqKey;
	if (!$noHead) $body_ = "
		<html>
			<title>Permintaan Reset Password</title>
			<style>
				body {font-family: sans-serif; color: #1B253F; font-size: 12pt;}
				a {color: #00496D;text-decoration: none;}
			</style>
			<body>";
	$body_ .= "
					<p>Yth. Bapak/Ibu/Saudara/i <b>".htmlentities($accName)."</b>,</p>
					<p>Sistem kami menerima permintaan reset password untuk akun dengan e-mail <u>".$accEmail."</u>. Untuk melanjutkan
					proses tersebut, silakan klik link berikut:</p>
					<p><a href='"._complete_url($resetLink)."' target='_blank'>"._complete_url($resetLink)."</a></p>
					<p>Link tersebut hanya berlaku selama satu jam setelah permintaan diterima sistem. Jika Anda merasa tidak meminta
					proses reset password ini, e-mail ini dapat Anda abaikan. Terima kasih atas perhatiannya.</p>\n
					<br>Regards,<br><b>Administrator SiZakat LAZISBA</b>\n
					<p><small>E-mail ini dibuat oleh mesin, mohon jangan balas e-mail ini.</small></p>
			";
	if (!$noHead) $body_ .= "</body>
			</html>";
	return $body_;
}

function siz_send_email($emailSubject, $msgBody, $destEmail, $destName, $configEmailHost, $configEmailUser, $configEmailPass, $configEmailFrom, $logFlag) {
	$sendEmailResult = false;
	// Kirim e-mail..!
	require_once(COMPONENT_PATH.'/libraries/phpmailer/class.phpmailer.php');

	$mail             = new PHPMailer();

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = $configEmailHost; 	// SMTP server
	$mail->SMTPDebug  = (IS_DEBUGGING?1:0);	// enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;               // enable SMTP authentication
	$mail->Host       = $configEmailHost;	// sets the SMTP server
	$mail->Port       = 465; //25;					// set the SMTP port for the GMAIL server
	$mail->Username   = $configEmailUser;	// SMTP account username
	$mail->Password   = $configEmailPass;	// SMTP account password

	$mail->SMTPSecure = "ssl";

	$mail->SetFrom($configEmailFrom, 'e-SIZ LAZISBA');

	$mail->isHTML(true);
	$mail->Subject    = $emailSubject;
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($msgBody);

	$address = $destEmail;
	$mail->AddAddress($address, $destName);

	$sendEmailResult = $mail->Send();
	$reportToLog = "\r\n[".date('j F Y, H:i:s')."]\t: mailto [".$address."]\t: ";
	if (!$sendEmailResult) {
		$reportToLog .= "Mailer Error: " . $mail->ErrorInfo;
	} else {
		$reportToLog .= "Message sent!";
	}

	$dateChunk = date("Ymd-His");
	$reportToLog .= "\t[".$logFlag." | ".$destName."] | [".$dateChunk.".html]";

	file_put_contents(COMPONENT_PATH."/email.log", $reportToLog, FILE_APPEND);
	file_put_contents(COMPONENT_PATH."/emails/".$dateChunk.".html", $msgBody);

	return $sendEmailResult;
}