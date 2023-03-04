<?php
    function connect_database($server_name = "localhost",
                            $username = "root",
                            $password = "",
                            $database = "rspp_certificati")
    {
        $conn = new mysqli($server_name, $username, $password);
        if($conn->connect_error){
            die("Connessione fallita: " . $conn->connect_error);
        }

        $retval = mysqli_select_db( $conn, $database );
        if(! $retval ) {
            die('Impossibile selezionare il database: ' . mysqli_error($conn));
        }

        return $conn;
    }

    function make_query($query){
        $conn = connect_database();

        if ($result = $conn->query($query)) {
            $conn->close();
            return $result;
        }
        $conn->close();
        return null;
    }

    function obtain_password($mail) {
        $results = make_query("SELECT mail, pw FROM personale");

        foreach($results as $row) {
            if($row['mail'] == $mail)
                return $row['pw'];
        }
        return null;
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Massimo Cuccurullo Official Fan Page

    membri:<br>
    <?php
        $results = make_query("SELECT * FROM personale");

        foreach($results as $row) {
            echo $row['nome'] . " " . $row['cognome'] . "<br>";
        }
    ?>
</body>
</html>