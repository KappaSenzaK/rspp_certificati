<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

class EmailStatus
{
    const OK = "Al tuo indirizzo e-mail istituzionale è stata inviata una mail di conferma.";
    const NOT_OK_SCHEMA = "Errore durante la creazione dell'account.";
    const OK_RSPP = "L'email è stato inviato con successo.";
}

function sendEmail($to, $subject, $body, $email_type)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host     = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cuccurulloprogetto@gmail.com';
    $mail->Password = 'vozjcwivaxzgtdas'; // e' la password app (in questo caso collegata all'email cuccurulloprogetto@gmail.com

    $mail->setFrom('cuccurulloprogetto@gmail.com');

    $mail->addAddress($to); // email destinataria
    $mail->isHTML(true);

    $mail->Subject = $subject;  // Subject della email
    $mail->Body    = $body;   // Il contenuto dell'email (in html)

    try {
        $mail->send();

        return "<div align='center'><h2>$email_type</h2></div>";
    } catch (Exception $e) {
        $s = EmailStatus::NOT_OK_SCHEMA.". Motivo: ".$e->errorMessage();

        return "<div align='center'> <h2>$s</h2> </div>";
    }
}