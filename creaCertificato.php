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

<div style="text-align: center">
    <?php
    include "./html/cuccurullo_page.html"
    ?>


    <?php
        include 'database/database.php';
        include "./utils/files_utility.php";
    //   echo $user;
    //   echo $tipo;
    if (isset($_FILES['att']) && $_FILES['att']['error'] == 0) {
        if ($_FILES['att'] != null) {
            if($_POST["tipiAttestato"] != '') {
                if($_POST["users"] != '') {
                    $mail = $_POST['users'];
                    $nameAndSurname = retrieveNameAndSurname($mail);
                    $tipo = $_POST['tipiAttestato'];
                    $file_allegato = $_FILES['att']['name'];

                    if (saveFileToWebServer($mail, $_FILES['att'],$tipo)) {
                        insertNewAttestato($mail, $_POST['descrizione'], $tipo, $_POST["scad"], $file_allegato);
                    }
                }
            }    
        }
    }
    ?>
    <h2>I dati sono stati inseriti !</h2><br><br>
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