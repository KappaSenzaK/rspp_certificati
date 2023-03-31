<?php
    include 'database\database.php';
    session_start();
    $mail = $_SESSION['mail'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>Richiesta di modifica</title>
</head>
<body class="font">
    <style>
        th, td {
            border: 2px solid white;
        }
    </style>

    <div>
    <?php
        include "./html/header.html";
        include "./html/user_page.html";
        ?>
    </div>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>

    <br><div align="center">
        <h1>Pagina di <?php 
            $nameAndSurname = retrieveNameAndSurname($mail);
            echo $nameAndSurname['name'] . ' ' . $nameAndSurname['surname'];

            $statement = $conn->prepare("UPDATE personale
                                         SET stato = 'Richiesta modifica'
                                         WHERE mail = '" . $mail . "'");
            $statement->execute();
            ?>
        </h1><br><br>
        <h2>La modifica è stata richiesta !</h2>
        <p>Attendi che l'amministratore accetti la richiesta di modifica ...</p>
        <br><br><br>
        <button id="helpButton" class="button" onclick="window.open('userPage.php'); window.close()"> Torna ai tuoi dati </button>
        <br><br>
    </div><br>
</body>
</html>
