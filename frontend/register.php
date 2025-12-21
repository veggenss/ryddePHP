<?php
session_start();
require_once '../backend/db.php';
require_once '../backend/Services/AuthService.php';

if($_SESSION['user_id']){
    header('Location: tasks.php');
    exit();
}

$conn = dbConnection();
$authService = new AuthService($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if(strlen($username) <= 1) {
        $error = "Brukernavnet må være minst 2 tegn.";
    }
    elseif($authService->usernameExists($username)){
        $error = "Brukernavnet finnes allerede";
    }
    elseif(strlen($password) < 4){
        $error = "Passordet må være minst 4 tegn";
    }
    else{
        $userData = ['username' => $username, 'password' => $password];
        if ($authService->registerUser($userData)) {
            $success = "Du er nå registrert!";
        }
        else{
            $error = "Noe gikk galt!";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <title>Rydde hjelper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/logRegStyle.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="auth-container">

            <h1 class="auth-title">Registrer Bruker</h1>

            <?php if(isset($error)):?>
                <div class="error"><?php echo $error;?></div>
            <?php elseif(isset($success)):?>
                <div class="positive"><?php echo $success;?></div>
            <?php endif;?>

            <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <p>Brukernavn</p>
                <input placeholder="Brukernavn" type="text" name="username">

                <p>Passord</p>
                <input placeholder="Passord" type="password" name="password">

                <button type="submit">Registrer</button>
            </form>

            <div class="auth-link">
                Trykk <a href="login.php">her</a> for å logge inn
            </div>

        </div>
    </div>
</body>
</html>
