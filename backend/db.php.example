<?php
function dbConnection()
{
    $db_server = "localhost";
    $db_user = "user_name";
    $db_pass = "user_password";
    $db_name = "db_name";
    $conn = "";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}