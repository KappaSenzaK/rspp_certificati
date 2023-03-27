<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'cuccurulloprogetto@gmail.com';
$mail->Password = 'vozjcwivaxzgtdas'; // e' la password app (in questo caso collegata all'email cuccurulloprogetto@gmail.com

$mail->setFrom('cuccurulloprogetto@gmail.com');

$mail->addAddress('stefano.hu1.stud@tulliobuzzi.edu.it'); // email di test
$mail->isHTML(true);

$mail->Subject = 'Subject';  // Subject della email
$mail->Body = '<h1>Sono un napoletano figo forte e imbattibile</h1>';   // Il contenuto dell'email

$mail->send();

echo "Funziona il send";