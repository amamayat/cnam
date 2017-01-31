<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
  <link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen"/>
  <title>YAHD - Saisie Anomalie</title>
</head>

<body>
<?php include 'login/loginAccess.inc.php'; ?>
<?php
function getAllApplisAsOptions() {
  $apps = getAppList();
  $result = '<option value="-1">--Autre--</option>';
  foreach ( $apps as $app )
    $result .= "<option>$app</option>\n";
  return $result;
}
function getAppList() {
  return getAppListFromFile('app.csv');
}
function getAppListFromFile($filePath) {
	$result = array();
	if( $fd = fopen($filePath, 'r')) { // ouverture du fichier en lecture
		while ( ! feof($fd) ) { 	     // teste la fin de fichier
			$line = trim(fgets($fd));
			if ( $line ) {
				list($appId, $appName) = explode(';', $line);
				$appName = trim($appName);
				$result[] = $appName;
			}
		}
		fclose ($fd); 		             // fermeture du fichier
	} else {
		die("Ouverture du fichier <b>$filePath</b> impossible.");
	}
	return $result;
}

$allApplisAsOptions = getAllApplisAsOptions(); 
include 'editTicketForm.php';
?>
<a href="index.php" id="home">Page d'accueil</a><br/>
<a href="?logout=1" id="deconnexion">Se déconnecter</a>

</body>
</html>
