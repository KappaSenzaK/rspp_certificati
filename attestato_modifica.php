<?php

include "database/database.php";

$email = $_POST['email'];
$tipologia = $_POST['tipologia'];
$desc = $_POST['desc'];
$data_scadenza = $_POST['data_scadenza'];

if (!isset($email) || !isset($tipologia) || !isset($desc) || !isset($data_scadenza)) {
    die("<h1>Dati non validi </h1>");
}

$result = modifyAttestato($email, $tipologia, $desc, $data_scadenza);

if($result) {
    echo "Attestato modificato con successo";
} else {
    echo "Impossibile modificare l'attestato";
}
?>

<a href="cuccurullo_page.php">Ritorna alla pagina dell'RSPP!</a>
