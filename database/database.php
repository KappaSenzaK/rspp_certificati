<?php

$database_set = 0;

class StatoCertificati
{
    const DA_COMPILARE = "Da compilare";
    const DA_REVISIONARE = "Da revisionare";
    const REVISIONATO = "Revisionato";
    const RICHIESTA_MODIFICA = "Richiesta modifica";
}

function connect_database(
        $server_name = "localhost",
        $username = "root",
        $password = "",
        $database = "rspp_certificati"
) {
    $conn = new mysqli($server_name, $username, $password);
    if ($conn->connect_error) {
        die("Connessione fallita: ".$conn->connect_error);
    }

    $retval = mysqli_select_db($conn, $database);
    if ( ! $retval) {
        die('Impossibile selezionare il database: '.mysqli_error($conn));
    }

    return $conn;
}

function obtain_password($mail)
{
    $conn      = connect_database();
    $mail      = mysqli_real_escape_string($conn, $mail);
    $statement = $conn->prepare('SELECT pw FROM personale WHERE mail = "'.$mail.'"');
    $statement->execute();

    $results = $statement->get_result();
    $row     = $results->fetch_assoc();

    if ($row == null) {
        return null;
    } else {
        return $row['pw'];
    }
}

function findUserByEmail($mail)
{
    $conn      = connect_database();
    $mail      = mysqli_real_escape_string($conn, $mail);
    $statement = $conn->prepare("
            SELECT tipo,
                   nome, 
                   cognome, 
                   cod_fiscale, 
                   data_nascita,
                   luogo,
                   note, 
                   stato,
                   in_servizio 
            FROM personale 
            WHERE mail = '$mail'"
    );
    $statement->execute();

    $results = $statement->get_result();
    $row     = $results->fetch_assoc();

    $user                   = [];
    $user['email']          = $mail;
    $user['tipo']           = $row['tipo'];
    $user['nome']           = $row['nome'];
    $user['cognome']        = $row['cognome'];
    $user['codice_fiscale'] = $row['cod_fiscale'];
    $user['data_nascita']   = $row['data_nascita'];
    $user['luogo']          = $row['luogo'];
    $user['note']           = $row['note'];
    $user['stato']          = $row['stato'];
    $user['in_servizio']    = $row['in_servizio'];

    return $user;
}

function existAccountByEmail($mail): bool
{
    $conn      = connect_database();
    $mail      = mysqli_real_escape_string($conn, $mail);
    $statement = $conn->prepare('SELECT COUNT(mail) as emails FROM personale WHERE mail = "'.$mail.'"');
    $statement->execute();

    $results = $statement->get_result();
    $row     = $results->fetch_assoc();

    if ($row == null || $row['emails'] == 0) {
        return false;
    } else {
        return true;
    }
}

