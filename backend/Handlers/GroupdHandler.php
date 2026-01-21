<?php
session_start();
require "../db.php";
require "../Services/GroupService.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$conn = dbConnection();

$groupService = new GroupService($conn);

switch($action){
    case '':
        break;

    default:
        break;
}
