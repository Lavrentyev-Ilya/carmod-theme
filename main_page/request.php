<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//session_start();
mb_internal_encoding("UTF-8");
define('CM_PROLOG_INCLUDED',true);
//global $arMyCon;
$fData = json_decode(file_get_contents('php://input'), true);

$errors = [];
if (empty($fData['femail'])) $errors[] = "Valid email is required";
if (empty($fData['fdescription'])) $errors[] = "Description is required";

if(!empty($errors)){
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => implode(", ", $errors)]);
	exit;
}

try{
	$FullPath = __DIR__;
	$ReltPath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$FullPath);
	$aDir = array_filter(explode('/',$ReltPath));
	if(count($aDir)<2){$aDir = array_filter(explode('\\',$ReltPath));} //Windows server back slash fix
	list($ModDir) = array_slice($aDir,-4,1);
	require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/config.php");

	$Email_Message = '<div style="font-size:24px;">Cannot find car</b></div><br>
					<table style="min-height:300px; font-size:18px;">
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Article/OOM/VIN</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fcardId'].'</td></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Brand</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fbrand'].'</td></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Model</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fmodel'].'</td></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Engine</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fengine'].'</td></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">E-Mail</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['femail'].'</td></tr></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Phone</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fphone'].'</td></tr>
						<tr><td style="text-align:right; padding:0px 10px; font-weight:bold;">Description</td><td style="text-align:left; padding:0px 10px; border-radius:6px; background-color:#f0f0f0;">'.$fData['fdescription'].'</td></tr>
					</table>';

	$Email_Title = $email_title;
	require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/core/object.php");

	$CPMod = new CPMod();
	
	require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/media/phpmailer/PHPMailer.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/media/phpmailer/SMTP.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/".$ModDir."/media/phpmailer/Exception.php");
	
	$AdminEmail = $CPMod->arSettings['ADMIN_EMAIL'];
	$name = 'CarMod Mail';
	$password = $CPMod->arSettings['EMAIL_PASSWORD'];
	$smtp_server = $CPMod->arSettings['EMAIL_STMP'];
	$smtp_port = (int) $CPMod->arSettings['EMAIL_STMP_PORT'];
	$encryption = $CPMod->arSettings['EMAIL_ENC'];
	
	$mail = new PHPMailer(true);
	
	try{
		$mail->isSMTP();
		$mail->Host = $smtp_server;
		$mail->SMTPAuth = true;
		$mail->Username = $AdminEmail;
		$mail->Password = $password;

		// Устанавливаем шифрование в зависимости от выбора пользователя
		if ($encryption == 'tls') {
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		} elseif ($encryption == 'ssl') {
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		} else {
			$mail->SMTPSecure = '';
		}

		$mail->Port = $smtp_port;

		// От кого
		$mail->setFrom($AdminEmail, $name);
		$mail->addAddress($AdminEmail); // Кому отправляем

		// Контент письма
		$mail->isHTML(true);
		$mail->Subject = $fData['femail'].' : '.$fData['fphone'];
		$mail->Body    = $Email_Message;
		$mail->AltBody = $fData['femail'].' : '.$fData['fphone'];

		$mail->send();
		$mailTrue = "Y";
		echo json_encode(['success' => true, 'message' => 'Form submitted successfully']);
	}catch (Exception $e) {
		//$errorSend = $Error_sending_mail.' ('.$mail->ErrorInfo.')';
		echo json_encode(['success' => false, 'message' => 'Mail error: '.$mail->ErrorInfo]);
	}
	
}catch(Exception $e){
	http_response_code(500);
	echo json_encode(['success' => false, 'message' => 'Server error: '.$e->getMessage()]);
}