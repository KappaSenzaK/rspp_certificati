<?php
    session_start();
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

            cancellaCertificatiGenericiVecchi($mail);

            if($_SERVER["REQUEST_METHOD"] != "POST"){
                die("<h1>E' richiesta il metodo POST. </h1>");
            }

            // salvataggio file
            if(isset($_FILES['att_g']) && $_FILES['att_g']['error'] == 0) {
                if ($_FILES['att_g'] != null) {
                    if(saveFileToWebServer($mail, $_FILES['att_g'], "generico") != 'error')
                        insertNewAttestatoGenerico($mail);
                }
            }

            if(isset($_FILES['att_s']) && $_FILES['att_s']['error'] == 0) {
                if ($_FILES['att_s'] != null && isset($_POST['att_s_date'])) {
                    $date = date('Y-m-d', strtotime($_POST['att_s_date']));
                    if(saveFileToWebServer($mail, $_FILES["att_s"], "specifico") != 'error')
                        insertNewAttestatoSpecifico($mail, $date);
                }
            }

            aggiornareCertificati_a_revisionare($mail);
        ?></h1><br>
        <h2>I dati sono stati inseriti !</h2><br>
        <button id="helpButton" class="button" onclick="window.open('./userPage.php'); window.close()"> Vai ai tuoi dati </button>

    </div>
</body>
</html>