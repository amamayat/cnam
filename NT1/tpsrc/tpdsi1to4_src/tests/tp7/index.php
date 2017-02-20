<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>YAHD tp7 Tests</title>
</head>
<body>

<?php 
$requestURI = dirname($_SERVER['REQUEST_URI']);
$requestURI = $_SERVER['REQUEST_URI'];
$requestURI = str_replace('index.php', '', $requestURI);
$urlRoot = 'http://' . $_SERVER['SERVER_NAME'] . ':' 
		. $_SERVER['SERVER_PORT'] . $requestURI;
?>
	<h2>Ex&eacute;cution des tests</h2>
	Il est possible d'ex&eacute;cuter individuellement chacun des tests :
	<br />

	<ol>
		<li><a target="_blank"
			href="<?php echo $urlRoot;?>TicketTest.php">test
				unitaire de la classe Ticket</a></li>
		<li><a target="_blank"
			href="<?php echo $urlRoot;?>MySQLiTicketDAOTest.php">test
				unitaire de la classe MySQLiTicketDAO</a></li>
		<li><a target="_blank"
			href="<?php echo $urlRoot;?>CreateTicketTests.php">test
				de recette de cr&eacute;ation d'un ticket par le web</a></li>
		<li><a target="_blank"
			href="<?php echo $urlRoot;?>ShowAllTicketTest.php">test
				de recette de visualisation de tous les&nbsp;tickets par le web</a></li>
	</ol>
	On peut aussi
	<a target="_blank" href="<?php echo $urlRoot;?>AllTests.php">lancer
		l'ex&eacute;cution de tous les tests</a>.
	<br />
</body>
</html>