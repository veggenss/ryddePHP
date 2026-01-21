<?php
session_start();
require '../db.php';
require '../Services/TaskService.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$conn = dbConnection();

$taskService = new TaskService($conn);

switch($action){

    case 'createTask':
        $response = $taskService->createTask($data['data']);
        if (!$response) {
            echo json_encode(["success" => false, "message" => "Error: Noe gikk galt!"]);
        }
        else{
            echo json_encode(["success" => true, "message" => "Oppgave opprettet!"]);
        }
        break;

    case 'loadTasks':
        $tasks = $taskService->getTasks();
        echo json_encode(["success" => true, "taskData" => $tasks, "session" => $_SESSION['user_id']]);
        break;

    case 'completeTask':
        $compName = $data['completorUsername'] ?? null;
        $taskData = $data['taskData'] ?? null;

        if ($_SESSION['user_id'] !== $taskData['author_id']){
            echo json_encode(["success" => false, "message" => "SessionID og AuthorID Passer ikke!"]);
            break;
        }

        $response = $taskService->completeTask($compName, $taskData);
        if (!$response) {
            echo json_encode(["success" => false, "message" => "Error: Noe gikk galt!"]);
        }
        else{
            echo json_encode(["success" => true, "message" => "Oppgave markert som fullført!"]);
        }
        break;

    case 'deleteTask':
        $compName = $data['completorUsername'] ?? null;
        $taskData = $data['taskData'] ?? null;

        if ($_SESSION['user_id'] !== $taskData['author_id']){
            echo json_encode(["success" => false, "message" => "SessionID og AuthorID Passer ikke!"]);
            break;
        }

        $response = $taskService->deleteTask($data['taskId']);
        if(!$response){
             echo json_encode(["success" => false, "message" => "Error: Noe gikk galt!"]);
        }
        else{
             echo json_encode(["success" => true, "message" => "Oppgave slettet!"]);
        }
        break;

    case 'getMemberTasks':
        $tasks = $taskService->getMemberTasks();
        echo json_encode(["success" => true, "taskData" => array_values($tasks)]);
        break;

    default:
        echo json_encode(["success" => false, "message" => "Ukjent action"]);
}