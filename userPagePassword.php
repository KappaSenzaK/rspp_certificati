<?php
    if(!isset($database_set))
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
    <title>Modifica password</title>
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
        ?></h1>
    </div><br>
    
    <div align="center">
        <?php
        if(isset($_POST['old_p']))
            if(isset($_POST['new_p'])){
                $conn = connect_database();
                $statement = $conn->prepare("SELECT pw
                                            FROM personale
                                            WHERE mail = '" . $mail . "'");
                $statement->execute();
                $results = $statement->get_result();
                $old_password = mysqli_fetch_row($results);

                if($_POST['old_p'] == $old_password[0]) {
                    $conn = connect_database();
                    $statement = $conn->prepare("UPDATE personale
                                                SET pw = '" . $_POST['new_p'] . "'
                                                WHERE mail = '" . $mail . "'");
                    $statement->execute();
                    ?>
                        <h2>La password è stata modificata!<h2><br>
                        <button class="button" onclick="window.open('userPage.php'); window.close()"> Torna ai tuoi dati </button>
                    <?php
                } else {
                    ?>
                        <h2>La vecchia password è errata!<br>La password non è stata modificata!<h2><br>
                        <button class="button" onclick="window.open('userPage.php'); window.close()"> Torna ai tuoi dati </button>&emsp;
                        <button class="button" onclick="window.open('userPagePassword.php'); window.close()"> Prova di nuovo </button>
                    <?php
                }
                die();
            }
        
        if(isset($_POST['send_p'])){
            include 'utils/emails.php';

            $conn = connect_database();
            $statement = $conn->prepare("SELECT pw
                                        FROM personale
                                        WHERE mail = '" . $mail . "'");
            $statement->execute();
            $results = $statement->get_result();
            $old_password = mysqli_fetch_row($results);
            
            $sendmail_message = sendEmail(
                $mail."@tulliobuzzi.edu.it",
                "Credenziali RSPP Certificati",
                "<h1>Password per l'accesso all'account: " . $old_password[0] . "</h1>"
            );
            
            if($sendmail_message != EmailStatus::OK) {
                echo "<h2>" . $sendmail_message . "</h2>";
                die();
            }
            ?>
                <h1>Al tuo indirizzo e-mail è stata mandata la tua vecchia password<h1><br>
                <button class="button" onclick="window.open('userPage.php'); window.close()"> Torna ai tuoi dati </button>
            <?php
            die();
        }

        include 'html/user_cambia_pass.html';

        ?>
    </div>
</body>
</html>