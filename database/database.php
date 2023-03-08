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

function obtain_password($mail): string {
    $conn = connect_database();
    $statement = $conn->prepare('SELECT pw FROM personale WHERE mail = ?');
    $statement->bind_param('s', $mail);
    $statement->execute();

    $results = $statement->get_result();
    $row = $results->fetch_assoc();

    return hash('sha256', $row['pw']);
}