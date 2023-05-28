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
    $users = getAttestatiForCuccurullo();

    echo '<table class="table table-striped table-hover w-75 ">
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Stato</th>

                </tr>
            </thead>
            <tbody>';

    $id = 0;

    $mail    = "mail";
    $nome    = "nome";
    $cognome = "cognome";
    $tipo    = "tipo";
    $stato   = "stato";


    $fine_mail = "@tulliobuzzi.edu.it";

    foreach ($users as $user) {
        $id++;
        $da_compilare = $user['stato'] == StatoCertificati::DA_COMPILARE;

        echo '<tr id="'.$id.'" >
                <td id="'.$id.$mail.'">'.$user['mail'].$fine_mail.'</td>
                <td id="'.$id.$tipo.'">'.$user['tipo'].$fine_mail.'</td>
                <td id="'.$id.$nome.'">'.$user['nome'].'</td>
                <td id="'.$id.$cognome.'">'.$user['cognome'].'</td>
                <td id="'.$id.$stato.'" class="'.($da_compilare ? 'bg-warning' : '').'">'.$user['stato'].'</td>
                <td>
                    <form method="post" action="GeneraPDF.php" align="center">
                        <input type="hidden" name="user" id="user" value="'.$user['mail'].'"/>
                        <input type="hidden" name="tipo" id="tipo" value="'.$user['tipo'].'"/>
                        <input type="hidden" name="nome" value="'.$user['nome'].'"/>
                        <input type="hidden" name="cognome" value="'.$user['cognome'].'"/>
                        <input type="hidden" name="data" value="'.$user['data'].'"/>
                        <input type="hidden" name="luogo" value="'.$user['luogo'].'"/>
                        <input type="hidden" name="stato" value="'.$user['stato'].'"/>
                        <input type="submit" value="Certificato"/>
                    </form>
                </td>
            </tr>';
    }
    echo '</tbody>
        </table>';

    ?>


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
