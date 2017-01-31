<?php
session_start();
if ( !isset($_SESSION['userId']) ) {
  header('Location: editTicket.php');
  exit();
}
?>
<?php
function printTicketAsTableRow($applicationName, $priorityText, $type, $date, $oneLiner, $description, $tableRecordClass){
	$str = <<<EOT
    <tr class="$tableRecordClass">
      <td class="center">$applicationName</td>
      <td class="center">$priorityText</td>
      <td class="center">$type</td>
      <td class="center">$date</td>
      <td class="left">$oneLiner</td>
      <td class="left">$description</td>
    </tr>
EOT;
    echo $str;
}

function saveTicket($applicationId, $login, $priorityId, $type, $date, $oneLiner, $description, $attachmentName) {
  // construction de la ligne à sauvegarder
  $ticketId = 0;
  $line = implode(';', array($ticketId, $applicationId, $login, $priorityId, $type, $date, $oneLiner, $description, $attachmentName));
  $filePath = 'tickets.csv';
  if( $fd = fopen($filePath, 'a+')) { // ouverture du fichier en écriture 'append'
    $line .= "\n";     // concaténation d'un saut de ligne
    fputs($fd, $line); // écriture de la ligne en fin de fichier
    fclose ($fd); 		             // fermeture du fichier
  } else {
    die("Ouverture du fichier <b>$filePath</b> impossible.");
  }
}
function saveAttachedFile() {
  $destinationPath = '../attachements/' . $_FILES['userfile']['name'];
  move_uploaded_file($_FILES['userfile']['tmp_name'], $destinationPath);
}
// Controle de l'adresse IP du navigateur client
list($ip1,$ip2,$ip3,$ip4) = explode('.', $_SERVER['REMOTE_ADDR']);
$domain = "$ip1.$ip2";
$expectedDomain = $domain; // A remplacer par un domaine : '163.173'
if ( $domain != $expectedDomain ) {
  echo 'Accès interdit';
  exit;
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
// date
$todayDate = date('d/m/Y');

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
// sauvegarde du ticket
$attachmentName = '';
if ( isset($_FILES['userfile']) )
  $attachmentName = $_FILES['userfile']['name'];
$login = $_SESSION['userId'];
saveTicket($application, $login, $priority, $type, $todayDate, $oneLiner, $contents, $attachmentName);
if ( isset($_FILES['userfile']) )
  saveAttachedFile();
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

     <?php printTicketAsTableRow($application, $priorityText, $type, $todayDate, $oneLiner, $contents, $tableRecordClass); ?>

  </tbody>
</table>

</body>

</html>
