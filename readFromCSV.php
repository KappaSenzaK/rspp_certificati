<?php
    session_start();
    include 'checkPage.php';
    include 'utils/files_utility.php';
    include 'database/database.php';
    include 'utils/emails.php';

    if($_SESSION['mail'] != 'rspp') {
        ?>
            <h1>Errore nell'accesso!</h1><br><br><br>
            <button id="helpButton" class="button" onclick="window.open('index.php'); window.close()"> Torna alla pagina di accesso </button>
        <?php 
        die();
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica utenti da file</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="font">
    <?php
        include 'html/header.html';
        include 'html/cuccurullo_page.html';
    ?>

    <div align="center">
    <?php
        if(!isset($_FILES['csv'])) {
            ?>
                <h2>Inserisci il file .csv da cui leggere gli utenti</h2><br>
                <form id="ins_form" action="readFromCSV.php" method="post" enctype="multipart/form-data"><br>
                    <input required class="button" type="file" accept=".csv" name="csv" id="csv"/>
                    <input class="button" type="submit" value="Invia il modulo" id="submit"/><br>
                </form>
            <?php
        } else {
            if ($_FILES['csv'] != null) {

                $results = "";
                try {
                    $file_allegato = $_FILES['csv']['name'];
                    $date = date('Y-m-d H-i-s');
                    saveFileToWebServer("file csv caricati", $_FILES['csv'], $date);
                    
                    $file = fopen("certificati/file csv caricati/" . $date . "/" . $file_allegato, "r");

                    $conn = connect_database();

                    $results = '<h2>File ' . $file_allegato . 'letto correttamente !</h2><br><h3>Utenti inseriti:</h3>';
                    while($row = fgetcsv($file)) {
                        $nome = "";
                        $cognome = "";
                        $tipo = "";
                        $cod_fiscale = "";
                        $mail = "";
                        $digestPassword = substr(
                            hash('md5',$mail.$tipo.$nome.$cognome.$cod_fiscale), 0, 5
                        );

                        $results += "<br>" . $nome . " " . $cognome;

                        $sendmail_message = sendEmail(
                            $mail."@tulliobuzzi.edu.it",
                            "Credenziali RSPP Certificati",
                            "<h1>Password per l'accesso all'account: " . $digestPassword . "</h1>"
                        );
                        if($sendmail_message != EmailStatus::OK) {
                            echo "<h2>" . $sendmail_message . "</h2>";
                            die();
                        }


                        $statement = $conn->prepare(
                            "INSERT INTO personale(mail,tipo,nome,cognome,cod_fiscale,pw)
                            VALUES('$mail','$tipo','$nome','$cognome','$cod_fiscale','$digestPassword');");
                        $statement->execute();
                    }
                } catch(exception $e) {
                    echo $results = '<h2>Errore nella lettura del file!</h2>';
                }
            } else
                echo $results = '<h2>Errore nella lettura del file!</h2>';

            echo $results;
        }
    ?>
    <a href=cuccurullo_page.php>Ritorna alla pagina principale</a>
    </div>
</body>
</html>