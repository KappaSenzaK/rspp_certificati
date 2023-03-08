<?php

$DEBUG = true;

include '../database/database.php';

$request = json_decode(file_get_contents('php://input', true));

$password_db = obtain_password($request->email);
$hashed_password = hash('sha256', $request->password);

// perche i dati predifiniti non sono crittografati (digest)
if ($DEBUG) $password_db = hash('sha256', $password_db);

if ($password_db != $hashed_password){
    echo json_encode(array("status" => 401, "message" => "password is not valid"));
}
else {
    echo json_encode(array("status" => 200, "message" => "password ok"));
}

