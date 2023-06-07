<!DOCTYPE html>
<?php
session_start();
include 'checkPage.php';
if ($_SESSION['mail'] != 'rspp') {
?>
<h1>Errore nell'accesso!</h1><br><br><br>
<button id="helpButton" class="button" onclick="window.open('index.php'); window.close()"> Torna alla pagina di
  accesso
</button>
<?php
die();
}
?>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/index.css">
  <title>Main page del cuccurullo</title>

  <style>
      .table {
          margin: auto;
          width: 50% !important;
      }

      #email-section {
          padding-top: 70px;
          margin: auto;
          width: 50%;
      }
  </style>
</head>
<body>

<div>
    <?php
    include "./html/header.html"
    ?>
</div>
<div>
    <?php
    include "./html/cuccurullo_page.html"
    ?>

    <?php
    include 'database/database.php';
    ?>
</div>

<div id="email-section" class="w-25">

  <div></div>

  <form action="http://localhost:80/rspp_certificati/sendmail.php" method="post" class="font form">
    <input type="hidden" name="rspp" value="rspp">

    <label class="form-label" for="to">Inserisci l'indirizzo email del destinatario</label>
    <input class="button form-control" type="email" name="to" id="to" placeholder="Email destinatario">

    <br>

    <label class="form-label" for="subject">Inserisci l'oggetto dell'email</label>
    <input class="button form-control" type="text" name="subject" id="subject" placeholder="Oggetto email">

    <br>

    <label id="form-label" for="body">Inserisci il messaggio da inviare</label>
    <input class="button form-control" type="text" name="body" id="body" placeholder="Contenuto email">

    <br>

    <input class="button btn btn-success" type="submit" value="Invia email">
  </form>
</div>


<script src="js/actions.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

</body>
</html>
