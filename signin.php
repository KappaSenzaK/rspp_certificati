<?php
session_start();

include 'database/database.php';
include 'utils/emails.php';

$email = $_POST['email'];
$tipo = $_POST['tipo'];
$nomeUtente = $_POST['nomeUtente'];
$cognomeUtente = $_POST['cognomeUtente'];
$codiceFiscale = $_POST['codiceFiscale'];
$dataNascita = $_POST['dataNascita'];
$note = $_POST['note'];

if(!isset($email)
    || !isset($tipo)
    || !isset($nomeUtente)
    || !isset($cognomeUtente)
    || !isset($codiceFiscale)
    || !isset($dataNascita))
{
    die("<h1>Campi non validi</h1>");
}

if(!isset($note)) {
    $note = "Nessuna nota";
}

function firstLetterToUpperCase($string) {
    if (ctype_upper($string[0])) {
        return strtoupper(substr($string, 0, 1)) . substr($string, 1,  strlen($string)-1);
    }
    return $string;
}

$nomeUtente = firstLetterToUpperCase($nomeUtente);
$cognomeUtente = firstLetterToUpperCase($cognomeUtente);
$note = firstLetterToUpperCase($note);

$codiceFiscale = strtoupper($codiceFiscale);

if(existAccountByEmail($email)) {
    die("<h1>L'account esiste di gia!");
}

$digestPassword = hash('md5', $email.$tipo.$nomeUtente.$cognomeUtente.$codiceFiscale.$dataNascita.$note);
echo "<h2>" . sendEmail($email."@tulliobuzzi.edu.it", "Credenziali RSPP Certificati", "<h1>Salve, le invio la password generata: " . $digestPassword . "</h1>") . "</h2>";

createNewDefaultAccount($email, $tipo, $nomeUtente, $cognomeUtente, $codiceFiscale, $dataNascita, $note, hash('sha256', $digestPassword));
?>

<h1>Account creata!</h1>;
<button onclick='window.location.replace("http://localhost:80/rspp_certificati/html/signin.html")'>Ritorna indietro</button>;
