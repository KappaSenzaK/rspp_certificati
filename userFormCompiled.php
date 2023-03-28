<?php
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
    <title>Inserimento requisiti sulla sicurezza sul lavoro</title>
</head>
<body class="font">
    <?php
    include "./html/header.html";
    include "./database/database.php"
    ?>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>
    <br><div align="center">
        <h1>Pagina di <?php 
            $conn = connect_database();
            $statement = $conn->prepare("SELECT DISTINCT nome, cognome
                                         FROM personale_generale
                                         WHERE mail = '" . $mail . "'");
            $statement->execute();
            $results = $statement->get_result();
            $row = mysqli_fetch_row($results);
            echo $row[0] . " " . $row[1];

            // [elimina vecchi dati]
            $statement = $conn->prepare("DELETE FROM attestato_generico
                            WHERE mail = '" . $mail . "'");
            $statement->execute();

            $statement = $conn->prepare("DELETE FROM attestato_generico
                            WHERE mail = '" . $mail . "'");
            $statement->execute();

            $statement = $conn->prepare("DELETE FROM attestato_specifico
                            WHERE mail = '" . $mail . "'");
            $statement->execute();
            
            //[salvataggio file]
            if(isset($_FILES['att_g']))
                if($_FILES['att_g'] != null) {
                    //
                    $conn->prepare("INSERT INTO attestato_generico(mail)
                                    VALUES ('" . $mail . "');")->execute();
                }

            if(isset($_FILES['att_s']))
                if($_FILES['att_s'] != null) {
                    //
                    $conn->prepare("INSERT INTO attestato_specifico(mail,data_scadenza)
                                    VALUES ('" . $mail . "', " .date( 'Y-m-d', $_POST['att_s_date'] ) . ");")->execute();
                }
            
            $statement = $conn->prepare("UPDATE personale
                                         SET stato = 'Da revisionare'
                                         WHERE mail = '" . $mail . "'");
            $statement->execute();
        ?></h1><br>
        <h2>I dati sono stati inseriti !</h2><br>
        <button id="helpButton" class="button" onclick="window.open('./userPage.php'); window.close()"> Vai ai tuoi dati </button>

    </div>
</body>
</html>