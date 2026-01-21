<nav>
    <ul>
        <li><a href="#">Oppgaver</a></li>
        <li><a href="podium.php">Podium</a></li>
        <li><a href="family.php">Familie</a></li>
        <li><a href="profile.php">Profil</a></li>
        <?php if ($_SESSION['role'] === 1) echo '<li><a href="administrate.php">Administrer</a></li>';?>
    </ul>
</nav>