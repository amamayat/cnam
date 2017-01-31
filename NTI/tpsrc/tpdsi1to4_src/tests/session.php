<?php session_start();
if (isset($_REQUEST["nom"]))  { $_SESSION["nom"] = $_REQUEST["nom"];}
if (isset($_REQUEST["delete"]))  { session_destroy();}
?>
<html>
<head>
<title>Test session</title>
</head>
<body>
<ul>
	Paramètres de session :
	<li>Identifiant session : <?php echo session_id();?>
	
	
	<li>Durée de vie d' une session : <?php echo session_cache_expire();?>
	
	
	<li>Emplacement des sessions : <?php echo session_save_path() ?>
	
	
	<li>Nom session : <?php echo session_name();?>

</ul>
<p>Bonjour <b><?php echo $_SESSION["nom"]; ?> </b></p>
<ul>
	Contenu des variables de session:
	<?php
	while (list ($key, $val) = each ($_SESSION))
	{ echo "<li> $key => <B>$val</B>";}?>
</ul>

<form action="session.php" method="post">Votre nom: <input type="text"
	size="6" name="nom"> <input type="submit"></form>
<a href="session.php?delete=1">Supprimer la session</a>
</body>
</html>
