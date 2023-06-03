<?php
    if(!isset($database_set))
        include 'database\database.php';
    session_start();
    include 'checkPage.php';
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

        $statement = $conn->prepare("SELECT in_servizio
                                    FROM personale
                                    WHERE mail = '" . $mail . "'");
        $statement->execute();
        $results = $statement->get_result();
        $in_servizio = mysqli_fetch_row($results);
        if($in_servizio[0] == 'no') {
           echo "Attenzione! Il tuo account è stato contrassegnato come 'non in serivizio', per questo motivo non potrai richiedere modifiche ai tuoi dati.<br>Se pensi che ci sia un errore, contatta l'amministratore di rete.";
        }

        if(isset($_GET['tipo'])) {
            if(isset($_GET['file'])) {
                die();
            }
        }

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
                                <input type="hidden" name="fine_cert" id="fine_comp" value="...">
                                <input title="Attenzione! Una volta confermato l'inserimento di tutti i dati, potrai inserirne di nuovi solo dopo aver effettuato una richiesta all'amministratore!" class="button" type="submit" value="Hai inserito tutti i tuoi certificati?">
                            </form>
                            &emsp;
                            <form method="post" action="userPage.php">
                                <input type="hidden" name="add_cert" id="add_cert" value="...">
                                <input title="Carica un nuovo certificato sulla piattaforma" class="button" type="submit" value="Aggiungi un nuovo certificato">
                            </form><br><br>
                        <?php
                    }
                }
                ?>
                    <h2>Certificati di cui sei in possesso</h2>
                    
                <?php
                $statement = $conn->prepare("SELECT tipo AS t, descrizione AS d, data_scadenza AS d_s, file_allegato AS f
                                            FROM attestato
                                            WHERE mail = '" . $mail . "'");
                $statement->execute();
                $results = $statement->get_result();

                $row = mysqli_fetch_row($results);

                if($row == null) echo "<h2 style='color:#700016;'>Non hai nessun certificato !</h2>";
                else {
                    ?>
                        <table style='background-color:#e8e8e8;'>
                        <tr><th>Tipologia</th><th>Descrizione</th><th>Data di scadenza</th></tr>
                    <?php
                    do {
                        ?>
                            <tr><td><?=$row[0]?></td><td><?=$row[1]?></td><td><?php 
                                    if($row[2]==null) echo '---'; 
                                    else {
                                        $statement = $conn->prepare("select p2.mail, a_s.tipo, a_s.data_scadenza 
                                                from (`personale` `p2` join `attestato` `a_s` on(`a_s`.`data_scadenza` is not null AND `a_s`.`mail` = `p2`.`mail`)) 
                                                where p2.mail = '$mail' AND a_s.tipo = '$row[0]' AND a_s.descrizione = '$row[1]' AND
                                                    `a_s`.`data_scadenza` < curdate();");
                                        $statement->execute();
                                        $scaduto = $statement->get_result();

                                        $scaduto = mysqli_fetch_row($scaduto);

                                        if($scaduto == null)
                                            echo $row[2];
                                        else
                                            echo "<span style='color: #ff0000'>$row[2]</span>";
                                    } 
                                ?></td><td>
                                <?php 
                                    echo '<a href="./certificati/' . str_replace('.', '_', $mail) . '/' . $row[0] . '/' . $row[3] . '">Visualizza PDF</a>';
                                    
                                    
                                ?>
                                </td>
                            <tr>
                        <?php
                    } while($row = mysqli_fetch_row($results));
                    ?>
                        </table>
                    <?php
                }
            ?>
            <br><br><br>
            <button id="helpButton" class="button" onclick="help()"> Hai bisogno di aiuto? </button>&emsp;
            
            <?php
                if($status[0] == "Validato") {
                    $statement = $conn->prepare("SELECT in_servizio
                                    FROM personale
                                    WHERE mail = '" . $mail . "'");
                    $statement->execute();
                    $results = $statement->get_result();
                    $in_servizio = mysqli_fetch_row($results);
                    if($in_servizio[0] == 'si') {
                        ?>
                            <button class="button" title="Richiedi all'amministratore di rete la possibilità di modificare i tuoi dati" onclick="window.open('userPageRequestedModify.php'); window.close()"> Richiedi una modifica </button>
                        <?php
                    }
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