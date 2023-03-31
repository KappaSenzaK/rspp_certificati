<?php

function saveFileToWebServer($mail, $file, $type) {

    if($file['error'] != 0) {
        return 'error';
    }
    $mail_directory_path = str_replace(".", "_", $mail); //impossibile creare directory con '.' nel nome

    $filepath_directory = __DIR__ . "/certificati/$mail_directory_path/$type";
    $filename = basename($file['name']);
    $target_file = $filepath_directory.'/'.$filename;

    if(file_exists($target_file)) {
        die("<h1>Il file esiste di gia</h1>");
    }

    $ext = strtoupper(pathinfo($target_file, PATHINFO_EXTENSION));
    if(!is_dir($filepath_directory)) {
        mkdir($filepath_directory, 0755, true);
    }

    if(!move_uploaded_file($file["tmp_name"], $target_file)){
        echo "<h1>Errore generico nel caricamento del file";
    }
}