<?php
// Controle de l'adresse IP du navigateur client
// echo "***DEBUG***" . $_SERVER['REMOTE_ADDR'] . "\n";
$array = explode('.', $_SERVER['REMOTE_ADDR']);
if ( count($array) >= 4 ) {
	list($ip1,$ip2,$ip3,$ip4) = $array;
	$domain = "$ip1.$ip2";
	$expectedDomain = $domain; // A remplacer par un domaine : '163.173'
	if ( $domain != $expectedDomain ) {
		echo 'Accès interdit';
		exit;
	}
}
// récupération des données du formulaire 
$application = $_REQUEST['application'];
$applicationOther = $_REQUEST['applicationOther'];
$priority = $_REQUEST['priority'];
$type = $_REQUEST['type'];
$oneLiner = $_REQUEST['oneLiner'];
$contents = $_REQUEST['contents'];

// calcul de certains champs affichés
if ( $application == '-1' ) {
     $application = $applicationOther;
}
$contentsPart = substr($contents, 0, 40);
$todayDate = '?'; // ***TBD***

// détermination du texte associée à la priorité
$priorityTextMap = array(
		  5 => 'Très urgente',
		  4 => 'Urgente',
		  3 => 'Moyenne',
		  2 => 'Faible',
		  1 => 'Très faible'
);
$priorityText = $priorityTextMap[$priority];
// détermination de la couleur associée à la priorité
$priorityColorMap = array(
		  '' => 'red', 
		  5 => 'red', // 'Très urgente'
		  4 => 'orange', // 'Urgente'
		  3 => 'yellow', //'Moyenne'
		  2 => 'blue', // 'Faible'
		  1 => 'green' // 'Très faible'
);
$tableRecordClass = 'tab_bg_' . $priorityColorMap[$priority];

echo "IP=" . $_SERVER['REMOTE_ADDR'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
  <title>editTicketAction</title>
  <link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen"/>


</head>

<body>

<h2>Demande d'intervention bien reçue</h2>

<table id="buglist" border="1" cellspacing="1">

  <tbody>

    <tr>

      <th>Application</th>

      <th>Priorit&eacute;</th>

      <th>Type</th>

      <th>Date</th>

      <th>R&eacute;sum&eacute;</th>

      <th>Description</th>

    </tr>

    <tr class="<?php echo $tableRecordClass; ?>">

      <td class="center"><?php echo $application; ?></td>

      <td class="center"><?php echo $priorityText; ?></td>

      <td class="center"><?php echo $type; ?></td>

      <td class="center"><?php echo $todayDate; ?></td>

      <td class="left"><?php echo $oneLiner; ?></td>

      <td class="left"><?php echo $contentsPart; ?></td>

    </tr>

  </tbody>
</table>

</body>

</html>
