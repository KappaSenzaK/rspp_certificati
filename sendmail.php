<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

#tutorial
#https://www.google.com/search?q=phpmailer+how+to+use+app+password+for+sending+emails&rlz=1C1CHBF_itIT1025IT1025&oq=phpmailer+how+to+use+app+password+for+sending+emails&aqs=chrome..69i57j33i160l5.5856j0j7&sourceid=chrome&ie=UTF-8#fpstate=ive&vld=cid:a2e78afb,vid:QvkxdMlbW90

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'cuccurulloprogetto@gmail.com';                     //SMTP username
    $mail->Password   = 'buzzi123';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Debug');
    $mail->addAddress('rockyjoe@example.net');     //Add a recipient

    $message = "This is a test message!";

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = $message;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}