<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>Main page per registrazione requisiti sulla sicurezza sul lavoro</title>
</head>
<body class="font">

<div>
    <?php
    include "./html/header.html"
    ?>
    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
</div>
<div>
    <?php
    if (!isset($_SESSION['mail']) || isset($_POST['logout'])) {
        include "./html/loginPage.html";
    } else {
        ?>
        <div align="center">
            Hai già effettuato l'accesso !<br><br>
            <button id="helpButton" class="button" onclick="window.location.replace('./userPage.php');"> Vai ai tuoi
                dati
            </button>
            <br><br>
            <form method="post" action="index.php">
                <input type="hidden" id="logout" name="logout" value="a">
                <input type="submit" class="button" value="Torna all'accesso">
            </form>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>