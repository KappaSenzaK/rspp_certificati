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
    <title>Password dimenticata</title>
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="font">
    <div align="center">
        <br>
            <?php
                if(!isset($_POST['email'])) {
                    ?>
                        <form method="post" action="forgot_password.php">
                            Inserisci il tuo indirizzo e-mail istituzionale
                            <table class="button" >
                                <tr align="left">
                                    <th >
                                        <label for="email">Email: </label>
                                    </th>
                                    <th>
                                        <input type="text" id="email" name="email" required><label for="email">@tulliobuzzi.edu.it </label>
                                    </th>
                                </tr>

                            </table>
                            <br><br>
                            <input class="button" type="submit" value="Invia il modulo" name="loginAction" id="loginAction">
                        </form>
                    <?php
                }
                else {
                    try {
                        $statement = $conn->prepare("SELECT mail
                                    FROM personale
                                    WHERE mail = '" . $_POST['email'] . "'");
                        $statement->execute();
                        $results = $statement->get_result();
                        if($results == false || $results == null)
                            throw new exception();

                        $results = mysqli_fetch_row($results);
                        if($results == false || $results == null)
                            throw new exception();

                        $digestPassword = substr(hash(
                            'md5',
                            $_POST['email'] . (New DateTime())->format('Y-m-d H:i:s')),
                            0, 5
                        );

                        $sendmail_message = sendEmail(
                            $_POST['email'] . "@tulliobuzzi.edu.it",
                            "Credenziali RSPP Certificati",
                            "<h1>Password per l'accesso all'account: " . $digestPassword . "</h1>"
                        );

                        if ($sendmail_message != EmailStatus::OK) {
                            echo "<h2>" . $sendmail_message . "</h2>";
                            die();
                        }

                        $_SESSION['signin'] = true;
                        $_SESSION['code'] = $digestPassword;

                        $statement = $conn->prepare("UPDATE personale
                                    SET pw = .". hash("sha256", $pw) . " 
                                    WHERE mail = '" . $_POST['email'] . "'");
                        $statement->execute();

                        ?>
                            <h2>All'indirizzo e-mail <?php echo $email; ?>@tulliobuzzi.edu.it
                                è stata inviata la tua nuova password</h2> <br> 
                            <?php
                        }
                        catch (exception $e) {
                                echo "Attenzione! Account non trovato!";
                            }
                        }
                    ?>
                <br><a href="index.php">Ritorna alla pagina principale</a>
    </div>
</body>
</html>

