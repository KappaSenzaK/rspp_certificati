<?php

include 'utils/database-consts.php';

$email = $_POST['email'];
$tipologia = $_POST['tipologia'];
$desc = $_POST['desc'];
$data_scadenza = $_POST['data_scadenza'];

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User page</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/button.css">
</head>
<body class="font">
<?php
include "./html/header.html";
include "./html/cuccurullo_page.html";
?>
<br>

<h1 align="center">L'eleminazione è andata a buon fine.</h1><br>

<div align="center" style="margin-top: 50px; padding: 20px">
<?php
    $conn = connect_database();
    $statement = $conn->prepare("DELETE FROM attestato WHERE mail = '" . $email . "'&& tipo = '" . $tipologia . "'&& descrizione = '" . $desc . "'&& data_scadenza = '" . $data_scadenza ."'");
    $statement->execute(); 
?>
<a href="cuccurullo_page.php">Ritorna alla pagina principale</a>
</div>
</body>
</html>