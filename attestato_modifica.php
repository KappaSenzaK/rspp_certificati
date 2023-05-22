<?php

include "database/database.php";

$email = $_POST['email'];

$old_tipologia = $_POST['old_tipologia'];
$old_desc = $_POST['old_desc'];

$tipologia = $_POST['tipologia'];
$desc = $_POST['desc'];
$data_scadenza = $_POST['data_scadenza'];

if (!isset($email) || !isset($tipologia) || !isset($desc) || !isset($data_scadenza)) {
    die("<h1>Dati non validi </h1>");
}

$result = modifyAttestato($email, $tipologia, $desc, $data_scadenza, $old_desc, $old_tipologia);

if($result) {
    echo "Attestato modificato con successo";
} else {
    echo "Impossibile modificare l'attestato";
}
?>

<a href="cuccurullo_page.php">Ritorna alla pagina dell'RSPP!</a>
