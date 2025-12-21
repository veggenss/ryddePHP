<?php
session_start();

require '../db.php';
require '../Services/AuthService.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$conn = dbConnection();

$authService = new AuthService($conn);

switch($action){
    case "usernameExists":
        $username = $action['username'];
        $response = $authService->usernameExists($username);
        echo json_encode(["status" => $response]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Ukjent action"]);
}