<?php

$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "db_siz_lazisba";

global $mysqli;
$mysqli = new mysqli($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
