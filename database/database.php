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