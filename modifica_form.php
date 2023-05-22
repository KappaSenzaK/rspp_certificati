<?php

include './database/database.php';

$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$email = $_POST['email'];
$codice_fiscale = $_POST['codice_fiscale'];
$stato = $_POST['stato'];


$real_email = explode('@', $email);

// verificare i valori degli input

modifyAccount($nome, $cognome, $real_email[0], $codice_fiscale, $stato);

echo "L'account di $nome e' stato modificato con successo! <br>";
echo "<a href='cuccurullo_page.php'>Ritorna alla pagina dell'RSPP </a>";

