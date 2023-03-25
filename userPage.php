<?php
    include 'database\database.php';
    $mail = 'zhario.zhang';
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
        include "./header.html"
        ?>
    </div>

    <link rel="stylesheet" href="./css/button.css">
    <link rel="stylesheet" href="./css/font.css">
    <style> th, td { padding: 10pt; } </style>

    <br><div align="center">
        <h1>Pagina di <?php echo 'placeholder nome cognome'?></h1>
        <h2>Certificati di cui sei in possesso</h2>
    </div><br>
    
    <div align="center">
        <?php 
            $conn = connect_database();
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
                        <tr><th><?php if($row[0] != null) echo "Certificato generico </th><th style='color:#363636;'> Visualizza pdf" ?></th></tr>
                        <tr><th id="att_sp"><?php if($row[1] != null) echo "Certificato specifico (scadrà il " . $row[2] . ") </th><th style='color:#363636;'> Visualizza pdf" ?></th></tr>
                    </table>
                <?php
            }
            if($row[1] != null){
                $conn = connect_database();
                $statement = $conn->prepare("SELECT mail
                                            FROM  personale_generale_attestato_specifico_in_scadenza
                                            WHERE mail = '" . $mail . "'");
                $statement->execute();
                $in_scad_results = $statement->get_result();

                if($in_scad_results->num_rows != "0")
                    echo "<div style='color:#700016;>Attenzione: il certificato specifico è in scadenza !</div>";
            }

        ?>
</body>
</html>