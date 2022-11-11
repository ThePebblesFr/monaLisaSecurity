<?php
    $config_file = (object) parse_ini_file("config.ini");

    $servname = $config_file->servername;
    $user = $config_file->username;
    $pwd = $config_file->password;
    $bddname = $config_file->database;
    $table = $config_file->table;

    $bddConn = new PDO('mysql:host='.$servname.';dbname='.$config_file->database.';charset=utf8', $config_file->username, $config_file->password);
?>