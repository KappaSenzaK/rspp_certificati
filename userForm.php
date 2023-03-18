<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>Inserimento requisiti sulla sicurezza sul lavoro</title>
</head>
<body class="font">

    <div>
    <?php
        include "./header.html"
        ?>
    </div>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>

    <br><div align="center">
        <h1>Pagina di <?php echo 'placeholder nome cognome'?></h1>
    </div><br>
    
    <div align="center">
        <form action="placeholder.php" method="post">
            <h3>Inserisci i certificati di cui già sei in possesso</h3><br>

            <table style="padding: 2;">
                <tr>
                    <th><h2>Certificato generico</h2></th>
                    <th><h2>Certificato specifico</h2></th>
                </tr>
                <tr>
                    <th>
                        <input class="button" type="file" name="att_g"></input>
                    </th>
                    <th>
                        <input class="button" type="file" name="att_s"></input>
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th>
                        Data di scadenza &emsp; <input type="date" name="att_s_date"></input>
                    </th>
                </tr>
                <input type="hidden" id="mail" name="mail" value="<?php echo 'placeholder' ?>">
            </table>
            <input class="button" type="submit" value="Invia il modulo"></input>
        </form>
        <br><br><br>
        <button id="helpButton" class="button" onclick="help()"> Hai bisogno di aiuto? </button>
        <br><br>
        <div id="help"></div>
    </div>
    <script src="./js/user_form.js"></script>

</body>
</html>