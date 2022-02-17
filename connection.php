<?php
function openConection():?PDO {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name="messenger";
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_user, $db_pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //return $conn;
    } catch (PDOException $exception) {
        echo $exception->getMessage();
        die("Connection to database failed!");
    }
    return $conn;
}