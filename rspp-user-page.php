<?php

include 'utils/database-consts.php';

$email = $_POST['user'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$c_f = $_POST['c_f'];
$note = $_POST['note'];
$stato = $_POST['stato'];
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
<h1 align="center">Informazioni sull'utente <?php echo $nome . " " . $cognome ?></h1><br>

<form align="center" action="modifica_form.php" method="post">
    <table class="button">
        <!--        Hidden inputs       -->
        <input type="hidden" name="email" value="<?php echo $email ?>"/>
        <input type="hidden" name="stato" value="<?php echo $stato ?>"/>
        <tr>
            <td>Nome</td>
            <td>&nbsp;</td>
            <td><input type="text" id="nome" name="nome" value="<?php echo $nome; ?>"/></td>
        </tr>
        <tr>
            <td>Cognome</td>
            <td>&nbsp;</td>
            <td><input type="text" id="cognome" name="cognome" value="<?php echo $cognome; ?>"/></td>
        </tr>
        <tr>
            <td>Codice fiscale</td>
            <td>&nbsp;</td>
            <td><input type="text" id="codice_fiscale" name="codice_fiscale" value="<?php echo $c_f; ?>"/></td>
        </tr>
        <tr>
            <td>In servizio</td>
            <td>&nbsp;</td>
            <td><?php echo $in_servizio; ?></td>
        </tr>
        <tr>
            <td><input type="submit" value="Conferma le modifiche" class="btn btn-success"/></td>
        </tr>
    </table>
    <br>
    <a href=cuccurullo_page.php>Ritorna alla pagina principale</a>
</form>

<div align="center" style="margin-top: 50px; padding: 20px">
    <?php
    $conn = connect_database();
    $statement = $conn->prepare("SELECT tipo AS t, descrizione AS d, data_scadenza AS d_s, file_allegato AS f
                                            FROM attestato
                                            WHERE mail = '" . $email . "'");
    $statement->execute();
    $results = $statement->get_result();

    $row = mysqli_fetch_row($results);

    if ($row == null) echo "<h2 style='color:#700016;'>Non hai nessun certificato !</h2>";
    else {
        ?>
        <table style='background-color:#e8e8e8;'>
            <tr>
                <th>Tipologia</th>
                <th>Descrizione</th>
                <th>Data di scadenza</th>
            </tr>
            <?php
            do {
            ?>
            <tr>
                <td><?= $row[0] ?></td>
                <td><?= $row[1] ?></td>
                <td><?php if ($row[2] == null) echo '---'; else echo $row[2]; ?></td>
                <td>
                    <?php echo '<a href="./certificati/' . str_replace('.', '_', $email) . '/' . $row[0] . '/' . $row[3] . '">Visualizza PDF</a>' ?>
                </td>
                <td>

                    <form action="form_attestato_modifica.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $email ?>"/>
                        <input type="hidden" name="tipologia" value="<?php echo $row[0] ?>"/>
                        <input type="hidden" name="desc" value="<?php echo $row[1] ?>"/>
                        <input type="hidden" name="data_scadenza" value="<?php echo $row[2] ?>"/>
                        <input type="submit" value="Modifica attestato">
                    </form>

                </td>
            <tr>
                <?php
                } while ($row = mysqli_fetch_row($results));
                ?>
        </table>
        <?php
    }
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