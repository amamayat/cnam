<?php
session_start(); 
?>
<?php include 'login/loginAccess.inc.php'; ?>
<?php
include_once 'Ticket.class.php';
include_once 'AppService.class.php';

$priorityColorMap = array(
		  5 => 'red', // 'Très urgente'
		  4 => 'orange', // 'Urgente'
		  3 => 'yellow', //'Moyenne'
		  2 => 'blue', // 'Faible'
		  1 => 'green' // 'Très faible'
);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
  <title>YAHD - Voir tous les tickets</title>
  <link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen"/>
  <script type="text/javascript" language="javascript" src="../js/jquery.js"></script>

<script type="text/javascript" >
function masquerCouleur(color){
  var cssClassName = "tab_bg_" + color;
  masquerStyle(cssClassName);
}
function demasquerCouleur(color){
  var cssClassName = "tab_bg_" + color;
  demasquerStyle(cssClassName);
}
function masquerStyle(cssClassName){
  var selector = "." + cssClassName;
  $(selector).hide();
}
function demasquerStyle(cssClassName){
  var selector = "." + cssClassName;
  $(selector).show();
}
</script>

</head>

<body>

<h2>Liste de tous les tickets ouverts</h2>

Masquer les 
   <a id="hideR"  href="javascript:masquerCouleur('yellow');">jaunes</a>,
   <a id="hideR"  href="javascript:masquerCouleur('blue');">bleus</a>
| D&eacute;masquer les 
   <a id="hideR"  href="javascript:demasquerCouleur('yellow');">jaunes</a>,
   <a id="hideR"  href="javascript:demasquerCouleur('blue');">bleus</a>

<table id="buglist" border="1" cellspacing="1">

    <tr>

      <th>Application</th>

      <th>Priorit&eacute;</th>

      <th>Type</th>

      <th>Date</th>

      <th>R&eacute;sum&eacute;</th>

      <th>Description</th>

    </tr>

  <tbody>

<?php 
     $allTickets = AppService::getAllTickets();
     foreach ( $allTickets as $ticket ) {       
        $tableRecordClass = 'tab_bg_' . $priorityColorMap[$ticket->getPriority()];
        AppService::printTicketAsTableRow($ticket, $tableRecordClass); 
     }
?>

  </tbody>
</table>

<a href="index.php" id="home">Page d'accueil</a><br/>
<a href="?logout=1" id="deconnexion">Se déconnecter</a>


</body>

</html>
