<?php
$header = false;
foreach (get_included_files() as $file)
    if (false !== strpos($file, 'header.html'))
        $header = true;

if (!$header)
    include 'html/header.html';

if (!isset($_SESSION))
    session_start();

include 'checkPage.php';

$mail = $_SESSION['mail'];

if (isset($_GET['cambiato'])) {
    $generico = $_GET['cambiato'] == 'true';
} else {
    //indica se l'utente e' nella pagina per il certificato generico o no
    $generico = true;
}
if (!isset($database_set))
    include 'database/database.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>Inserimento certificazioni sulla sicurezza sul lavoro</title>
</head>
<body class="font">

<link rel="stylesheet" href="./css/button.css">
<link rel="stylesheet" href="./css/font.css">
<style> th, td {
        padding: 10pt;
    } </style>
<br>
<div align="center">
    <h1>Pagina di <?php
        $nameAndSurname = retrieveNameAndSurname($mail);
        echo $nameAndSurname['name'] . ' ' . $nameAndSurname['surname'];
        ?></h1>
</div>
<br>

<?php
if (isset($_POST['add_cert']))
    if ($_POST['add_cert'] == "...")
        include 'html/user_form_select.html';

if (isset($_GET['add_cert'])) {
    echo '<br><h1 align="center">';
    if ($_GET['add_cert'] != 'Altro')
        echo $_GET['add_cert'];
    else
        echo 'Attestato di altra tipologia';
    echo '</h1><br>';

    $_SESSION['cert'] = $_GET['add_cert'];

    if ($_GET['add_cert'] == 'Attestato di formazione generale' ||
        $_GET['add_cert'] == 'Attestato di formazione specifica - rischio medio' ||
        $_GET['add_cert'] == 'Attestato di formazione - rischio alto' ||
        $_GET['add_cert'] == 'Attestato di formazione sicurezza per il preposto' ||
        $_GET['add_cert'] == 'Attestato di formazione per RLS' ||
        $_GET['add_cert'] == 'Attestato di formazione per rischio di incendio - rischio medio' ||
        $_GET['add_cert'] == 'Attestato di formazione per rischio di incendio - rischio alto' ||
        $_GET['add_cert'] == 'Attestato di formazione per il primo soccorso' ||
        $_GET['add_cert'] == 'Attestato di formazione BLSD') {
        $_SESSION['cert_t'] = 'no_scad';
        include 'html/user_form.html';
    } else if ($_GET['add_cert'] == 'Altro') {
        $_SESSION['cert_t'] = 'scad';
        include 'html/user_form_altro.html';
    } else {
        $_SESSION['cert_t'] = 'scad';
        include 'html/user_form_scade.html';
    }
}
?>
</body>
</html>