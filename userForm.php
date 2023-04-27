<?php
    $header = false;
    foreach(get_included_files() as $file)
        if(false !== strpos($file, 'header.html'))
            $header = true;
    
    if(!$header)
        include 'html/header.html';

    if(!isset($_SESSION))
        session_start();
    $mail = $_SESSION['mail'];

    if(isset($_GET['cambiato'])){
        $generico = $_GET['cambiato'] == 'true';
    } else {
        //indica se l'utente e' nella pagina per il certificato generico o no
        $generico = true;
    }
    if(!isset($database_set))
        include 'database/database.php';
?>
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

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>
    <br><div align="center">
        <h1>Pagina di <?php
            $nameAndSurname = retrieveNameAndSurname($mail);
            echo $nameAndSurname['name'] . ' ' . $nameAndSurname['surname'];
        ?></h1>
    </div><br>

    <?php
        if(!isset($_POST['cert']))
            echo '
                <h2>Che tipo di certificato vuoi aggiungere?</h2>
                <form method="post" action="userForm.php">
                    <input type="hidden" name="add_cert" id="add_cert" value="1">
                    <select name="cert" id="cert">
                        <option value=""></value>
                </form>';
    ?>

    <script src="./js/user_form.js"></script>
</body>
</html>