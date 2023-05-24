<?php

include 'utils/database-consts.php';

$email = $_POST['user'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$c_f = $_POST['c_f'];
$note = $_POST['note'];
$stato = $_POST['stato'];
$in_servizio = $_POST['in_servizio'];

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

<h1 align="center">L'utente <?php echo $nome . " " . $cognome . "è stato validato."?></h1><br>

<div align="center" style="margin-top: 50px; padding: 20px">
<?php
    aggiornaStato($email, 'Validato');
    $conn = connect_database();
    $statement = $conn->prepare("SELECT stato FROM personale WHERE mail = '" . $email . "'");
    $statement->execute();
    $results = $statement->get_result();
    $row = mysqli_fetch_row($results);  

    echo '<form method="post" action="rspp-user-page.php" align="center">
                <input type="hidden" name="user" id="user" value="' . $email . '">
                <input type="hidden" name="nome" value="' . $nome . '">
                <input type="hidden" name="cognome" value="' . $cognome . '">
                <input type="hidden" name="c_f" value="' . $c_f . '">
                <input type="hidden" name="note" value="' . $note . '">
                <input type="hidden" name="stato" value="' . $row[0] . '">
                <input type="hidden" name="in_servizio" value="' . $in_servizio . '">
                <input type="submit" value=" Conferma " class="btn btn-success">
            </form>';
?>
</div>
</body>
</html>