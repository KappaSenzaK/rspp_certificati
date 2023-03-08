<?php
include 'database.php';

function login($email, $password){
    $password_db = obtain_password($email);
    $hashed_password = hash('sha256', $password);

    if ($password_db != $hashed_password){
        return json_decode('{"status": "Passowrd not valid"}, "http_status": 401}');
    }
    return json_decode('{"status": "Password ok", "http_status": 200}');
}