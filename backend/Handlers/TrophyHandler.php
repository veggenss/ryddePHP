<?php
session_start();
require '../db.php';
require '../Services/TrophyService.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$conn = dbConnection();

$trophyService = new TrophyService($conn);

switch($action){
    case 'trackTrophy':
        break;

    case 'completeTrophy':
        break;

    default:
        echo json_encode(["success" => false, "message" => "ukjent action"]);
        break;
}