<?php
session_start();
?>

<h2>Enquête</h2>

<?php
include 'login/loginAccess.inc.php';
?>

Bienvenue <?php echo $_SESSION['userId']; ?>

<br>
<a href="?logout=1">Se déconnecter</a>