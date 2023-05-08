<?php
    session_start();

    include 'checkPage.php';
    
    $mail = $_SESSION['mail'];

include "./database/database.php";
include "./utils/files_utility.php";
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
    ?>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>
    <br><div align="center">
        <h1>Pagina di <?php
            $nameAndSurname = retrieveNameAndSurname($mail);
            echo $nameAndSurname['name'] . ' ' . $nameAndSurname['surname'];

            if($_SERVER["REQUEST_METHOD"] != "POST"){
                die("<h1>E' richiesta il metodo POST. </h1>");
            }


            // elimina vecchi file
            cancellaCertificatiVecchi($mail, $_SESSION['cert']);

            // salvataggio file
                if(isset($_FILES['att']) && $_FILES['att']['error'] == 0) {
                    if ($_FILES['att'] != null) {
                        if($_SESSION['cert_t'] == 'scad') 
                            $date = date('Y-m-d', strtotime($_POST['att_s_date']));
                        else
                            $date = null;

                        $file_allegato = $_FILES['att']['name'];

                        if(saveFileToWebServer($mail, $_FILES['att'], $_SESSION['cert']) != 'error')
                            insertNewAttestato($mail, $_POST['descrizione'], $_SESSION['cert'], $date, $file_allegato);
                    }
                }

            
        ?></h1><br>
        <h2>I dati sono stati inseriti !</h2><br><br>
        <button id="helpButton" class="button" onclick="window.open('userPage.php'); window.close();"> Vai ai tuoi dati </button>
    </div>
</body>
</html>