<nav>
    <ul>
        <li><a href="tasks.php">Oppgaver</a></li>
        <li><a href="podium.php">Podium</a></li>
        <li><a href="family.php">Familie</a></li>
        <li><a href="profile.php">Profil</a></li>
        <?php if ($_SESSION['privilege'] === 1) echo '<li><a href="administrate.php">Administrer</a></li>';?>
    </ul>
</nav>