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
            $status = mysqli_fetch_row($results);
            if($status[0] == 'Da compilare') {
                if(isset($_POST['add_cert'])) {
                    include './userForm.php';
                    die();
                }
            }

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
                <?php
                if($status[0] == 'Da compilare') {
                    if(isset($_POST['fine_cert'])) {
                        aggiornaStato($mail, 'Da validare');
                        echo 'Hai inserito tutti i tuoi certificati!<br>';
                    }
                    else {
                        ?>
                            <form method="post" action="userPage.php">
                                <input type="hidden" name="fine_cert" id="fine_comp" value="1">
                                <input class="button" type="submit" value="Hai inserito tutti i tuoi certificati?">
                            </form>
                            &emsp;
                            <form method="post" action="userPage.php">
                                <input type="hidden" name="add_cert" id="add_cert" value="1">
                                <input class="button" type="submit" value="Aggiungi un nuovo certificato">
                            </form><br><br>
                        <?php
                    }
                }
                ?>
                    <h2>Certificati di cui sei in possesso</h2>
                    
                <?php
                $statement = $conn->prepare("SELECT tipo AS t, descrizione AS d, data_scadenza AS d_s
                                            FROM attestato
                                            WHERE mail = '" . $mail . "'");
                $statement->execute();
                $results = $statement->get_result();

                $row = mysqli_fetch_row($results);

                if($row == null) echo "<h2 style='color:#700016;'>Non hai nessun certificato !</h2>";
                else {
                    ?>
                        <table style='background-color:#e8e8e8;'>
                        <tr><th>Tipologia</th><th>Descrizione</th><th>Data di scadenza</th></td>
                    <?php
                    do {
                        ?>
                            <tr><td><?=$row[0]?></td><td><?=$row[1]?></td><td><?php if($row[2]==null) echo '---'; else echo $row[2]; ?></td><td> <form><input type="hidden" id="tipo" name="tipo" value="<?=$row[0]?>"><input type="submit" value=" Visualizza PDF "></form> </td><tr>
                        <?php
                    } while($row = mysqli_fetch_row($results));
                    ?>
                        </table>
                    <?php
                }
                // if($row[1] != null){
                //     $statement = $conn->prepare("SELECT mail
                //                                 FROM  personale_generale_attestato_specifico_in_scadenza
                //                                 WHERE mail = '" . $mail . "'");
                //     $statement->execute();
                //     $in_scad_results = $statement->get_result();

                //     if(mysqli_num_rows($in_scad_results) != "0")
                //         echo "<div style='color:#700016;'>Attenzione: il certificato specifico è in scadenza !</div>";
                //     else {
                //         $statement = $conn->prepare("SELECT mail
                //                                      FROM  personale_generale_attestato_specifico_scaduto
                //                                      WHERE mail = '" . $mail . "'");
                //         $statement->execute();
                //         $scad_results = $statement->get_result();

                //         if(mysqli_num_rows($scad_results) != "0")
                //         echo "<div style='color:#700016;'>Attenzione: il certificato specifico è scaduto !</div>";
                //     }
                // }
            ?>
            <br><br><br>
            <button id="helpButton" class="button" onclick="help()"> Hai bisogno di aiuto? </button>&emsp;
            
            <?php
                if($status[0] == "Validato") {
                    ?>
                        <button class="button" onclick="window.open('userPageRequestedModify.php'); window.close()"> Richiedi una modifica </button>
                    <?php
                }
            ?>
            <br><br>
            <div id="help"></div>
        <script src="./js/user_page.js"></script>
        <?php
    ?>
    </div>
</body>
</html>