<?php
	include_once("fcts.php");
	$conn=connect();

	define('ID_USER', 'noreply@icoop.live');
	define('ID_PASS', 'Qwerty1234');

	require './vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
	
	$message = 'We are testing the email function.';
echo 3;
	$sender="noreply@icoop.live";
	$recipient="noreply@icoop.live";
				echo 2;
					$mail = new PHPMailer;
					// $mail->isSMTP();
					// $mail->SMTPDebug = 4;
					// $mail->SMTPSecure = 'ssl';
					// $mail->Debugoutput = 'html';
					// $mail->Host = "mail.icoop.live ";
					// $mail->Port = 465;
					// $mail->SMTPAuth = true;
					// $mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icoop.live>";
					// $mail->Username = ID_USER;
					// $mail->Password = ID_PASS;
					// $mail->setFrom('noreply@icoop.live');
					// $mail->AddBCC("croth53@gmail.com"); 
					// $mail->AddCC("charlessabenin@hotmail.com");
					// $mail->addAddress("charlessabenin@gmail.com");
					// $mail->Subject = 'Test email';
					// $mail->msgHTML($message);
					// $mail->AltBody = 'This is a plain-text message body';
			echo 1;
					//send the message, check for errors
					// if (!$mail->send()) {
						// echo 'Mailer Error: ' . $mail->ErrorInfo;
					// } else {
						// echo 'Email sent.';
					// } 
					
					echo 0;