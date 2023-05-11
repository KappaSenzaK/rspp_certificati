<?php
    $database_set = 0;

class StatoCertificati{
    const DA_COMPILARE = "Da compilare";
    const DA_REVISIONARE = "Da revisionare";
    const REVISIONATO = "Revisionato";
    const RICHIESTA_MODIFICA = "Richiesta modifica";
}

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

function createNewAccount($mail, $tipo, $nome, $cognome, $cod_fiscale, $pw) {
    $conn = connect_database();
    $statement = $conn->prepare(
        "INSERT INTO personale(mail, tipo, nome, cognome, cod_fiscale, stato, pw) 
                VALUES ('$mail', '$tipo', '$nome', '$cognome', '$cod_fiscale', 'Da compilare', '$pw')");
    $statement->execute();
}

function modifyAccount($nome, $cognome, $email, $codice_fiscale, $stato, $in_servizio) {
    $conn = connect_database();
    $statement = $conn->prepare("UPDATE personale 
    SET nome = '$nome', cognome = '$cognome', cod_fiscale = '$codice_fiscale', stato = '$stato', in_servizio = '$in_servizio' 
    WHERE mail = '$email'");

    $statement->execute();
}

function retrieveNameAndSurname($mail): array
{
    $conn = connect_database();
    $statement = $conn->prepare("SELECT DISTINCT nome, cognome
                                         FROM personale
                                         WHERE mail = '" . $mail . "'");
    $statement->execute();
    $results = $statement->get_result();
    $row = mysqli_fetch_row($results);

    return array("name" => $row[0], "surname" => $row[1]);
}

function insertNewAttestato($mail,
                            $descrizione = '',
                            $tipo = 'Altro',
                            $data_scadenza = null,
                            $file_allegato = ''
                            ) {
    $conn = connect_database();
    if($data_scadenza == null)
        $statement = $conn->prepare("INSERT INTO attestato(mail, tipo, descrizione, file_allegato)    
                                    VALUES ('" . $mail . "','" . $tipo . "','" . $descrizione . "', '" . $file_allegato ."')");
    else
        $statement = $conn->prepare("INSERT INTO attestato(mail, tipo, data_scadenza, descrizione, file_allegato)    
                                    VALUES ('" . $mail . "','" . $tipo . "','" . $data_scadenza . "','" . $descrizione . "', '" . $file_allegato ."')");
    $statement->execute();
}

function aggiornaStato($mail, $stato){
    $conn = connect_database();
    $statement = $conn->prepare("UPDATE personale
                                SET stato = '$stato'
                                WHERE mail = '" . $mail . "'");
    $statement->execute();
}

function getUsersForCuccurullo() {
    $conn = connect_database();

    $sql = "
        SELECT p.mail as mail, p.nome as nome, p.cognome as cognome, p.note as note, p.stato as stato, p.in_servizio as in_servizio, p.cod_fiscale as c_f
        FROM personale p
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $users = array();

    while($row = $results->fetch_assoc()) {
        $users[] = array(
            "mail" => $row['mail'],
            "nome" => $row['nome'],
            "cognome" => $row['cognome'],
            "note" => $row['note'],
            "stato" => $row['stato'],
            "in_servizio" => ($row['in_servizio'] == 's') ? 'Si' : 'No',
            "c_f" => $row['c_f']
            );
    }
    return $users;
}

function getDocentiForCuccurullo() {
    $conn = connect_database();

    $sql = "
        SELECT p.mail as mail, p.tipo as tipo, p.nome as nome, p.cognome as cognome, p.note as note, p.stato as stato, p.in_servizio as in_servizio, p.cod_fiscale as c_f
        FROM personale p
        WHERE tipo = 'docente'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = array();

    while($row = $results->fetch_assoc()) {
        $docenti[] = array(
            "mail" => $row['mail'],
            "tipo" => $row['tipo'],
            "nome" => $row['nome'],
            "cognome" => $row['cognome'],
            "note" => $row['note'],
            "stato" => $row['stato'],
            "in_servizio" => ($row['in_servizio'] == 's') ? 'Si' : 'No',
            "c_f" => $row['c_f']
            );
    }
    return $docenti;
}

function getAtaForCuccurullo() {
    $conn = connect_database();

    $sql = "
        SELECT p.mail as mail, p.tipo as tipo, p.nome as nome, p.cognome as cognome, p.note as note, p.stato as stato, p.in_servizio as in_servizio, p.cod_fiscale as c_f
        FROM personale p
        WHERE tipo = 'ata'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();

    $docenti = array();

    while($row = $results->fetch_assoc()) {
        $docenti[] = array(
            "mail" => $row['mail'],
            "tipo" => $row['tipo'],
            "nome" => $row['nome'],
            "cognome" => $row['cognome'],
            "note" => $row['note'],
            "stato" => $row['stato'],
            "in_servizio" => ($row['in_servizio'] == 's') ? 'Si' : 'No',
            "c_f" => $row['c_f']
            );
    }
    return $docenti;
}

function cancellaCertificatiVecchi($mail = '', $att = '') {
    $conn = connect_database();
    if($mail == ''){
        if($att == '')
            $sql = "
            DELETE FROM attestato
            WHERE 1
            ";
        else
            $sql = "
            DELETE FROM attestato
            WHERE tipo = '".$att."'
            ";
    }
    else{
        if($att == '')
            $sql = "
            DELETE FROM attestato
            WHERE mail = '".$mail."'
            ";
        else
            $sql = "
            DELETE FROM attestato
            WHERE mail = '".$mail."' AND tipo = '".$att."'
            ";
    }
       
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}