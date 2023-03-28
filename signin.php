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
<body class="font">
<br>
<div align="center">
    <h1>Signin page</h1>
    <form action="loginAction.php" method="post">

        <label for="email">Inserisci l'indirizzo e-mail del nuovo utente</label>
        <input type="text" name="email" id="email" required>@tulliobuzzi.edu.it

        <label for="tipo">Ata o docente</label>
        <select name="tipo" id="tipo">
            <option value="ata">Ata</option>
            <option value="docente">Docente</option>
        </select>

        <label for="nomeUtente">Nome utente</label>
        <input type="text" name="nomeUtente" id="nomeUtente">

        <label for="cognomeUtente">Cognome utente</label>
        <input type="text" name="cognomeUtente" id="cognomeUtente">

        <label for="codiceFiscale">Codice fiscale</label>
        <input type="text" name="codiceFiscale" id="codiceFiscale">

        <label for="dataNascita">Data di nascita</label>
        <input type="date" name="dataNascita" id="dataNascita">

        <label for="note">Note</label>
        <input type="text" name="note" id="note">

        <input class="button" type="submit" value="Invia il modulo" name="loginAction" id="loginAction">
    </form>
</div>

<script src="js/actions.js"></script>
</body>
</html>
