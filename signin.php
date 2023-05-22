<link rel="stylesheet" href="css/button.css">
<link rel="stylesheet" href="css/font.css">
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creazione dell'account</title>
    <link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<?php
session_start();

include 'database/database.php';
include 'utils/emails.php';

include 'html/header.html';
?>
<script src="js/login.js"></script>
<head>
    <title>Creazione dell'account</title>
</head>
<div class="font" align="center"><h1>Creazione di un nuovo account</h1></div>
<br>
<?php

if (!isset($_SESSION['signin'])) {
    $_SESSION['signin'] = true;
}

if (isset($_POST['back'])) {
    if (isset($_SESSION['code']))
        unset($_SESSION['code']);
}

if (isset($_SESSION['code'])) {
    if (isset($_POST['code']))
        if ($_SESSION['code'] == $_POST['code']) {
            // createNewAccount($mail, $tipo, $nome, $cognome, $cod_fiscale, $pw)
            createNewAccount(
                $_SESSION['signin_mail'],
                $_SESSION['signin_tipo'],
                $_SESSION['signin_nomeUtente'],
                $_SESSION['signin_cognomeUtente'],
                $_SESSION['signin_codiceFiscale'],
                $_SESSION['code']
            );

            echo '<div class="font" align="center">
                    <h2>Il tuo account è stato creato con successo!</h2><br><br>
                    Adesso puoi tornare alla pagina di accesso ed entrare nella piattaforma.<br><br>
                    Ricorda che i dati che hai inserito passeranno sotto la revisione dell\'RSPP scolastico,
                    perciò potrebbero variare nel caso in cui ci siano delle incongruenze!<br><br><br>
                    <form action="index.php" method="post">
                        <input type="submit" value="Torna alla pagina di accesso">
                    </form>
                 </div>';

            session_destroy();
            die();
        } else {
            echo '<div class="font" align="center">
                    <h2>Attenzione! La password inserita non è corretta!</h2><br>
                    <form class="button" action="signin.php" method="post">
                        <input type="text" name="code" id="code" placeholder="Codice">
                        <input type="submit">
                    </form><br>
                    <form action="signin.php" method="post">
                        <input type="hidden" id="back" name="back" value="a">
                        <input type="submit" value="Torna indietro">
                    </form>
                 </div>';
        }
    die();
}


if ($_SESSION['signin']) {
    include 'html/signin.html';

    $_SESSION['signin'] = false;
    die();
}

if (!isset($_POST['email'])
    || !isset($_POST['tipo'])
    || !isset($_POST['nomeUtente'])
    || !isset($_POST['cognomeUtente'])
    || !isset($_POST['codiceFiscale'])
) {
    include 'html/signin.html';

    $_SESSION['signin'] = false;
    die();
}

$email = $_POST['email'];
$tipo = $_POST['tipo'];
$nomeUtente = $_POST['nomeUtente'];
$cognomeUtente = $_POST['cognomeUtente'];
$codiceFiscale = $_POST['codiceFiscale'];


$_SESSION['signin_mail'] = $email;
$_SESSION['signin_tipo'] = $tipo;
$_SESSION['signin_nomeUtente'] = $nomeUtente;
$_SESSION['signin_cognomeUtente'] = $cognomeUtente;
$_SESSION['signin_codiceFiscale'] = $codiceFiscale;


function firstLetterToUpperCase($string)
{
    if (ctype_upper($string[0])) {
        return strtoupper(substr($string, 0, 1)) . substr($string, 1, strlen($string) - 1);
    }
    return $string;
}

$nomeUtente = firstLetterToUpperCase($nomeUtente);
$cognomeUtente = firstLetterToUpperCase($cognomeUtente);

$codiceFiscale = strtoupper($codiceFiscale);

if (existAccountByEmail($email)) {
    $_SESSION['signin'] = true;
    die("<br><h1>Attenzione: l'account è già esistente!</h1><br><h1><button class='button' onclick='openLogin()'>Effettua il login! </button></h1>");
}

$digestPassword = substr(hash(
    'md5',
    $email . $tipo . $nomeUtente . $cognomeUtente . $codiceFiscale),
    0, 5
);

$sendmail_message = sendEmail(
    $email . "@tulliobuzzi.edu.it",
    "Credenziali RSPP Certificati",
    "<h1>Password per l'accesso all'account: " . $digestPassword . "</h1>"
);

if ($sendmail_message != EmailStatus::OK) {
    echo "<h2>" . $sendmail_message . "</h2>";
    die();
}

$_SESSION['signin'] = true;
$_SESSION['code'] = $digestPassword;

?>
<div class="font">
    <div align="center">

        <h2>All'indirizzo e-mail <?php echo $email; ?>@tulliobuzzi.edu.it
            è stata inviata la tua nuova password</h2> <br>

        Controlla bene che i dati che hai inserito siano corretti:<br>
        <h3>
            <?php
            echo "Indirizzo e-mail: " . $email . "<br>" .
                "Tipologia di utente: " . $tipo . "<br>" .
                "Nome e cognome: " . $nomeUtente . " " . $cognomeUtente . "<br>" .
                "Codice fiscale: " . $codiceFiscale . "<br>";
            ?>
        </h3><br>
        <button class='button' onclick='location.reload()'> I tuoi dati non sono corretti? Torna all'inserimento
        </button>
        <br><br><br>
        Se tutti i dati sono corretti, inserisci qui sotto il codice che ti è stato inviato via e-mail per creare il tuo
        account<br>
        <br>
        <form class="button" action="signin.php" method="post">
            <input type="text" name="code" id="code" placeholder="Codice">
            <input type="submit">
        </form>
    </div>
</div>