<!DOCTYPE html>
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

        #email-section{
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
    $users = getUsersForCuccurullo();

    echo '<table class="table table-striped table-hover w-75 ">
            <thead>
                <tr>
                    <th scope="col">Email</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Note</th>
                    <th scope="col">Stato</th>
                    <th scope="col">Data scadenza attestato specifico</th>
                </tr>
            </thead>
            <tbody>';

    $id = 0;

    $mail = "mail";
    $nome = "nome";
    $cognome = "cognome";
    $note = "note";
    $stato = "stato";
    $ass_data_scadenza = "ass_data_scadenza";

    $fine_mail = "@tulliobuzzi.edu.it";

    foreach ($users as $user) {
        $id++;
        $da_compilare = $user['stato'] == StatoCertificati::DA_COMPILARE;
        $data_scadenza_specifico_attivo = $user['ass_data_scadenza'] > new DateTime();

        echo '<tr id="' . $id . '" >
                <td id="' . $id . $mail . '">' . $user['mail'].$fine_mail . '</td>
                <td id="' . $id . $nome . '">' . $user['nome'] . '</td>
                <td id="' . $id . $cognome . '">' . $user['cognome'] . '</td>
                <td id="' . $id . $note . '">' . $user['note'] . '</td>
                <td id="' . $id . $stato . '" class="' . ($da_compilare ? 'bg-warning' : '') . '">' . $user['stato'] . '</td>
                <td id="' . $id . $ass_data_scadenza . '" class="' . ($data_scadenza_specifico_attivo ? 'bg-danger' : '') . '">' . $user['ass_data_scadenza'] . '</td>
            </tr>';
    }
    echo '</tbody>
        </table>';

    ?>


</div>

<div id="email-section" class="w-25">

    <div></div>

    <form action="http://localhost:80/rspp_certificati/sendmail.php" method="post" class="font form">
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