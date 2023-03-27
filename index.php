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
    include "./header.html"
    ?>
    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
</div>
<div>
    <?php
    if(!isset($_SESSION['mail']))
    {
        include "./loginPage.html";
    }
    else
    if($_SESSION['mail'] == null)
    {
        include "./loginPage.html";
    }
    else
    {
        ?>
        <div align="center">
            Hai già effettuato l'accesso !<br><br>
            <button id="helpButton" class="button" onclick="window.open('./userPage.php'); window.close()"> Vai ai tuoi dati </button>
        </div>
        <?php
    }
    ?>
</div>





</body>
</html>