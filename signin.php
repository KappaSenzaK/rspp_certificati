<?php
session_start();

include 'database/database.php';
include 'utils/emails.php';

include 'html/header.html';

?>
    <head>
        <title>Creazione dell'account</title>
    </head>
<?php

if(!isset($_SESSION['signin'])) {
    $_SESSION['signin'] = true;
}

if($_SESSION['signin']){
    $_SESSION['signin'] = true;

    include 'html/signin.html';

    $_SESSION['signin'] = false;
    die();
}

if(!isset($_POST['email'])
    || !isset($_POST['tipo'])
    || !isset($_POST['nomeUtente'])
    || !isset($_POST['cognomeUtente'])
    || !isset($_POST['codiceFiscale'])
    )
{
    $_SESSION['signin'] = true;
    die("<br><h1>Campi non validi!</h1>");
}

$email = $_POST['email'];
$tipo = $_POST['tipo'];
$nomeUtente = $_POST['nomeUtente'];
$cognomeUtente = $_POST['cognomeUtente'];
$codiceFiscale = $_POST['codiceFiscale'];

function firstLetterToUpperCase($string) {
    if (ctype_upper($string[0])) {
        return strtoupper(substr($string, 0, 1)) . substr($string, 1,  strlen($string)-1);
    }
    return $string;
}

$nomeUtente = firstLetterToUpperCase($nomeUtente);
$cognomeUtente = firstLetterToUpperCase($cognomeUtente);

$codiceFiscale = strtoupper($codiceFiscale);

if(existAccountByEmail($email)) {
    $_SESSION['signin'] = true;
    die("<br<h1>Attenzione: l'account è già esistente!</h1>");
}

$digestPassword = substr(hash(
        'md5',
        $email.$tipo.$nomeUtente.$cognomeUtente.$codiceFiscale),
    0, 5
);

$sendmail_message = sendEmail(
        $email."@tulliobuzzi.edu.it",
        "Credenziali RSPP Certificati",
        "<h1>Password per l'accesso all'account: " . $digestPassword . "</h1>"
);
?>


echo "<h2>" . $sendmail_message . "</h2>";

if($sendmail_message = "Al tuo indirizzo e-mail istituzionale è stata inviata una mail di conferma") {
    echo
}

?>

<h1>Account creata!</h1>;
<button onclick='window.location.replace("http://localhost:80/rspp_certificati/index.php")'>Torna alla pagina di accesso</button>;
