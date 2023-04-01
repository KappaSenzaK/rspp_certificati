<?php
    if(!isset($database_set))
        include 'database\database.php';
    session_start();
    $mail = $_SESSION['mail'];
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
    <style>
        th, td {
            border: 2px solid white;
        }
    </style>

    <div>
    <?php
        include "./html/header.html";
        ?>
    </div>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>

    
    
    <div align="center">
        <?php
            $conn = connect_database();
            $statement = $conn->prepare("SELECT stato
                                         FROM personale
                                         WHERE mail = '" . $mail . "'");
            $statement->execute();
            $results = $statement->get_result();
            $row = mysqli_fetch_row($results);
            if($row[0] == 'Da compilare') {
                include './userForm.php';
            }
            else
            {
                echo '<div align="left">';
                include "./html/user_page.html";
                echo '</div>';
                ?>
                    <br><div align="center">
                        <h1>Pagina di <?php 
                            $nameAndSurname = retrieveNameAndSurname($mail);
                            echo $nameAndSurname['name'] . ' ' . $nameAndSurname['surname'];
                        ?></h1>
                    </div><br>
                    <h2>Certificati di cui sei in possesso</h2>
                    
                <?php
                $statement = $conn->prepare("SELECT attestato_generico, attestato_specifico, data_scadenza
                                            FROM personale_generale
                                            WHERE mail = '" . $mail . "'");
                $statement->execute();
                $results = $statement->get_result();

                $row = mysqli_fetch_row($results);
                
                if($row == null) echo "<h2 style='color:#700016;'>Non hai nessun certificato !</h2>";
                else {
                    ?>
                        <table style='background-color:#e8e8e8;'>
                            <?php if($row[0] != null) echo "<tr><th>Certificato generico </th><th style='color:#363636;'> Visualizza pdf</th></tr>" ?>
                            <?php if($row[1] != null) echo "<tr><th id='att_sp'>Certificato specifico (scadrà il " . $row[2] . ") </th><th style='color:#363636;'> Visualizza pdf</th></tr>" ?>
                        </table>
                    <?php
                }
                if($row[1] != null){
                    $statement = $conn->prepare("SELECT mail
                                                FROM  personale_generale_attestato_specifico_in_scadenza
                                                WHERE mail = '" . $mail . "'");
                    $statement->execute();
                    $in_scad_results = $statement->get_result();

                    if(mysqli_num_rows($in_scad_results) != "0")
                        echo "<div style='color:#700016;'>Attenzione: il certificato specifico è in scadenza !</div>";
                    else {
                        $statement = $conn->prepare("SELECT mail
                                                     FROM  personale_generale_attestato_specifico_scaduto
                                                     WHERE mail = '" . $mail . "'");
                        $statement->execute();
                        $scad_results = $statement->get_result();

                        if(mysqli_num_rows($scad_results) != "0")
                        echo "<div style='color:#700016;'>Attenzione: il certificato specifico è scaduto !</div>";
                    }
                }
            ?>
            <br><br><br>
            <button id="helpButton" class="button" onclick="help()"> Hai bisogno di aiuto? </button>&emsp;<button id="helpButton" class="button" onclick="window.open('userPageRequestedModify.php'); window.close()"> Richiedi una modifica </button>
            <br><br>
            <div id="help"></div>
        <script src="./js/user_page.js"></script>
        <?php
    }
    ?>
    </div>
</body>
</html>