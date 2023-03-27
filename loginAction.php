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
    <title>Inserimento account per identificazione</title>
</head>
<body class="font">
<div>
<?php
    include "./html/header.html";
    include "./database/database.php";
    ?>
    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
</div>
<div align="center">
    <br><h2>
    <?php
        $email = $_POST['email'];
        try {
            if(obtain_password($email) == $_POST['password']) {
                echo "Accesso effettuato !<br>";
                $_SESSION['mail'] = $email; ?>
                    <button id="helpButton" class="button" onclick="window.open('./userPage.php'); window.close()"> Vai ai tuoi dati </button>
                <?php
            }
            else
                if($email == 'rspp' && $_POST['password'] == '12345') {
                    echo "Accesso effettuato !<br>";
                    $_SESSION['mail'] = $email; ?>
                        <button id="helpButton" class="button" onclick="window.open('./cuccurullo_page.php'); window.close()"> Vai ai tuoi dati </button>
                    <?php
                }
                else {
                    echo "Errore nell'accesso !<br>";
                     ?>
                        <button id="helpButton" class="button" onclick="window.open('./index.php'); window.close()"> Torna al login </button>
                    <?php
                }
        } catch(Exception $e) {
            echo "Errore nell'accesso !<br>";
            ?>
               <button id="helpButton" class="button" onclick="window.open('./index.php'); window.close()"> Torna al login </button>
           <?php
        }
    ?>
    </h2>
</div>
</body>
</html>