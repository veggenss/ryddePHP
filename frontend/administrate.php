<?php
session_start();
require_once '../backend/db.php';
require_once '../backend/Services/GroupService.php';
require_once '../backend/Services/TaskService.php';
require_once '../backend/Services/TrophyService.php';

// include 'navigation.php';

if(!isset($_SESSION['user_id']) || $_SESSION['privilege'] !== 1){
    header('Location: login.php');
}

$conn = dbConnection();

$groupService = new GroupService($conn);
$taskService = new TaskService($conn);
$trophyService = new TrophyService($conn);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <title>Rydde hjelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <script src="js/administrate.js" defer></script>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/index.css">

</head>
<body>
<?php
    if(isset($error)){
        echo '<div class="error">$error</div>';
    }
    if(isset($success)){
        echo '<div class="positive">$success</div>';
    }
?>
<div class="window">
    <ul class="tab-list">
        <li><button class="tab-btn active" data-tab="tasksTab">Tasks</button></li>
        <li><button class="tab-btn" data-tab="usersTab">Users</button></li>
        <li><button class="tab-btn" data-tab="groupsTab">Groups</button></li>
    </ul>

    <div class="tab active" id="tasksTab">
        <div class="task-action-con">
            <button class="task-action-button" id="taskEdit">Edit</button>
            <button class="task-action-button" id="taskHide">Hide</button>
            <button class="task-action-button" id="taskDelete">Delete</button>
        </div>
        <div class="task-list"></div>
    </div>

    <div class="tab" id="usersTab">
        <div class="user-action-con">
            <button class="user-action-button" id="adminPromote">Promote admin</button>
            <button class="user-action-button" id="adminDemote">Demote admin</button>
            <button class="user-action-button" id="userTimeout">Timeout</button>
            <button class="user-action-button" id="userRestrict">Restrict</button>
            <button class="user-action-button" id="userDelete">Delete</button>
        </div>
        <div class="user-list"></div>
    </div>

    <div class="tab" id="groupsTab">
        <div class="group-action-con">
            <button class="group-action-button id=""></button>
            <button class="group-action-button id=""></button>
            <button class="group-action-button id=""></button>
        </div>
        <div class="group-list"></div>
    </div>
</div>
</body>
</html>