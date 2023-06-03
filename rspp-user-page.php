<?php

include 'utils/database-consts.php';

$email       = $_POST['user'];
$nome        = $_POST['nome'];
$cognome     = $_POST['cognome'];
$c_f         = $_POST['c_f'];
$note        = $_POST['note'];
$stato       = $_POST['stato'];
$in_servizio = $_POST['in_servizio'];

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User page</title>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/font.css">
  <link rel="stylesheet" href="css/button.css">
</head>
<body class="font">
<?php
include "./html/header.html";
include "./html/cuccurullo_page.html";
?>
<br>
<h1 align="center">Informazioni sull'utente <?php
    echo $nome." ".$cognome ?></h1><br>

<?php

$user = findUserByEmail($email);

?>

<form align="center" action="modifica_form.php" method="post">
  <table class="button">
    <!--        Hidden inputs       -->
    <input type="hidden" name="email" value="<?php
    echo $email ?>"/>
    <tr>
      <td>Nome</td>
      <td>&nbsp;</td>
      <td><input type="text" id="nome" name="nome" value="<?php
          echo $nome; ?>"/></td>
    </tr>
    <tr>
      <td>Cognome</td>
      <td>&nbsp;</td>
      <td><input type="text" id="cognome" name="cognome" value="<?php
          echo $cognome; ?>"/></td>
    </tr>
    <tr>
      <td>Codice fiscale</td>
      <td>&nbsp;</td>
      <td><input type="text" id="codice_fiscale" name="codice_fiscale" value="<?php
          echo $c_f; ?>"/></td>
    </tr>
    <tr>
      <td>In servizio</td>
      <td>&nbsp;</td>
      <td><?php
          $personale_servizi = enum_personale_in_servizio();
          echo "<select name='in_servizio'>";
          foreach ($personale_servizi as $servizio) {
              $echo = $servizio == $in_servizio ? "<option value='$servizio' selected>$servizio</option>" : "<option value='$servizio'>$servizio</option>";
              echo $echo;
          }
          echo "</select>";
          ?></td>
    </tr>
    <tr>
      <td>Descrizione</td>
      <td>&nbsp;</td>
      <td>
        <input type="text" name="desc" value="<?php
        echo $user['note'] ?>"/>
      </td>
    </tr>
    <tr>
      <td>Stato</td>
      <td>&nbsp;</td>
      <td>
          <?php
          $stati_personale = enum_personale_stato();
          echo "<select name='stato'>";
          foreach ($stati_personale as $stato_personale) {
              $echo = $user['stato'] == $stato_personale ? "<option value='$stato_personale' selected >" : "<option value='$stato_personale' >";
              echo $echo;
          }
          echo "</select>";
          ?>
      </td>
    </tr>
  </table>
  <br><br>
  <input type="submit" value="Conferma le modifiche" class="btn btn-success"/>

</form>

