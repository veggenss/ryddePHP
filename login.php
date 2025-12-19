<?php
session_start();
require_once 'db.php';

$mysqli = dbConnection();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];

    // sjekker brukernavn og passord opp mot databasen
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // verifiser brukernavn og passord og lager session hvis de er riktig
    if (!$user){
        $error = "Bruker finnes ikke";
    }
    elseif(!password_verify($_POST['password'], $user['password'])) {
        $error = "Ugyldig brukernavn eller passord";
    }
    else{
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // $_SESSION['profile_picture'] = $user['profile_picture'];

        header('Location: tasks.html'); // redirecter til hovedsiden
        exit();
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

            <h1 class="auth-title">Logg Inn</h1>

            <?php if(isset($error)):?>
                <div class="error"><?php echo $error;?></div>
            <?php elseif(isset($success)):?>
                <div class="positive"><?php echo $success;?></div>
            <?php endif;?>

            <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
                <p>Brukernavn</p>
                <input placeholder="Brukernavn" type="text" name="username" required>

                <p>Passord</p>
                <input placeholder="Passord" type="password" name="password" required>

                <button type="submit">Logg inn</button>
            </form>

            <div class="auth-link">
                Trykk <a href="register.php">her</a> for å registrere bruker
            </div>
        </div>
    </div>
</body>
</html>
