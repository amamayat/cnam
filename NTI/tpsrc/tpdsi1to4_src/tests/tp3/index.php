<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>YAHD tp3 Tests</title>
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
	Il est possible d'ex&eacute;cuter le <a target="_blank"
			href="<?php echo $urlRoot;?>CreateTicketTests.php">test
				de recette de cr&eacute;ation d'un ticket par le web</a>.
</body>
</html>