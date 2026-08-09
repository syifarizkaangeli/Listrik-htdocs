<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "listrik";


$koneksi = new mysqli(
    $host,
    $user,
    $pass,
    $db
);


/*
|--------------------------------------------------------------------------
| CEK KONEKSI DATABASE
|--------------------------------------------------------------------------
*/

if ($koneksi->connect_error) {

    die(
        "Koneksi database gagal: "
        . $koneksi->connect_error
    );

}


/*
|--------------------------------------------------------------------------
| SET CHARSET
|--------------------------------------------------------------------------
*/

$koneksi->set_charset("utf8mb4");

?>