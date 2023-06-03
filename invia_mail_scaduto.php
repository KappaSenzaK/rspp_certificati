<!DOCTYPE html>
<?php
session_start();
include 'checkPage_rspp.php';
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/font.css">
    <title>Invia mail</title>

    <style>
        .table {
            margin: auto;
            width: 50% !important;
        }

        #email-section {
            padding-top: 70px;
            margin: auto;
            width: 50%;
        }
    </style>
</head>
<body class="font">

<div>
    <?php
    include "./html/header.html";
    ?>
</div>
<div align="center">
    <?php
    include "./html/cuccurullo_page.html"
    ?>
    <br>
    <?php
        try {
            include "./utils/emails.php";
            if(!isset($_POST['mail']) || 
            !isset($_POST['attestato']) || 
            !isset($_POST['data_scadenza']))
                throw new exception();
            
            sendEmail(
                $_POST['mail'].'@tulliobuzzi.edu.it',
                'Attestato scaduto',
                'Attenzione! Il tuo certificato "'.$_POST['attestato'].'" è scaduto in data '.$_POST['data_scadenza'].'!'
            );
            echo "<h2>L'utente è stato avvisato via mail del certificato scaduto!</h2><br>";

        } catch (exception $e) {
            echo '<h1> Attenzione! Non è possibile inviare la mail!</h1>';
        }

        echo "<a href='cuccurullo_page.php'>Torna alla pagina principale</a>";
        
    ?>
    <script src="js/actions.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

</body>
</html>
