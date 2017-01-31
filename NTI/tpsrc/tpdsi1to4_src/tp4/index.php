<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen">
  <title>YAHD - Page d'accueil</title>
</head>

<body>
<?php include 'login/loginAccess.inc.php'; ?>

<a href="editTicket.php">Créer un nouveau ticket</a> <br/>
<a href="showAllTickets.php">Voir tous les tickets</a> <br/>
<a href="?logout=1" id="deconnexion">Se déconnecter</a>

</body>
</html>