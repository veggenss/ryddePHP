<?php
require_once "db.php";
$mysqli = dbConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!preg_match('/^.{4,}$/', $_POST['username'])) {
        $error = "Brukernavnet må være minst 4 tegn.";
    }
    elseif(preg_match('/[ ]/', $_POST['username'])) {
        $error = "Brukernavnet kan ikke ha mellomrom";
    }
    else{
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);

        $sql = "SELECT username FROM user WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows >= 1){
            $error = "Brukernavnet finnes allerede";
        }
        else {
            if (!preg_match('/^.{4,}$/', $_POST['password'])) {
                $error = "Passordet må være minst 4 tegn.";
            }
            // elseif (!preg_match('/(?=.*\w)(?=.*\d)/', $_POST['password'])) {
            //     $error = "Passordet må ha minst 1 bokstav og 1 tall.";
            // }
            elseif (preg_match('/[ ]/', $_POST['password'])) {
                $error = "Passordet kan ikke inneholde mellomrom.";
            }
            else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // lager brukeren i databasen
                $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ss", $username, $password);

                if ($stmt->execute()) {
                    $success = "Du er nå registrert";
                } else {
                    $error = "Kunne ikke registrere bruker";
                }
                $stmt->close();
            }
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
