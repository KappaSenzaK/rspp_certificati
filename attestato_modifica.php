<?php
    include './database/database.php';
    include "./html/header.html";
    include "./html/cuccurullo_page.html";

    $conn = connect_database();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica attestato</title>
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="font">
    <div align="center">
        <br>
        <h2><?php
            $email = $_POST['email'];

            $old_tipologia = $_POST['old_tipologia'];
            $old_desc = $_POST['old_desc'];

            $tipologia = $_POST['tipologia'];
            $desc = mysqli_real_escape_string($conn, htmlspecialchars($_POST['desc']));
            $data_scadenza = $_POST['data_scadenza'];

            $statement = $conn->prepare("SELECT file_allegato AS f
                                        FROM attestato
                                        WHERE mail = '" . $email . "' AND tipo = '" . $old_tipologia . "'");
            $statement->execute();
            $file_name = $statement->get_result();
            $file_name = $file_name->fetch_assoc();

            if (!isset($email) || !isset($tipologia) || !isset($desc) || !isset($data_scadenza)) {
                die("I dati inseriti per la modifica non sono validi!");
            }

            $result = modifyAttestato($email, $tipologia, $desc, $data_scadenza, $old_desc, $old_tipologia);

            if($result) {
                echo "Attestato modificato con successo";

                if(!file_exists('./certificati/' . str_replace('.', '_', $email) . '/' . $tipologia . '/' . $file_name['f']))
                {
                    if(!file_exists('./certificati/' . str_replace('.', '_', $email) . '/' . $tipologia . '/'))
                    {
                        try{
                            mkdir('./certificati/' . str_replace('.', '_', $email) . '/' . $tipologia);
                        } catch(exception $e) {}
                    }

                    rename('./certificati/' . str_replace('.', '_', $email) . '/' . $old_tipologia . '/' . $file_name['f'],
                        './certificati/' . str_replace('.', '_', $email) . '/' . $tipologia . '/' . $file_name['f']);
                }
            } else 
                echo "Impossibile modificare l'attestato";
        ?></h2>

        <a href="cuccurullo_page.php">Ritorna alla pagina principale</a>
    </div>
</body>
</html>

