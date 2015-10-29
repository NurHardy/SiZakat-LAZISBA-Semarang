<?php

$actionWord = $_GET['action'];

if ($actionWord=="do") { 
	include COMPONENT_PATH."/file/user/resetpass/form_resetpass_set.php";
} else { // Default: Form email reset password
	include COMPONENT_PATH."/file/user/resetpass/form_resetpass_email.php";
}