<div align="center" style="margin-top: 50px; padding: 20px">
    <?php
    $conn      = connect_database();
    $statement = $conn->prepare("SELECT tipo AS t, descrizione AS d, data_scadenza AS d_s, file_allegato AS f
                                            FROM attestato
                                            WHERE mail = '".$email."'");
    $statement->execute();
    $results = $statement->get_result();

    $row = mysqli_fetch_row($results);

    echo "<h2>Attestati</h2>";

    if ($row == null) {
        echo "<h2 style='color:#700016;'>L'utente non ha nessun certificato!</h2>";
    } else {
        ?>
      <table style='background-color:#e8e8e8; '>
        <tr style="border: 2px solid white;">
          <th style="border: 2px solid white;">&nbsp; Tipologia &nbsp;</th>
          <th style="border: 2px solid white;">&nbsp; Descrizione &nbsp;</th>
          <th style="border: 2px solid white;">&nbsp; Data di scadenza &nbsp;</th>
        </tr>
          <?php
          do {
              ?>
            <tr style="border: 2px solid white;">
              <td style="border: 2px solid white;"><?= $row[0] ?></td>
              <td style="border: 2px solid white;"><?= $row[1] ?></td>
              <td style="border: 2px solid white;"><?php
                  if ($row[2] == null || $row[2] == "0000-00-00") {
                      echo '---';
                  } else {
                      $statement = $conn->prepare("select p2.mail, a_s.tipo, a_s.data_scadenza 
                            from (`personale` `p2` join `attestato` `a_s` on(`a_s`.`data_scadenza` is not null AND `a_s`.`mail` = `p2`.`mail`)) 
                            where p2.mail = '$email' AND a_s.tipo = '$row[0]' AND a_s.descrizione = '$row[1]' AND
                                `a_s`.`data_scadenza` < curdate();");
                      $statement->execute();
                      $scaduto = $statement->get_result();

                      $scaduto = mysqli_fetch_row($scaduto);

                      if ($scaduto == null) {
                          echo $row[2];
                      } else {
                          echo '<form action="invia_mail_scaduto.php" method="post">'."<span style='color: #ff0000'>$row[2] </span>".'
                                <input type="hidden" name="mail" value="'.$email.'"/>
                                <input type="hidden" name="attestato" value="'.$row[0].'"/>
                                <input type="hidden" name="data_scadenza" value="'.$row[2].'"/>
                                <input type="submit" value="✉">
                            </form>';
                      }
                  }
                  ?></td>
              <td style="border: 2px solid white;">
                  <?php
                  echo '<a href="./certificati/'.str_replace('.', '_',
                                  $email).'/'.$row[0].'/'.$row[3].'">Visualizza PDF<span>&nbsp&nbsp</span></a>' ?>
              </td style="border: 2px solid white;">
              <td style="border: 2px solid white;">

                <form action="form_attestato_modifica.php" method="post">
                  <input type="hidden" name="email" value="<?php
                  echo $email ?>"/>
                  <input type="hidden" name="tipologia" value="<?php
                  echo $row[0] ?>"/>
                  <input type="hidden" name="desc" value="<?php
                  echo $row[1] ?>"/>
                  <input type="hidden" name="data_scadenza" value="<?php
                  echo $row[2] ?>"/>
                  <input type="submit" value="Modifica attestato">
                </form>
              </td>
              <td style="border: 2px solid white;">

                <form action="form_attestato_elimina.php" method="post">
                  <input type="hidden" name="email" value="<?php
                  echo $email ?>"/>
                  <input type="hidden" name="tipologia" value="<?php
                  echo $row[0] ?>"/>
                  <input type="hidden" name="desc" value="<?php
                  echo $row[1] ?>"/>
                  <input type="hidden" name="data_scadenza" value="<?php
                  echo $row[2] ?>"/>
                  <input type="submit" value="Elimina attestato">
                </form>
              </td>
            </tr>
              <?php
          } while ($row = mysqli_fetch_row($results));
          ?>
      </table>
        <?php
    }
    ?>
    <?php
    if ($stato != "Validato" && $stato == "Da validare") {
        echo '<br><form method="post" action="utenteValidato.php" align="center">
                <input type="hidden" name="user" id="user" value="'.$email.'">
                <input type="hidden" name="nome" value="'.$nome.'">
                <input type="hidden" name="cognome" value="'.$cognome.'">
                <input type="hidden" name="c_f" value="'.$c_f.'">
                <input type="hidden" name="note" value="'.$note.'">
                <input type="hidden" name="stato" value="'.$stato.'">
                <input type="hidden" name="in_servizio" value="'.$in_servizio.'">
                <input type="submit" value=" Valida " class="btn btn-success">
            </form>';
    }
    if ($stato == "Richiesta modifica" && $in_servizio == "Si") {
        echo '<br><form method="post" action="modificaCons.php" align="center">
                <input type="hidden" name="user" id="user" value="'.$email.'">
                <input type="hidden" name="nome" value="'.$nome.'">
                <input type="hidden" name="cognome" value="'.$cognome.'">
                <input type="hidden" name="c_f" value="'.$c_f.'">
                <input type="hidden" name="note" value="'.$note.'">
                <input type="hidden" name="stato" value="'.$stato.'">
                <input type="hidden" name="in_servizio" value="'.$in_servizio.'">
                <input type="submit" value=" Permetti modifiche " class="btn btn-success">
            </form>';
    }
    ?>
  <br><br>
  <a href=cuccurullo_page.php>Ritorna alla pagina principale</a>
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