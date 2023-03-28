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

if(existAccountByEmail($email)) {
    die("<h1>L'account esiste di gia!");
}

$digestPassword = hash('md5', $email.$tipo.$nomeUtente.$cognomeUtente.$codiceFiscale.$dataNascita.$note);
echo "<h2>" . sendEmail($email, "Credenziali RSPP Certificati", "<h1>Salve, le invio la password generata: " . $digestPassword . "</h1>") . "</h2>";

createNewDefaultAccount($email, $tipo, $nomeUtente, $cognomeUtente, $codiceFiscale, $dataNascita, $note, $digestPassword);
?>

<h1>Account creata!</h1>;
<button onclick='window.location.replace("http://localhost:80/rspp_certificati/html/signin.html")'>Ritorna indietro</button>;