function createNewAccount($mail, $tipo, $nome, $cognome, $cod_fiscale, $data, $luogo, $pw)
{
    $pw = hash("sha256", $pw);

    $conn      = connect_database();
    $pw       = mysqli_real_escape_string($conn, $pw);
    $statement = $conn->prepare(
            "INSERT INTO personale(mail, tipo, nome, cognome, cod_fiscale, data_nascita, luogo, stato, pw) 
                VALUES ('$mail', '$tipo', '$nome', '$cognome', '$cod_fiscale', '$data', '$luogo', 'Da compilare', '$pw')");
    $statement->execute();
}

function modifyAccount($nome, $cognome, $email, $codice_fiscale, $stato, $desc, $in_servizio, $tipo)
{
    $conn            = connect_database();
    $nome            = mysqli_real_escape_string($conn, $nome);
    $cognome         = mysqli_real_escape_string($conn, $cognome);
    $email           = mysqli_real_escape_string($conn, $email);
    $codice_fiscale  = mysqli_real_escape_string($conn, $codice_fiscale);
    $stato           = mysqli_real_escape_string($conn, $stato);
    $in_servizio     = mysqli_real_escape_string($conn, $in_servizio);
    $tipo            = mysqli_real_escape_string($conn, $tipo);
    $statement = $conn->prepare("UPDATE personale 
    SET nome = '$nome', cognome = '$cognome', cod_fiscale = '$codice_fiscale', stato = '$stato', note = '$desc', in_servizio = '$in_servizio', tipo = '$tipo'
    WHERE mail = '$email'");

    $statement->execute();
}

function modifyAttestato($email, $tipologia, $desc, $data_scadenza, $old_desc, $old_tipologia)
{
    $conn     = connect_database();
    $desc     = mysqli_real_escape_string($conn, $desc);
    $old_desc = mysqli_real_escape_string($conn, $old_desc);
    $email    = mysqli_real_escape_string($conn, $email);
    $conn->query("UPDATE attestato 
SET tipo = '$tipologia', descrizione = '$desc', data_scadenza = '$data_scadenza'
WHERE mail = '$email' AND tipo = '$old_tipologia' AND descrizione = '$old_desc' ");

    if (mysqli_affected_rows($conn)) {
        return true;
    }

    return false;
}

function retrieveNameAndSurname($mail): array
{
    $conn      = connect_database();
    $mail      = mysqli_real_escape_string($conn, $mail);
    $statement = $conn->prepare("SELECT DISTINCT nome, cognome
                                         FROM personale
                                         WHERE mail = '".$mail."'");
    $statement->execute();
    $results = $statement->get_result();
    $row     = mysqli_fetch_row($results);

    return ["name" => $row[0], "surname" => $row[1]];
}

function insertNewAttestato(
        $mail,
        $descrizione = '',
        $tipo = 'Altro',
        $data_scadenza = null,
        $file_allegato = ''
) {
    $conn = connect_database();
    $mail          = mysqli_real_escape_string($conn, $mail);
    $descrizione   = mysqli_real_escape_string($conn, $descrizione);
    $file_allegato = mysqli_real_escape_string($conn, $file_allegato);
    if ($data_scadenza == null) {
        $statement = $conn->prepare("INSERT INTO attestato(mail, tipo, descrizione, file_allegato)    
                                    VALUES ('".$mail."','".$tipo."','".$descrizione."', '".$file_allegato."')");
    } else {
        $statement = $conn->prepare("INSERT INTO attestato(mail, tipo, data_scadenza, descrizione, file_allegato)    
                                    VALUES ('".$mail."','".$tipo."','".$data_scadenza."','".$descrizione."', '".$file_allegato."')");
    }
    $statement->execute();
}

function aggiornaStato($mail, $stato)
{
    $conn      = connect_database();
    $mail      = mysqli_real_escape_string($conn, $mail);
    $stato     = mysqli_real_escape_string($conn, $stato);
    $statement = $conn->prepare("UPDATE personale
                                SET stato = '$stato'
                                WHERE mail = '".$mail."'");
    $statement->execute();
}

function getUsersForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.* 
        FROM personale p
    ";

    return get_user_array($conn, $sql);
}

function searchUserForCuccurullo($id)
{
    $conn = connect_database();

    $sql = "
        SELECT p.* 
        FROM personale p 
        WHERE p.mail = '$id'
    ";

    return get_user_array($conn, $sql);
}

/**
 * @param  mysqli|null  $conn
 * @param  string  $sql
 *
 * @return array
 */
function get_user_array(?mysqli $conn, string $sql): array
{
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $users = [];

    while ($row = $results->fetch_assoc()) {
        $users[] = [
                "mail"        => $row['mail'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['cod_fiscale'],
                "data"        => $row['data_nascita'],
                "luogo"       => $row['luogo'],
        ];
    }

    return $users;
}

function getAttestatiForCuccurullo($mail)
{
    $conn = connect_database();

    $sql = "
    SELECT a.tipo FROM attestato a WHERE a.mail = '$mail'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $users = [];

    while ($row = $results->fetch_assoc()) {
        $users[] = [
                "tipo"    => $row['tipo'],
        ];
    }

    return $users;
}

function getTipiAttestatiForCuccurullo()
{
    $conn = connect_database();

    $sql = "
    SELECT DISTINCT a.tipo FROM attestato a;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $users = [];

    while ($row = $results->fetch_assoc()) {
        $users[] = [
                "tipo"    => $row['tipo'],
        ];
    }

    return $users;
}

function getDocentiForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_docente p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getAtaForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_ata p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getDCForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_da_compilare p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getDVForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_da_validare p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getVForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_validato p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getRCForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_richiesta_modifica p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getISForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_in_scadenza p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getNISForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale p
        WHERE in_servizio = 'no'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getInSForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale p
        WHERE in_servizio = 'si'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function getSForCuccurullo()
{
    $conn = connect_database();

    $sql = "
        SELECT p.mail AS mail, p.tipo AS tipo, p.nome AS nome, p.cognome AS cognome, p.note AS note, p.stato AS stato, p.in_servizio AS in_servizio, p.cod_fiscale AS c_f
        FROM personale_scaduto p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = [];

    while ($row = $results->fetch_assoc()) {
        $docenti[] = [
                "mail"        => $row['mail'],
                "tipo"        => $row['tipo'],
                "nome"        => $row['nome'],
                "cognome"     => $row['cognome'],
                "note"        => $row['note'],
                "stato"       => $row['stato'],
                "in_servizio" => ($row['in_servizio'] == 'si') ? 'Si' : 'No',
                "c_f"         => $row['c_f'],
        ];
    }

    return $docenti;
}

function cancellaCertificatiVecchi($mail = '', $att = '')
{
    $conn = connect_database();
    $mail = mysqli_real_escape_string($conn, $mail);
    
    if ($mail == '') {
        if ($att == '') {
            $sql = "
            DELETE FROM attestato
            WHERE 1
            ";
        } else {
            $sql = "
            DELETE FROM attestato
            WHERE tipo = '".$att."'
            ";
        }
    } else {
        if ($att == '') {
            $sql = "
            DELETE FROM attestato
            WHERE mail = '".$mail."'
            ";
        } else {
            $sql = "
            DELETE FROM attestato
            WHERE mail = '".$mail."' AND tipo = '".$att."'
            ";
        }
    }


    $stmt = $conn->prepare($sql);
    $stmt->execute();
}
