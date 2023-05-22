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
    <br><h1 align="center">Informazioni sull'utente <?php echo $nome . " " . $cognome ?></h1><br>

    <form align="center" action="modifica_form.php" method="post">
        <table class="button">
            <!--        Hidden inputs       -->
            <input type="hidden" name="email" value="<?php echo $email ?>" />
            <input type="hidden" name="stato" value="<?php echo $stato ?>" />
            <tr>
                <td>Nome </td><td>&nbsp;</td><td> <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>"/> </td>
            </tr>
            <tr>
                <td>Cognome </td><td>&nbsp;</td><td> <input type="text" id="cognome" name="cognome" value="<?php echo $cognome; ?>"/> </td>
            </tr>
            <tr>
                <td>Codice fiscale </td><td>&nbsp;</td><td> <input type="text" id="codice_fiscale" name="codice_fiscale" value="<?php echo $c_f; ?>"/> </td>
            </tr>
            <tr>
                <td>In servizio </td><td>&nbsp;</td><td><?php echo $in_servizio; ?></td>
            </tr>
            <tr>
                <td><input type="submit" value="Conferma le modifiche" class="btn btn-success" /></td>
            </tr>
        </table>

    </form>

    <a href=cuccurullo_page.php>Ritorna alla pagina principale</a>


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