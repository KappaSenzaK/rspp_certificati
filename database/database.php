<?php
function connect_database($server_name = "localhost",
                          $username = "root",
                          $password = "",
                          $database = "rspp_certificati")
{
    $conn = new mysqli($server_name, $username, $password);
    if($conn->connect_error){
        die("Connessione fallita: " . $conn->connect_error);
    }

    $retval = mysqli_select_db( $conn, $database );
    if(! $retval ) {
        die('Impossibile selezionare il database: ' . mysqli_error($conn));
    }

    return $conn;
}

function obtain_password($mail) {
    $conn = connect_database();
    $statement = $conn->prepare('SELECT pw FROM personale WHERE mail = "'. $mail . '"');
    $statement->execute();

    $results = $statement->get_result();
    $row = $results->fetch_assoc();

    if($row == null)
        return null;
    else
        return $row['pw'];
}

function existAccountByEmail($mail): bool
{
    $conn = connect_database();
    $statement = $conn->prepare('SELECT COUNT(mail) as emails FROM personale WHERE mail = "'. $mail . '"');
    $statement->execute();

    $results = $statement->get_result();
    $row = $results->fetch_assoc();

    if($row == null || $row['emails'] == 0) {
        return false;
    } else {
        return true;
    }
}

//Senza "stato" (default = "Da compilare")
function createNewDefaultAccount($mail, $tipo, $nome, $cognome, $cod_fiscale, $data_nascita, $note, $pw) {
    $conn = connect_database();
    $statement = $conn->prepare("INSERT INTO personale(mail, tipo, nome, cognome, cod_fiscale, data_nascita, note, stato, pw) VALUES ('$mail', '$tipo', '$nome', '$cognome', '$cod_fiscale', '$data_nascita', '$note', 'Da compilare', '$pw')");
    $statement->execute();
}

function createNewAccount($mail, $tipo, $nome, $cognome, $cod_fiscale, $data_nascita, $note, $stato, $pw) {
    $conn = connect_database();
    $statement = $conn->prepare("INSERT INTO personale(mail, tipo, nome, cognome, cod_fiscale, data_nascita, note, stato, pw) VALUES ($mail, $tipo, $nome, $cognome, $cod_fiscale, $data_nascita, $note, $stato, $pw)");
    $statement->execute();
}

function retrieveNameAndSurname($mail): array
{
    $conn = connect_database();
    $statement = $conn->prepare("SELECT DISTINCT nome, cognome
                                         FROM personale_generale
                                         WHERE mail = '" . $mail . "'");
    $statement->execute();
    $results = $statement->get_result();
    $row = mysqli_fetch_row($results);

    return array("name" => $row[0], "surname" => $row[1]);
}

function insertNewAttestatoGenerico($mail){
    $conn = connect_database();
    $statement = $conn->prepare("INSERT INTO attestato_generico(mail) VALUES ('$mail')");
    $statement->execute();
}

function insertNewAttestatoSpecifico($mail, $data_scadenza) {
    $conn = connect_database();
    $statement = $conn->prepare("INSERT INTO attestato_specifico(mail, data_scadenza) VALUES ('$mail', '$data_scadenza')");
    $statement->execute();
}

function cancellaCertificatiGenericiVecchi($mail) {
    $conn = connect_database();
    $statement = $conn->prepare("DELETE FROM attestato_generico
                            WHERE mail = '" . $mail . "'");
    $statement->execute();
}

function aggiornareCertificati_a_revisionare($mail){
    $conn = connect_database();
    $statement = $conn->prepare("UPDATE personale
                                         SET stato = 'Da revisionare'
                                         WHERE mail = '" . $mail . "'");
    $statement->execute();
}