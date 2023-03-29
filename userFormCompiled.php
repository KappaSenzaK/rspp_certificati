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
    include "./database/database.php";
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

            if($_SERVER["REQUEST_METHOD"] != "POST"){
                die("<h1>E' richiesta il metodo POST. </h1>");
            }

            function saveFileToWebServer($mail, $file, $type) {

                if($file['error'] != 0) {
                    return;
                }
                $mail_directory_path = str_replace(".", "_", $mail); //impossibile creare directory con '.' nel nome

                $filepath_directory = __DIR__ . "/certificati/$mail_directory_path/$type";
                $filename = basename($file['name']);
                $target_file = $filepath_directory.'/'.$filename;

                if(file_exists($target_file)) {
                    die("<h1>Il esiste di gia</h1>");
                }

                $ext = strtoupper(pathinfo($target_file, PATHINFO_EXTENSION));
                if(!is_dir($filepath_directory)) {
                    mkdir($filepath_directory, 0755, true);
                }

                if(!move_uploaded_file($file["tmp_name"], $target_file)){
                    echo "<h1>Errore generico nel caricamento del file";
                }
            }

            //[salvataggio file]
            if(isset($_FILES['att_g']) && $_FILES['att_g']['error'] == 0) {
                if ($_FILES['att_g'] != null) {
                    //
                    $conn->prepare("INSERT INTO attestato_generico(mail)
                                    VALUES ('" . $mail . "');")->execute();
                    saveFileToWebServer($mail, $_FILES['att_g'], "generico");
                }
            }

            if(isset($_FILES['att_s']) && $_FILES['att_s']['error'] == 0) {
                if ($_FILES['att_s'] != null) {
                    $date = date('Y-m-d', strtotime($_POST['att_s_date']));
                    try{
                        $statement = $conn->prepare("INSERT INTO attestato_specifico(mail,data_scadenza) VALUES ('$mail', '$date')");
                        $statement->execute();
                    } catch (Exception $e) {
                    }
                    saveFileToWebServer($mail, $_FILES["att_s"], "specifico");
                }
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