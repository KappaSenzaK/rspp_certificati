<?php

include "database/database.php";

$email = $_POST['email'];
$conn = connect_database();

$old_tipologia = $_POST['old_tipologia'];
$old_desc = $_POST['old_desc'];

$tipologia = $_POST['tipologia'];
$desc = $_POST['desc'];
$data_scadenza = $_POST['data_scadenza'];

$statement = $conn->prepare("SELECT file_allegato AS f
                            FROM attestato
                            WHERE mail = '" . $email . "' AND tipo = '" . $old_tipologia . "'");
$statement->execute();
$file_name = $statement->get_result();
$file_name = $file_name->fetch_assoc();

if (!isset($email) || !isset($tipologia) || !isset($desc) || !isset($data_scadenza)) {
    die("<h1>Dati non validi!</h1>");
}


$result = modifyAttestato($email, $tipologia, $desc, $data_scadenza, $old_desc, $old_tipologia);

if($result) {
    echo "Attestato modificato con successo";

    try{
        mkdir('./certificati/' . str_replace('.', '_', $email) . '/' . $tipologia);
    } catch(exception $e) {}
    rename('./certificati/' . str_replace('.', '_', $email) . '/' . $old_tipologia . '/' . $file_name['f'],
        './certificati/' . str_replace('.', '_', $email) . '/' . $tipologia . '/' . $file_name['f']);
} else {
    echo "Impossibile modificare l'attestato";
}
?>

<a href="cuccurullo_page.php">Ritorna alla pagina dell'RSPP</a>
