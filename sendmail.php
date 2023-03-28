<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
    <link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body>
<?php
include 'html/header.html'
?>

<div class="font">
    <?php

    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    if(!isset($to) || !isset($subject) || !isset($body)) {
        die("<h1>Email destinatario, subject, oppure body non sono configurati</h1>");
    }

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

    $mail->addAddress($to); // email destinataria
    $mail->isHTML(true);

    $mail->Subject = $subject;  // Subject della email
    $mail->Body = $body;   // Il contenuto dell'email (in html)

    try {
        $mail->send();
        echo "L'email e' stato mandato correttamente";
    } catch (Exception $e) {
        echo "Errore durante l'esecuzione dell'email. Motivo: " . $e->errorMessage();
    }

    ?>
</div>

<button class="font button" onclick="window.location.replace('./index.php')">Home page</button>

</body>
</html>
