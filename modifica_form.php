<?php

include './database/database.php';
include "./html/header.html";
include "./html/cuccurullo_page.html";

$conn = connect_database();

$nome           = mysqli_real_escape_string($conn, htmlspecialchars($_POST['nome']));
$cognome        = mysqli_real_escape_string($conn, htmlspecialchars($_POST['cognome']));
$email          = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
$codice_fiscale = mysqli_real_escape_string($conn, htmlspecialchars($_POST['codice_fiscale']));
// $stato = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dataNascita']));
// $stato = mysqli_real_escape_string($conn, htmlspecialchars($_POST['luogoNascita']));
$stato = mysqli_real_escape_string($conn, htmlspecialchars($_POST['stato']));
$desc = mysqli_real_escape_string($conn, htmlspecialchars($_POST['desc']));
$in_servizio = mysqli_real_escape_string($conn, htmlspecialchars($_POST['in_servizio']));
$tipo = mysqli_real_escape_string($conn, htmlspecialchars($_POST['tipo']));
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifica utente</title>
  <link rel="stylesheet" href="css/font.css">
</head>
<body class="font">
<div align="center">
    <?php

    $real_email = explode('@', $email);

    // verificare i valori degli input

    modifyAccount($nome, $cognome, $real_email[0], $codice_fiscale, $stato, $desc, $in_servizio, $tipo);

    echo "<br><h2>L'account di $nome $cognome e' stato modificato con successo! </h2>";
    echo "<a href='cuccurullo_page.php'>Ritorna alla pagina principale </a>";
    ?>
</div>
</body>
</html>
