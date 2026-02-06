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
    case 'createGroup':
        if($response = $groupService->createGroup($_SESSION['user_id'], $data['data'])){
            echo json_encode(["success" => true, "message" => "Familie opprettet"]);
        }
        json_encode($response);
        break;

    default:
        break;
}
