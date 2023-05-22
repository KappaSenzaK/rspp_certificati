<?php

include 'utils/database-consts.php';

$email = $_POST['email'];
$tipologia = $_POST['tipologia'];
$desc = $_POST['desc'];
$data_scadenza = $_POST['data_scadenza'];

if (!isset($email) || !isset($tipologia) || !isset($desc) || !isset($data_scadenza)) {
    die("<h1>Dati non validi </h1>");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form attestato</title>
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
</div>

<div align="center">
    <form action="attestato_modifica.php" method="post">
        <label class="form-label" for="tipologia">Tipologia: </label>
        <select name="tipologia" id="tipologia" class="form-control w-25">
            <?php

            $attestato_tipo = attestato_tipo();
            foreach ($attestato_tipo as $attestato) {
                $echo = $attestato == $tipologia ? "<option value='$attestato' selected>$attestato</option>" : "<option value='$attestato'>$attestato</option>";
                echo $echo;
            }

            ?>
        </select>

        <label class="form-label" for="desc">Descrizione: </label>
        <input class="button form-control w-25" style="margin-bottom: 5px" type="text" id="desc" name="desc"
               value="<?php echo $desc ?>"/>

        <label class="form-label" for="data_scadenza">Data scadenza: </label>
        <input class="" type="date" style="margin-bottom: 5px" id="data_scadenza" name="data_scadenza"
               value="<?php echo $data_scadenza ?>"/>

        <input type="hidden" name="email" value="<?php echo $email ?>" />

        <input type="hidden" name="old_tipologia" value="<?php echo $tipologia ?>" />
        <input type="hidden" name="old_desc" value="<?php echo $desc ?>" />

        <input type="submit" value="Conferma modifiche"/>
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
