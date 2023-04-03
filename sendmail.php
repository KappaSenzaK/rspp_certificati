
<?php
include 'utils/emails.php';
?>

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
    echo sendEmail($to, $subject, $body);
    ?>
</div>

<button class="font button" onclick="window.location.replace('./index.php')">Home page</button>

</body>
</html>
