<?php

include 'database/database.php';

function get_enum_values($table, $field)
{
    $conn = connect_database();

    $query  = "SHOW COLUMNS FROM ".$table." WHERE Field = '".$field."'";
    $result = mysqli_query($conn, $query);

    $row  = mysqli_fetch_array($result);
    $type = $row['Type'];

    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);

    mysqli_close($conn);

    return $enum;
}

function personale_tipo()
{
    return get_enum_values('personale', 'tipo');
}

function personale_stato()
{
    return get_enum_values('personale', 'stato');
}

function personale_in_servizio()
{
    return get_enum_values('personale', 'in_servizio');
}

function attestato_tipo()
{
    return get_enum_values('attestato', 'tipo');
}